<?php
require_once realpath(__DIR__ . "/../../../config/config.inc.php");


class ERRORTest extends PHPUnit_Framework_TestCase{
	public function setup(){
		ERROR::activate(true);
		ERROR::setStyle("console");
	}
	public static function tearDownAfterClass()
    {
		ERROR::activate();	
    }
    
	public function testBufferNotEmptyAfterWrite(){
		ERROR::clearBuffer();
		ERROR::writeln("fuck off");
		$this->assertEquals("fuck off", substr(ERROR::$thebuffer[0], 0, 8)); 
	}
	public function testBufferNotEmptyAfterLvar_dump(){
		ERROR::clearBuffer();
		ERROR::lvar_dump("yup", array("yo" => "work"));
		
		$this->assertEquals("yup", substr(ERROR::$thebuffer[0], 0, 3)); 
		$this->assertNotEmpty(ERROR::$thebuffer);
	}
}



