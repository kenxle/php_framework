<?php

/**
 * 
 * A class for managing CRUD database functionality. 
 * 
 * In your extension of this class, you should fill in the following:
 * 
 * $table_name
 * $fields
 * $field_nicks_map
 * $searchable_nicks
 * $sortable_nicks
 * $editable_nicks
 * 
 * 
 * 
 * Assumption:
 * The nickname 'id' will be used to represent the primary key. 
 * If this is not the case, you can set $constructor_key to the primary key nickname. 
 * 
 * @package ShippingAPI
 * @subpackage om
 * @author kenstclair
 *
 */
class BaseTable{
	
	/**
	 * The name of the table in the database. Use to construct queries
	 * @var unknown_type
	 */
	static $table_name = "";
	
	/**
	 * All fields in this table, as they are named in the database. 
	 * @var unknown_type
	 */
	static $fields = array();
	
	/**
	 * A mapping of nicknames to actual table names, like so:
	 * array(
	 * 	"id" => "p_id",
	 * 	"nickname" => "table_column_name"
	 * )
	 * 
	 * When constructing queries, use static::$field_nicks_map['nickname'] 
	 * to represent your field names. This will give you a layer of indirection
	 * that will allow you to change your database field names without having
	 * to update all your queries. 
	 * 
	 * @var unknown_type
	 */
	static $field_nicks_map = array();
	
	/**
	 * A list of fields that can be searched. USE NICKNAMES. 
	 * 
	 * Implementation note: All searchable nicks should be indexed. 
	 * 
	 * @var unknown_type
	 */
	static $searchable_nicks = array();
	
	/**
	 * A list of fields that can be sorted. USE NICKNAMES. 
	 * @var unknown_type
	 */
	static $sortable_nicks = array();
	
	/** 
	 * A list of fields that can be edited. USE NICKNAMES. 
	 * @var unknown_type
	 */
	static $editable_nicks = array();
	
	protected $loaded = false;
	
	/**
	 * The nickname of the column used to construct a new instance. 
	 * 
	 * Usually this will be 'id'. You can customize it for different
	 * primary keys. For example, in things like user accounts tables, 
	 * you may want to change this to 'username'
	 * 
	 * This should always be a unique column, if not the primary key
	 * 
	 * @var unknown_type
	 */
	static $constructor_key = "id";
	/**
	 * Load a database entry by its constructor key. 
	 * 
	 * Constructor key defaults to 'id', but can be changed in subclasses. 
	 * 
	 * Additional options are for loading presearched data. 
	 * Pass $load=false and $row=array to load a row from 
	 * a record into this object 
	 * 
	 * @param unknown_type $id
	 */
	public function __construct($id, $load=true, $row=null){
//		DEBUG::rollcall();
		if($load === true){
			$query = "SELECT * FROM ". static::$table_name .
				" WHERE `". static::$field_nicks_map[static::$constructor_key] ."` = '$id'";
			$result = SQL::query($query);
//			if($result === false) {return false;}
//			if(mysql_num_rows($result) == 0) {return false;}
			$row = mysql_fetch_assoc($result);
			DEBUG::lvar_dump("construct. item found. loading row: ", $row);
			$this->loaded = true; // only mark loaded when we know we've loaded all the data
		}
		
		foreach(static::$field_nicks_map as $field_nick => $field){
			$this->$field_nick = $row[$field]; // field nicks are the keys in $knownData
		}
	}
	
	/**
	 * Create a new database entry
	 * 
	 * Separated from __construct because of a data leak in the previous project that hinged on conditional creation of entries based on input to the function. 
	 * 
	 * Accepts a hash of known values to put into the row, with keys as field nicknames and values as table values. 
	 * 
	 * Assumption:
	 * An autoincrement primary key will have a nickname 'id'
	 * 
	 * Assumption: 
	 * If the primary/constructor key is not autoincrement, you will have provided it in the $knowndata
	 * 
	 * @param hash $knownData array("field_nickname" => "value", etc)
	 */
	public static function create($knownData){
		FPX::contract(array(
			'required' => array(),
			'optional' => array_keys(static::$field_nicks_map) // -krs takes the widest stance possible. might should be static::$editable_nicks
		));
		$query = "INSERT INTO ". static::$table_name. " ( `". implode('`, `', static::nicks_to_fields(array_keys($knownData))) ."` ) 
				VALUES ( '". implode('\', \'', $knownData) ."' )";
		$result = SQL::query($query);
		if($result === false) return false;
		
		// get the constructor key for the new record. usually 'id'
		// Assumption: The autoincrement primary key will have the nickname 'id'
		if(static::$constructor_key == 'id'){
			$id = mysql_insert_id();
		}else{
			// Assumption: if the primary/constructor key is not autoincrement, you will have provided it in the $knowndata
			$id = $knownData[static::$constructor_key];
		}		
		
		$classname = get_called_class();
		$obj = new $classname($id);
		DEBUG::lvar_dump("created new record. classname: ", $classname);
		return $obj;
	}
	
	/**
	 * Save the values in the object to the db.
	 * 
	 * Will throw an error if it thinks that the object hasn't
	 * had all the attributes loaded and you try to save all fields. 
	 * This will occur when creating the object and populating it with 
	 * data from an externally retrieved record. Since the object didn't create 
	 * the record, it doesn't trust that it has all the object's data in it. 
	 * If it doesn't have all the data, then a save could overwrite valid data
	 * with an empty field. 
	 * 
	 * The error is bypassed by specifying which fields you want 
	 * to save. If you can specify the fields you want to save, then
	 * you've probably loaded them.  
	 * 
	 * @param array $fields_to_save field nicknames
	 */
	public function save($fields_to_save=null){
		if($fields_to_save == null){ //default fields to save
			if(!$this->loaded){ // if not loaded in full, and fields to save aren't specified
				$classname = get_called_class();
				ERROR::writeln("Class: $classname Error: Trying to save an entire object when it 
						is not clear if it is fully loaded. Please load the object via provided methods or 
						specify which fields you want to save."
				); // -krs not sure if this is the best way to handle this. i have a hunch that it isn't, but i'm not sure how else to handle the problem of partially loaded data overwriting valid data on a save. 
			}
			$fields_to_save = static::$editable_nicks;
		}
		if(!is_array($fields_to_save)){// only one name was passed. wrap it up for them. 
			$fields_to_save = array($fields_to_save);
		}
		
		$query = "UPDATE ". static::$table_name . " SET ";
		foreach($fields_to_save as $nick){
			$query .= "`" . static::$field_nicks_map[$nick] . "` = '" . $this->$nick . "', ";
		}
		$query = substr($query, 0, -2); //remove the last comma-space
		
		$query .= " WHERE `" . static::$field_nicks_map[static::$constructor_key] . "` = '" . $this->{static::$constructor_key} . "'";
		$result = SQL::query($query);
		
		return $result;
	}
	
	/**
	 * Destroy the record. 
	 * 
	 * Deletes the record from the database. 
	 * 
	 * Override to create a soft-delete. 
	 */
	public function destroy(){
		$query = "DELETE FROM ". static::$table_name .
			" WHERE `". static::$field_nicks_map[static::$constructor_key] . "` = " . $this->{static::$constructor_key};
		$result = SQL::query($query);
	}
	
	/**
	 * Set the value of a field, by nickname. 
	 * 
	 * Automatically saves the field to the db. 
	 * 
	 * Can be set "softly" by setting the $save param to false. 
	 * Data will be set in this object, but not saved in the db. 
	 * You must then manually call $this->save() to save the data to the db. 
	 * 
	 * @param $nick
	 * @param $value
	 * @param boolean $save if omitted, the value is saved to the database. if anything other than boolean true is passed, it will not save to the db. 
	 */
	public function set($nick, $value, $save=true){
		if(!in_array($nick, static::$editable_nicks)){ // illegal operation
			ERROR::writeln("Sorry, the \"$nick\" field is not editable");
			return false;
		}
		
		// set the value
		$this->$nick = $value;
//		DEBUG::lvar_dump("SET $nick to $value. OBJ: ", $this);
		
		// optionally save the item to the db now. 
		if($save === true){
			return $this->save(array($nick));
		}
		
	}
	
	
	/**
	 * Basic search for records
	 * 
	 * This function allows two basic types of search: 
	 * 1) The most generic search -> search all $searchable_nicks for a phrase that contains the search term. (field LIKE '%term%' OR field LIKE '%term%')
	 * 2) The most restrictive search -> search only the listed fields for the exact values provided. (field = term AND field = term)
	 * 
	 * All other implementations need to be written for each class. Please do not let your SQL
	 * queries creep out of the objects and into the controllers. 
	 * 
	 * Returns an array of instantiated objects. 
	 * **An empty result set will return an empty array. 
	 * 
	 * -krs Should I write this? Or leave most of the searching to be written independently so the sql doesn't creep into the controller. 
	 * 
	 * @param unknown_type $searchHash
	 */
	public static function search($searchHashOrTerm){
		if(is_array($searchHashOrTerm)){ // search only fields specified in the hash
			$searchHash = $searchHashOrTerm;	
			return static::targetedSearch($searchHash);
		}else{ // search all searchable fields for the term
			$searchTerm = $searchHashOrTerm;
			return static::genericSearch($searchTerm);
		}
	}
	
	/**
	 * retrieve all rows from the table
	 */
	public static function getAll(){
		$query = "SELECT * FROM " . static::$table_name;
		
		$classname = get_called_class();
		$objs = SQL::query($query, array($classname, 'constructFromRecord'));
		$objs = static::markLoaded($objs);
		
		return $objs;
	}
	
	/**
	 * Search only the listed fields for the exact values
	 * 
	 * @param unknown_type $searchHash
	 */
	protected static function targetedSearch($searchHash){
		
		$query = "SELECT * FROM ". static::$table_name . " WHERE ";
		foreach($searchHash as $nick=>$value){
			$query .= "`" . static::$field_nicks_map[$nick] . "` = '" . $value . "' AND ";
		}
		$query = substr($query, 0, -4);// remove the last AND
		
		$classname = get_called_class();
		$objs = SQL::query($query, array($classname, 'constructFromRecord'));
		$objs = static::markLoaded($objs);
		
		return $objs;
	}
	
	/**
	 * Search any of the searchable fields for the search term. 
	 * 
	 * @param $searchTerm
	 */
	protected static function genericSearch($searchTerm){
		
		$query = "SELECT * FROM ". static::$table_name . " WHERE ";
		foreach(static::$searchable_nicks as $nick){
			$query .= "`" . static::$field_nicks_map[$nick] . "` LIKE '%" . $searchTerm . "%' OR ";
		}
		$query = substr($query, 0, -4); //remove the last OR
		
		return static::processQueryToObjects($query);
	}
	
	/**
	 * A utility function to hold the frequently repeated 
	 * code that happens when you write a simple query
	 * and need it processed to objects. 
	 * 
	 * Since it is marking the objects as loaded, please make
	 * sure that your query is "SELECT *..." if you are using
	 * this to load the objects. Objects that are incorrectly
	 * marked as loaded can destroy data in the database when
	 * saved. 
	 * 
	 * @param unknown_type $query
	 */
	protected static function processQueryToObjects($query){
		$classname = get_called_class();
		$objs = SQL::query($query, array($classname, 'constructFromRecord'));
		$objs = static::markLoaded($objs);
		
		return $objs;
	}
	
	/**
	 * Process an individual search result row into an object
	 * 
	 * Left public because of scoping issues when passing functions around
	 * 
	 * @param unknown_type $row
	 */	
	public static function constructFromRecord($row){
		$classname  = get_called_class();
		return new $classname(-1, false, $row);
	}
	
	/**
	 * Mark the objects as properly loaded. 
	 * 
	 * Call this only when you have set all of the attributes on 
	 * the object from the database.
	 * 
	 * @param unknown_type $objs
	 */
	protected static function markLoaded($objs){
		foreach($objs as $obj){
			$obj->loaded = true;
		}
		return $objs;
	}
	
	/**
	 * Returns an array of the actual field names, given an array of the nicknames.
	 * @param unknown_type $arr
	 */
	protected static function nicks_to_fields($arr){
		$func = function(&$el, $key, $map){
			$el = $map[$el];
		};
		array_walk($arr, $func, static::$field_nicks_map);
		return $arr;
	}
	
}