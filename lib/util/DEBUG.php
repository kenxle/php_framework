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
	static $outputStream = "php://output";
	static $outputHandle = null;
	static $writeWrapper = null;
	static $label_wrapper_open = "<b>";
	static $label_wrapper_close = "</b>";
	static $body_wrapper_open = "<pre>";
	static $body_wrapper_close = "</pre>";
	
	public static function setStyle($str){
		switch ($str){
			case "console":
				static::setNewLine("\n");
				static::setLabelWrapperOpen("");
				static::setLabelWrapperClose("");
				static::setBodyWrapperOpen("");
				static::setBodyWrapperClose("");
//				static::setWriteWrapper(function($string){
//					return "[".date('Y-m-d H:i:s')."] " . $string;
//				});
				break;
			case "html":
				static::setNewLine("<br />\n");
				static::setLabelWrapperOpen("<b>");
				static::setLabelWrapperClose("</b>");
				static::setBodyWrapperOpen("<pre>");
				static::setBodyWrapperClose("</pre>");
				break;
		}
	}
	
	public static function setNewLine($str){
		static::$newline = $str;
	}
	
	public static function setLabelWrapperOpen($str){
		static::$label_wrapper_open = $str;
	}
	public static function setLabelWrapperClose($str){
		static::$label_wrapper_close = $str;
	}
	public static function setBodyWrapperOpen($str){
		static::$body_wrapper_open = $str;
	}
	public static function setBodyWrapperClose($str){
		static::$body_wrapper_close = $str;
	}
	/**
	 * Choose the output stream. Allows you to print
	 * to a log instead of standard output
	 */
	public static function setOutputStream($outputStream, $command="w"){
		static::$outputStream = $outputStream;
		static::$outputHandle = fopen(static::$outputStream, $command);
	}
	/**
	 * A function to wrap write output
	 */
	public static function setWriteWrapper($func){
		static::$writeWrapper = $func;
	}
	
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
			// if we've got a wrapper function, call it. 
			if(is_callable(static::$writeWrapper)){
//				$string = static::$writeWrapper($string);
				$func = static::$writeWrapper;
				$string = $func($string);
			}
			if(static::$buffer){
				static::$thebuffer[] = $string; 
			}else{
				if(!static::$outputHandle) {
					static::$outputHandle = fopen(static::$outputStream, 'w');
				}
				fwrite(static::$outputHandle, $string);
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
			ini_set("memory_limit","2048M"); //TODO checek to see if memory limit is above this before setting
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
			ini_set("memory_limit","2048M");//TODO checek to see if memory limit is above this before setting
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
		static::$buffer = false; // turn off buffer so write will print
		foreach(static::$thebuffer as $string){
			 static::write($string);
		}
	}
	
/**
	 * same as formatted_var_dump, only it puts a bold label in front
	 */
	public static function labelled_var_dump($label, $var, $func=null){
		if(static::$debug){
			$label = static::$label_wrapper_open.$label.static::$label_wrapper_close;
			ob_start();
				echo $label;
				var_dump($var);
			$val = ob_get_clean();
			
			if($func) $val = $func($val);
			$val =  static::$body_wrapper_open.
					$val.
					static::$body_wrapper_close;
			
			static::write($val);
		}
	}
	
	/**
	 * same as var_dump, only it indents the children of objects and arrays 
	 */
	public static function formatted_var_dump($var, $func=null){
		if(static::$debug){
			ob_start();
				var_dump($var);
			$val = ob_get_clean();
			
			if($func) $val = $func($val);
			$val =  static::$body_wrapper_open.
					$val.
					static::$body_wrapper_close;
			
			static::write($val);
		}
	}
	
	/**
	 * same as formatted_var_dump, only it puts a bold label in front
	 */
	public static function labelled_print_r($label, $var){
		if(static::$debug){
			$label = static::$label_wrapper_open.$label.static::$label_wrapper_close;
			static::write($label);
			static::formatted_print_r($var);
		}
	}
	
	/**
	 * same as var_dump, only it indents the children of objects and arrays 
	 */
	public static function formatted_print_r($var){
		if(static::$debug){
			$val =  static::$body_wrapper_open;
			$val .= print_r($var, true);
			$val .=  static::$body_wrapper_close;
			static::write($val);
		}
	}

	/* aliases for labelled_var_dump and formatted_var_dump and the print_r's*/
	public static function lvar_dump($label, $var, $func=null){static::labelled_var_dump($label, $var, $func);}
	public static function fvar_dump($var, $func=null){static::formatted_var_dump($var, $func);}
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
	
	public static function getBacktrace(){
		ob_start();
			debug_print_backtrace();
		$backtraceStr = ob_get_clean();
		return static::$body_wrapper_open.$backtraceStr.static::$body_wrapper_close;
	}
}

?>
