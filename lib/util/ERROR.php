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
	
	public static function writeln($string, $e=null){
		parent::write($string . 
				static::$newline . 
				static::getBacktrace() . 
				static::$newline);
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
		$string = "<b>ERROR:</b> " . 
				($e ? $e . ": " : "") . 
				$string . 
				static::$newline . 
				static::getBacktrace();
				
		if(static::$debug){
			if(static::$buffer){
				static::$thebuffer[] = $string; 
			}else{
				echo $string;
			}
		}
		trigger_error($string, E_USER_ERROR);
	}
	
	public static function getBacktrace(){
		ob_start();
			debug_print_backtrace();
		$backtraceStr = ob_get_clean();
		return "<pre>".$backtraceStr."</pre>";
	}
	
}

?>
