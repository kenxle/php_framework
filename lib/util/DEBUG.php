<?php

/**
 * A class for conditionally printing output to the screen. The debug class
 * allows you to leave important debug statements in the code while 
 * centrally deciding whether they will print their output or not. 
 * 
 * Output can also be buffered and then printed when desired.
 * 
 * All of the functions in Debug work only if the class has been activated
 * by a call to 
 * DEBUG::activate();
 * 
 * @author kenstclair
 *
 */

class DEBUG{
	static $debug = false;
	static $buffer = false;
	static $thebuffer = array();
	static $newline = "<br />\n";
	
	/**
	 * print the input and a newline
	 * @param unknown_type $string
	 */
	public static function writeln($string){
		static::write($string . static::$newline);
	}
	
	/**
	 * print the input
	 * @param unknown_type $string
	 */
	public static function write($string){
		if(static::$debug){
			if(static::$buffer){
				static::$thebuffer[] = $string; 
			}else{
				echo $string;
			}
		}
	}
	
	/**
	 * Activate debugging output by printing all debug statements
	 * @param unknown_type $buffer - [true|false] to buffer the output instead of printing inline.
	 */
	public static function activate($buffer=false){
		static::$debug = true;
		static::$buffer = $buffer;
		
		if(static::$buffer){
			ini_set("memory_limit","2048M");
		}
	}
	
	/**
	 * Deactivate debugging output by supressing all debug statements
	 */
	public static function deactivate(){
		static::$debug = false;
	}
	
	/**
	 * Buffer the output of all debug statements. To view the buffer, use printBuffer.
	 * @param unknown_type $buffer
	 */
	public static function bufferOutput($buffer=true){
		static::$buffer = $buffer;
		
		if(static::$buffer){
			ini_set("memory_limit","2048M");
		}
	}
	/**
	 * Empties the buffer 
	 */
	public static function clearBuffer(){
		static::$thebuffer = array();
	}
	
	/**
	 * Dumps the buffer to the output
	 */
	public static function printBuffer(){
		foreach(static::$thebuffer as $string){
			echo $string;
		}
	}
	
	/**
	 * same as formatted_var_dump, only it puts a bold label in front
	 */
	public static function labelled_var_dump($label, $var){
		if(static::$debug){
			$label = "<b>".$label."</b>";
			if(static::$buffer){
				static::$thebuffer[] = $label;
			}else{
				echo $label;
			}
			static::formatted_var_dump($var);
		}
	}
	
	/**
	 * same as var_dump, only it indents the children of objects and arrays 
	 */
	public static function formatted_var_dump($var){
		if(static::$debug){
			ob_start();
				echo "<pre>";
				var_dump($var);
				echo "</pre>";
			$val = ob_get_clean();
			if(static::$buffer){
				static::$thebuffer[] = $val;
			}else{
				echo $val;
			}
		}
	}
	
	/**
	 * same as formatted_var_dump, only it puts a bold label in front
	 */
	public static function labelled_print_r($label, $var){
		if(static::$debug){
			$label = "<b>".$label."</b>";
			if(static::$buffer){
				static::$thebuffer[] = $label;
			}else{
				echo $label;
			}
			static::formatted_print_r($var);
		}
	}
	
	/**
	 * same as var_dump, only it indents the children of objects and arrays 
	 */
	public static function formatted_print_r($var){
		if(static::$debug){
			$val = "<pre>";
			$val .= print_r($var, true);
			$val .= "</pre>";
			if(static::$buffer){
				static::$thebuffer[] = $val;
			}else{
				echo $val;
			}
		}
	}

	/* aliases for labelled_var_dump and formatted_var_dump and the print_r's*/
	public static function lvar_dump($label, $var){static::labelled_var_dump($label, $var);}
	public static function fvar_dump($var){static::formatted_var_dump($var);}
	public static function lprint_r($label, $var){static::labelled_print_r($label, $var);}
	public static function fprint_r($var){static::formatted_print_r($var);}
	
	/**
	 * Gets the stack data about the function
	 * you call from. This will print out the arguments,
	 * function, class, file and line number. 
	 * 
	 * Good for making sure all your arguments are present. 
	 * 
	 */
	public static function rollcall(){
		if(static::$debug){
			$trace=debug_backtrace(false);
			$caller=$trace[1];
//			$args = $caller['args'];
			$func_name = $caller['function'];
			$val = $caller;
			
			static::lvar_dump("$func_name rollcall: ", $val);
		}
	}
}

?>
