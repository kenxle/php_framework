<?php

class Example extends BaseTable{
	

	/**
	 * The name of the table in the database. Use to construct queries
	 * @var unknown_type
	 */
	static $table_name = "example";
	
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
	static $field_nicks_map = array(
		"id" => "e_id",
		"title" => "e_title",
		"text" => "e_text",
		"created" => "e_created"		
	);
	
	/**
	 * A list of fields that can be searched. USE NICKNAMES.
	 *
	 * Implementation note: All searchable nicks should be indexed.
	 *
	 * @var unknown_type
	*/
	static $searchable_nicks = array(
		"id",
		"title",
		"text",
		"created"
	);
	
	/**
	 * A list of fields that can be sorted. USE NICKNAMES.
	 * @var unknown_type
	*/
	static $sortable_nicks = array(
		"id",
		"title",
		"text",
		"created"
	);
	
	/**
	 * A list of fields that can be edited. USE NICKNAMES.
	 * @var unknown_type
	*/
	static $editable_nicks = array(
		"title",
		"text",
	);
	
	/**
	 * An example new query using the tools of BaseTable
	 * @return unknown
	 */
	public static function getSpecificQueryThing(){
		$query = "SELECT * FROM " . static::$table_name . 
		" WHERE " . static::$field_nicks_map['text'] . 
		" LIKE '%example%' ";
		
		$classname = get_called_class();
		// run the query, and for each row, construct an object out of it
		$objs = SQL::query($query, array($classname, 'constructFromRecord'));
		$objs = static::markLoaded($objs);
		
		return $objs;
	}
}