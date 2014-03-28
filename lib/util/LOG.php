<?php

/**
 * A separate util for logging
 * 
 * All of the functions in LOG work only if the class has been activated
 * by a call to 
 * LOG::activate();
 * 
 * @author kenstclair
 *
 */

class LOG extends DEBUG{
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
	
}

?>
