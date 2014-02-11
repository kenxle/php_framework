<?php
require_once realpath(__DIR__ . "/../../../config/config.inc.php");


class FPXTest extends PHPUnit_Framework_TestCase{

	public function setUp(){
			
		ERROR::activate(true); //buffer errors so we can check for them
		ERROR::setStyle("console");
	}

	public function tearDown(){	
		ERROR::activate();
	}

	public function testExtractedVarAvailable(){
		FPX::activate();
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("varname"),
				"optional" => array(
					"this",
					"is",
					"an",
					"optional",
					"list",
					"of",
					"variables"
				)
			)));
			
			$t->assertNotEmpty($varname);
		};
		
		$func(array(
			"varname" => "hello"
		));
	}

	public function testFPXIsConformingPassthroughWhenInactive(){
		FPX::deactivate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("varname"),
				"optional" => array()
			)));
			$t->assertNotEmpty($varname);
			$t->assertEmpty($xxx);
			$t->assertEmpty(ERROR::$thebuffer);
		};
		
		$func(array(
			"varname" => "hello",
			"xxx" => "hello"
		));
	}

	public function testForErrorOnBadVar(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("varname"),
				"optional" => array("aname")
			)));
			$t->assertNotEmpty(ERROR::$thebuffer);
			$t->assertEmpty($other); // should have thrown an error and thrown out the offending param
		};
		
		$func(array(
			"varname" => "hello",
			"other" => "ho"
		));
	}

	public function testErrorForUnwantedVarWithTwoReqGroups(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array(array("varname"), array("other_required")),
				"optional" => array("dname")
			)));
			$t->assertNotEmpty(ERROR::$thebuffer); 
			$t->assertEmpty($other);
			$t->assertNotEmpty($varname);
		};
		
		$func(array(
			"varname" => "hello",
			"other" => "ho"
		));
	}
	
	public function testErrorWhenMissingFullRequiredGroupOfTwo(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array(array("varname", "other"), array("other_required")),
				"optional" => array("dname")
			)));
			$t->assertEquals("Missing required parameter", substr(ERROR::$thebuffer[0], 0, 26)); 
			$t->assertEmpty($varname); //if required params are gone, nothing is returned
		};
		
		ERROR::activate(true);
		$func(array(
			"varname" => "hello",
		));
	}

	public function testErrorWhenMissingFullRequiredGroupOfOne(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("varname", "other"),
				"optional" => array("dname")
			)));
			$t->assertEquals("Missing required parameter", substr(ERROR::$thebuffer[0], 0, 26)); 
			$t->assertEmpty($varname);
		};
		
		$func(array(
			"varname" => "hello",
		));
	}
	
	public function testOnlyOneRequiredGroupNeeded(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array(array("varname", "other"), array("other_required")),
				"optional" => array("dname")
			)));
			$t->assertEmpty(ERROR::$thebuffer);
			$t->assertNotEmpty($other);
			$t->assertNotEmpty($varname);
		};
		
		$func(array(
			"varname" => "hello",
			"other" => "ho"
		));
	}

	public function testPartialOnOneRequiredFullOnOther(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array(array("varname", "other"), array("other_required")),
				"optional" => array("dname")
			)));
			$t->assertEmpty(ERROR::$thebuffer); 
			$t->assertNotEmpty($other);
			$t->assertNotEmpty($other_required);
			$t->assertNotEmpty($dname);
		};
		
		$func(array(
			"other_required" => "hello",
			"other" => "ho",
			"dname" => "yo"
		));
	}

	public function testOptionalOnly(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"optional" => array("dname", "ename")
			)));
			$t->assertEmpty(ERROR::$thebuffer); 
		};
		
		$func(array( //optional included
			"dname" => "yo"
		));
		$func(array()); //optional excluded
	}

	public function testRequiredOnly(){
		FPX::activate();
		ERROR::clearBuffer();
		
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("dname", "ename")
			)));
			$t->assertEmpty(ERROR::$thebuffer) ;
			$t->assertEquals($dname, "yo");
		};
		//required success
		$func(array(
			"dname" => "yo",
			"ename" => "erin"
		));
		
		// required failure
		$t = $this;
		$func = function($pArr) use (&$t){
			extract(FPX::contract(array(
				"required" => array("dname", "ename")
			)));
			$t->assertNotEmpty(ERROR::$thebuffer) ;
		};
		
		$func(array());
	}
}
