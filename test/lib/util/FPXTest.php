<?php
ERROR::activate(true); //buffer errors so we can check for them

TEST::runTest("Test extracted var available", function(){
	FPX::activate();
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("varname"),
			"optional" => array()
		)));
		return TEST::assert_not_empty($varname);
	};
	
	return $func(array(
		"varname" => "hello"
	));
});

TEST::runTest("Test FPX is conforming passthrough when inactive", function(){
	FPX::deactivate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("varname"),
			"optional" => array()
		)));
		return TEST::assert_not_empty($varname)
				&& TEST::assert_empty($xxx)
				&& TEST::assert_empty(ERROR::$thebuffer);
	};
	
	return $func(array(
		"varname" => "hello",
		"xxx" => "hello"
	));
});

TEST::runTest("Test for error on bad var", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("varname"),
			"optional" => array("aname")
		)));
		return TEST::assert_not_empty(ERROR::$thebuffer) 
				&& TEST::assert_empty($other); // should have thrown an error and thrown out the offending param
	};
	
	return $func(array(
		"varname" => "hello",
		"other" => "ho"
	));
});

TEST::runTest("Test error for unwanted var with two req groups", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array(array("varname"), array("other_required")),
			"optional" => array("dname")
		)));
		return TEST::assert_not_empty(ERROR::$thebuffer) 
				&& TEST::assert_empty($other)
				&& TEST::assert_not_empty($varname);
	};
	
	return $func(array(
		"varname" => "hello",
		"other" => "ho"
	));
});
TEST::runTest("Test error when missing full required group of two", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array(array("varname", "other"), array("other_required")),
			"optional" => array("dname")
		)));
		return TEST::assert_equal("Missing required parameter", substr(ERROR::$thebuffer[0], 0, 26)) 
				&& TEST::assert_empty($varname); //if required params are gone, nothing is returned
	};
	
	ERROR::activate(true);
	return $func(array(
		"varname" => "hello",
	));
});

TEST::runTest("Test error when missing full required group of one", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("varname", "other"),
			"optional" => array("dname")
		)));
		return TEST::assert_equal("Missing required parameter", substr(ERROR::$thebuffer[0], 0, 26)) 
				&& TEST::assert_empty($varname);
	};
	
	return $func(array(
		"varname" => "hello",
	));
});
TEST::runTest("Test only one required group needed", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array(array("varname", "other"), array("other_required")),
			"optional" => array("dname")
		)));
		return TEST::assert_empty(ERROR::$thebuffer) 
				&& TEST::assert_not_empty($other)
				&& TEST::assert_not_empty($varname);
	};
	
	return $func(array(
		"varname" => "hello",
		"other" => "ho"
	));
});

TEST::runTest("Test partial on one required, full on other", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array(array("varname", "other"), array("other_required")),
			"optional" => array("dname")
		)));
		return TEST::assert_empty(ERROR::$thebuffer) 
				&& TEST::assert_not_empty($other)
				&& TEST::assert_not_empty($other_required)
				&& TEST::assert_not_empty($dname);
	};
	
	return $func(array(
		"other_required" => "hello",
		"other" => "ho",
		"dname" => "yo"
	));
});

TEST::runTest("Test optional only", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"optional" => array("dname", "ename")
		)));
		return TEST::assert_empty(ERROR::$thebuffer); 
	};
	
	return $func(array( //optional included
		"dname" => "yo"
	))
	&& $func(array()); //optional excluded
});

TEST::runTest("Test required only", function(){
	FPX::activate();
	ERROR::clearBuffer();
	
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("dname", "ename")
		)));
		return TEST::assert_empty(ERROR::$thebuffer) 
				&& TEST::assert_not_empty($dname);
	};
	//required success
	$res =  $func(array(
		"dname" => "yo",
		"ename" => "erin"
	));
	
	// required failure
	$func = function($pArr){
		extract(FPX::contract(array(
			"required" => array("dname", "ename")
		)));
		return TEST::assert_not_empty(ERROR::$thebuffer) ;
	};
	
	return $res && $func(array());
});

ERROR::activate();