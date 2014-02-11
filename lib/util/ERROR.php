<?php

/**
 * Error function like debug, but includes additional output
 * and can throw errors to logs, stop execution, etc.
 * 
 * @author kenstclair
 *
 */
class ERROR extends DEBUG{
	
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
	
	public static function writeln($string, $e=null){
		static::write($string);
		parent::write(static::$newline);
	}
	public static function write($string){
		parent::write($string . 
				static::$newline . 
				static::getBacktrace());
	}
	
	/**
	 * will only print output if the class has been activated, but will
	 * always send the trigger_error 
	 * @param unknown_type $string
	 * @param unknown_type $e
	 */
	public static function throwError($string, $e=null){
		$string = static::$label_wrapper_open."ERROR:".static::$label_wrapper_close. 
				($e ? $e . ": " : "") . 
				$string . 
				static::$newline ;
				
		static::write($string);
		trigger_error($string, E_USER_ERROR);
	}
	
	public static function getBacktrace(){
		ob_start();
			debug_print_backtrace();
		$backtraceStr = ob_get_clean();
		return static::$body_wrapper_open.$backtraceStr.static::$body_wrapper_close;
	}
	
}

?>
