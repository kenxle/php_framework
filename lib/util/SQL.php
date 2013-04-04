<?php
/**
 * A class that does all the extra bookkeeping surrounding my_sql queries. 
 * 
 * It includes error throwing, automated debug, and automated benchmarking. 
 * Also available is a compact form for processing each row of a result. 
 * Maintains a list of all queries and results. 
 * 
 * @author kenstclair
 *
 */
class SQL{
	static $queries = array();
	static $results = array();
	
	/**
	 * Run a sql query. 
	 * 
	 * Automatically creates debug output with the query and result (if DEBUG is active). 
	 * Automatically creates benchmark points before and after the query (if BENCHMARK is active). 
	 * Throws an error if the query is malformed (and ERROR is active). 
	 * Stores the query and result in lockstep arrays. 
	 * 
	 * Also takes an optional function to run on each row. 
	 * 
	 * Usage:
	 * $result = SQL::query($query);
	 * 
	 * or with a function applied to each row:
	 * $processed_results = SQL::query($query, function($row){...});
	 * 
	 * or using a function in a classes:
	 * $processed_results = SQL::query($query, array($class_name, $class_function_name));
	 * 
	 * 
	 * @param string $query
	 * @param function $func a function to run on each row
	 */
	public static function query($query, $func=null){
		DEBUG::writeln("QUERY: $query");
		BENCHMARK::createPoint("before query: $query");
		$result = mysql_query($query);
		BENCHMARK::createPoint("after query: $query");
		DEBUG::lvar_dump("RESULT: ", $result);
		if($result === false){
			ERROR::writeln("query failed: $query");
		}
		static::$queries[] = $query;
		static::$results[] = $result;
		if($func == null)
			return $result;
		else{
			return static::foreachrow($func);
		}
	}
	
	/**
	 * A compact form for processing rows.
	 * 
	 * It will automatically use the last query run as its
	 * result set. To use a different result set, pass in
	 * the result as the second parameter. 
	 * 
	 * Invoked like this:
	 * SQL::query($query);
	 * SQL::foreachrow(function($row){
	 * 		...
	 * });
	 * 
	 * or more simply:
	 * SQL::query($query, function($row){...});
	 * or using function in classes:
	 * SQL::query($query, array($class_name, $class_function_name));
	 * 
	 * @param $func the function that processes each row
	 * @param $result optional
	 * @return an array of the return values from $func on each row
	 */
	public static function foreachrow($func, $result=null){
		if(count(static::$results) == 0){
			ERROR::writeln("No result sets found");
			return false;			
		}
		
		if($result == null) 
			$result = static::$results[ count(static::$results) -1 ];
			
		$frets = array();
		while($row = mysql_fetch_assoc($result)){
			if(is_array($func)){
				$frets[] = call_user_func($func, $row); //covers static functions in classes. 
			}else{
				$frets[] = $func($row);
			}
//			DEBUG::lvar_dump("foreachrow row: ", $row);
		}
		
		return $frets;
	}

}