<?php
require_once realpath(__DIR__ . "/../../../config/config.inc.php");

class DEBUGTest extends PHPUnit_Framework_TestCase{
	
	public static function setUpBeforeClass()
    {
        $current_debug = DEBUG::$debug;
		$current_buffer = DEBUG::$buffer;
		$current_the_buffer = DEBUG::$thebuffer;
		$current_write_wrapper = DEBUG::$writeWrapper;
		$current_body_wrapper_open = DEBUG::$body_wrapper_open;
		$current_body_wrapper_close = DEBUG::$body_wrapper_close;
		$current_label_wrapper_open = DEBUG::$label_wrapper_open;
		$current_label_wrapper_close = DEBUG::$label_wrapper_close;
		
		DEBUG::setNewLine("\n");
		DEBUG::setLabelWrapperOpen("");
		DEBUG::setLabelWrapperClose("");
		DEBUG::setBodyWrapperOpen("");
		DEBUG::setBodyWrapperClose("");
    	
    }
    
 	public static function tearDownAfterClass()
    {
        
		DEBUG::$debug = $current_debug;
		DEBUG::$buffer = $current_buffer;
		DEBUG::$thebuffer = $current_the_buffer;
		DEBUG::$writeWrapper = $current_write_wrapper;
		DEBUG::$body_wrapper_open = $current_body_wrapper_open;
		DEBUG::$body_wrapper_close = $current_body_wrapper_close;
		DEBUG::$label_wrapper_open = $current_label_wrapper_open;
		DEBUG::$label_wrapper_close = $current_label_wrapper_close;
    	
    }
    
	public function testDebugIntoBuffer(){
		DEBUG::activate(true);
		DEBUG::setStyle("console");
		DEBUG::clearBuffer();
		DEBUG::write("this is a test");
		$should_be = array("this is a test");
		$this->assertEquals($should_be, DEBUG::$thebuffer);
		
	}
	
	public function testlvardumpWithFunction(){
		DEBUG::activate(true);
		DEBUG::setStyle("console");
		DEBUG::clearBuffer();
		DEBUG::lvar_dump("this is a test", "test", function($string){
			return "\n$string";
		});
	//	DEBUG::activate();
	//	DEBUG::lvar_dump("thebuffer: ", DEBUG::$thebuffer);
		$should_be = array("this is a test", "\nstring(4) \"test\"\n");
		$this->assertEquals($should_be, DEBUG::$thebuffer);
	}
	
	public function testWriteWrapper(){
		DEBUG::activate(true);
		DEBUG::setStyle("console");
		DEBUG::clearBuffer();
		DEBUG::setWriteWrapper(function($string){
			return "<pre>" . $string . "</pre>";
		});
		DEBUG::write("this is a test");
	
		DEBUG::setWriteWrapper($current_write_wrapper);
		$should_be = array("<pre>this is a test</pre>");
		$this->assertEquals($should_be, DEBUG::$thebuffer);
	}
	
	public function testWriteln(){
		DEBUG::activate(true);
		DEBUG::setStyle("console");
		DEBUG::clearBuffer();
		DEBUG::writeln("this is a test");
	
		DEBUG::setWriteWrapper($current_write_wrapper);
		$should_be = array("this is a test\n");
		$this->assertEquals($should_be, DEBUG::$thebuffer);
	}
	
	public function testNoOutputWhenOff(){
		DEBUG::deactivate();
		DEBUG::clearBuffer();
		DEBUG::writeln("this is a test");
	
		DEBUG::setWriteWrapper($current_write_wrapper);
		$should_be = array();
		$this->assertEquals($should_be, DEBUG::$thebuffer);
	}
	
	public function testClearBufferEmptiesBuffer(){
		DEBUG::activate(true);
		DEBUG::setStyle("console");
		DEBUG::clearBuffer();
		DEBUG::writeln("this is a test");
		DEBUG::clearBuffer();
	
		DEBUG::setWriteWrapper($current_write_wrapper);
		$should_be = array();
		$this->assertEquals($should_be, DEBUG::$thebuffer);
	}
	
}