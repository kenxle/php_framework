<?php
/**
 * 
 * Enter description here ...
 * @author ken.stclair
 *
 */
class TEST extends DEBUG {
	
	static $debug = false;
	static $buffer = false;
	static $thebuffer = array();
	static $newline = "<br />\n";
	static $outputStream = "php://output";
	static $outputHandle = null;
	static $writeWrapper = null;
	static $label_wrapper_open = "<b>";
	static $label_wrapper_close = "</b>";
	static $body_wrapper_open = "<pre>";
	static $body_wrapper_close = "</pre>";
	static $assertions = 0;
	static $tests = 0;
	static $passed_assertions = 0;
	static $failed_assertions = 0;
	static $passed_tests = 0;
	static $failed_tests = 0;
	static $non_bool_test_results = 0;
	static $tab_set = 7;
	static $console_tab_equivalent = 8;
	
	/**
	 * Assert that an expression or function is true. 
	 * 
	 * Accepts a bool or a function that returns a bool.
	 * Assertions that return true will pass, those that return
	 * false will fail. 
	 * @param $phrase
	 */
	public static function assert($phrase){
		static::$assertions++;

		if(is_bool($phrase)){
			$result = $phrase;
		}
		if(is_callable($phrase)){
			$result = $phrase();
		}
		if($result) static::$passed_assertions++;
		else {
			static::$failed_assertions++;
//			static::writeln("FAILED ASSERTION");
			static::writeln(static::getBacktrace());	
		}
		return $result;
	}
	
	/**
	 * Assert that two clauses are equal to each other
	 * 
	 * Option to pass in a function for equality
	 * @param mixed $clause1
	 * @param mixed $clause2
	 * @param function $eq
	 */
	public function assert_equal($clause1, $clause2, $eq=null){
		static::$assertions++;
//		static::fvar_dump($clause1);
//		static::fvar_dump($clause2);
//		if($eq)
//			static::lvar_dump("equality function: ", $eq);
		if($eq !== null && !is_callable($eq)){
			static::writeln("Equality function is set but not callable");
		}
		if(is_callable($eq)){
			$result = $eq($clause1, $clause2);
		}else{
			$result = $clause1 == $clause2;
		}
		
		if($result) static::$passed_assertions++;
		else {
			static::$failed_assertions++;
			static::writeln("FAILED ASSERTION");
			static::lvar_dump("clause1", $clause1);
			static::lvar_dump("clause2", $clause2);
			static::lvar_dump("equality function: ", $eq);
			
			static::writeln(static::getBacktrace());	
		}
		
//		static::lvar_dump("RESULT: ", $result);
		return $result;
	}
	
	/**
	 * Assert that two clauses are not equal to each other
	 * 
	 * Option to pass in a function for equality
	 * @param mixed $clause1
	 * @param mixed $clause2
	 * @param function $eq
	 */
	public function assert_not_equal($clause1, $clause2, $eq=null){
		static::$assertions++;
		if($eq !== null && !is_callable($eq)){
			static::writeln("Equality function is set but not callable");
		}
		if(is_callable($eq)){
			$result = $eq($clause1, $clause2);
		}else{
			$result = $clause1 == $clause2;
		}
		
		if(!$result) static::$passed_assertions++;
		else {
			static::$failed_assertions++;
			static::writeln("FAILED ASSERTION");
			static::lvar_dump("clause1", $clause1);
			static::lvar_dump("clause2", $clause2);
			static::lvar_dump("equality function: ", $eq);
			static::lvar_dump("RESULT: ", $result);
			
			static::writeln(static::getBacktrace());	
		}
		
		return !$result;
	}
	
	/**
	 * Assert that a clause is not empty
	 * 
	 * Option to pass in a function for emptiness
	 * @param mixed $clause1
	 * @param function $eq
	 */
	public function assert_not_empty($clause1, $eq=null){
		static::$assertions++;
		if($eq !== null && !is_callable($eq)){
			static::writeln("Equality function is set but not callable");
		}
		if(is_callable($eq)){
			$result = $eq($clause1);
		}else{
			$result = empty($clause1);
		}
		
		if(!$result) static::$passed_assertions++;
		else {
			static::$failed_assertions++;
			static::writeln("FAILED ASSERTION");
			static::lvar_dump("clause1", $clause1);
			static::lvar_dump("empty function: ", $eq);
			static::lvar_dump("RESULT: ", $result);
			
			static::writeln(static::getBacktrace());	
		}
		
		return !$result;
	}
	
	/**
	 * Assert that a clause is empty
	 * 
	 * Option to pass in a function for emptiness
	 * @param mixed $clause1
	 * @param function $eq
	 */
	public function assert_empty($clause1, $eq=null){
		static::$assertions++;
		if($eq !== null && !is_callable($eq)){
			static::writeln("Equality function is set but not callable");
		}
		if(is_callable($eq)){
			$result = $eq($clause1);
		}else{
			$result = empty($clause1);
		}
		
		if($result) static::$passed_assertions++;
		else {
			static::$failed_assertions++;
			static::writeln("FAILED ASSERTION");
			static::lvar_dump("clause1", $clause1);
			static::lvar_dump("empty function: ", $eq);
			static::lvar_dump("RESULT: ", $result);
			
			static::writeln(static::getBacktrace());	
		}
		
		return $result;
	}
	
	/**
	 * Run a test
	 * 
	 * Provides a structure for running a series of tests inside
	 * of closures, so that they don't have the chance to 
	 * interfere with each other. 
	 * 
	 * Will keep a running tally of all tests that have passed
	 * and failed. 
	 * 
	 * @example TEST::runTest("Test assert expression", function(){
			return TEST::assert(false === false);
		});
	 * @example TEST::runTest("Test assert true closure", function(){
			return TEST::assert(function(){
				return true;
			});
		});
	 * @example TEST::runTest("Test single switch", function(){
			$output = array();
			exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v test", $output);
			$should_be = array(
				"",
				"var: v",
				"command: test",
				"run: I'm running test"
			);
			$dif = array_diff($output, $should_be);
			return TEST::assert(empty($dif));
		});
	 * @param string $name
	 * @param function $closure
	 */
	public static function runTest($name, $closure){
		static::$tests++;
		// some extra stuff for nicer output. 
		TEST::write($name); //print the test name. the overall result will go on the same line
		TEST::bufferOutput(true); // so we'll buffer any output from the test itself and print later
		TEST::clearBuffer();
		$result = $closure(); // run the test
		TEST::bufferOutput(false);

		if(is_bool($result)){
			if($result){
				static::$passed_tests++;
				TEST::writeln(static::getTabs($name)."[PASSED]"); 
			} else {
				static::$failed_tests++;
				TEST::writeln(static::getTabs($name)."[FAILED]");	
			}
		}else{
			static::$non_bool_test_results++;
			TEST::writeln(" [NON-BOOL RETURN]");
		} 
		
		TEST::printBuffer(); // print any output from the test
	}
	
	/**
	 * Try and line up the passed/failed responses
	 * @param unknown_type $name
	 */
	public static function getTabs($name){
		$num = static::$tab_set - floor(strlen($name)/static::$console_tab_equivalent);
		$str = "";
		while($num > 0){
			$str .="\t";
			$num--;
		}
		return $str;
		
	}
	
	
	/**
	 * Run all test files in a specified directory
	 * 
	 * If directory contains a .testignore file, then 
	 * each line will be used as a regex pattern, and 
	 * any matching files will be ignored, case-insensitive. 
	 * 
	 * 
	 * @param unknown_type $directory
	 */
	public static function runTestsIn($directory){
		
		$files = static::recursiveDirToArray($directory);
		
		if($fh = fopen($directory."/.testignore", "r")){ // check for .testignore
			while (($buffer = fgets($fh, 4096)) !== false) {
				$buffer = str_replace(array("\n","\r"), "", $buffer);//strip the newline cause we don't need it in the regex
				
				//TODO be a little more sophisticated here with array ops
				foreach($files as $key=>$file){
					$pattern = "/$buffer/i";
					if(preg_match($pattern, $file)){
						unset($files[$key]);
						DEBUG::writeln("file ignored: $file");
					}
				}
		    }
		}else{DEBUG::writeln("no usable .testignore file found");}
		
		DEBUG::lvar_dump("eligible files: ", $files);
		foreach($files as $file){
			static::writeln("\nFile: " . $file);;
			$callable = function($string){
				return "\t" . $string;
			};
			static::setWriteWrapper($callable);
			include ($file); //TODO think about making this less naive
			static::setWriteWrapper(null);
		}
		
		static::writeSummary();
	}
	
	public static function writeSummary(){
		static::write("\nTests: ". static::$tests);
		static::write(" \tPassed: ". static::$passed_tests);
		if(static::$non_bool_test_results > 0){
			static::write("\tNon-bool return vals: ". static::$non_bool_test_results);
		}
		static::writeln(" \tFAILED: ". static::$failed_tests);
		static::write("Assertions: ". static::$assertions);
		static::write(" \tPassed: ". static::$passed_assertions);
		static::writeln(" \tFAILED: ". static::$failed_assertions);
	}
	
//	public static function recursiveDirToArray($directory){
//		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
//		foreach($scanned_directory as $potential_file){
//			
//		}
//	}
	
	public static function recursiveDirToArray($dir) { 
	   $result = array(); 
	
	   $cdir = scandir($dir); 
	   foreach ($cdir as $key => $value) 
	   { 
	      if (!in_array($value,array(".",".."))) 
	      { 
	         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
	         { 
	            $result = array_merge($result, static::recursiveDirToArray($dir . DIRECTORY_SEPARATOR . $value)); 
	         } 
	         else 
	         { 
	            $result[] = $dir . DIRECTORY_SEPARATOR . $value; 
	         } 
	      } 
	   } 
	   
	   return $result; 
	} 
}