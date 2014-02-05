<?php
/**
 * These tests are a little tricky because DEBUG is never
 * instantiated and only used as static. Because of that, 
 * each time we make a test, we have to pull the current values
 * of things that we are going to change, and make sure to set
 * them back to the values that existed before the test once
 * we've run our test. 
 */
$current_debug = DEBUG::$debug;
$current_buffer = DEBUG::$buffer;
$current_the_buffer = DEBUG::$thebuffer;
$current_write_wrapper = DEBUG::$writeWrapper;
$current_body_wrapper_open = DEBUG::$body_wrapper_open;
$current_body_wrapper_close = DEBUG::$body_wrapper_close;
$current_label_wrapper_open = DEBUG::$label_wrapper_open;
$current_label_wrapper_close = DEBUG::$label_wrapper_close;

static::setNewLine("\n");
static::setLabelWrapperOpen("");
static::setLabelWrapperClose("");
static::setBodyWrapperOpen("");
static::setBodyWrapperClose("");

TEST::runTest("Debug into buffer", function(){
	DEBUG::activate(true);
	DEBUG::setStyle("console");
	DEBUG::clearBuffer();
	DEBUG::write("this is a test");
	$should_be = array("this is a test");
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});

TEST::runTest("lvar_dump with function", function(){
	DEBUG::activate(true);
	DEBUG::setStyle("console");
	DEBUG::clearBuffer();
	DEBUG::lvar_dump("this is a test", "test", function($string){
		return "\n$string";
	});
//	DEBUG::activate();
//	DEBUG::lvar_dump("thebuffer: ", DEBUG::$thebuffer);
	$should_be = array("this is a test", "\nstring(4) \"test\"\n");
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});

TEST::runTest("write wrapper", function(){
	DEBUG::activate(true);
	DEBUG::setStyle("console");
	DEBUG::clearBuffer();
	DEBUG::setWriteWrapper(function($string){
		return "<pre>" . $string . "</pre>";
	});
	DEBUG::write("this is a test");

	DEBUG::setWriteWrapper($current_write_wrapper);
	$should_be = array("<pre>this is a test</pre>");
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});

TEST::runTest("writeln", function(){
	DEBUG::activate(true);
	DEBUG::setStyle("console");
	DEBUG::clearBuffer();
	DEBUG::writeln("this is a test");

	DEBUG::setWriteWrapper($current_write_wrapper);
	$should_be = array("this is a test\n");
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});


TEST::runTest("no output when off", function(){
	DEBUG::deactivate();
	DEBUG::clearBuffer();
	DEBUG::writeln("this is a test");

	DEBUG::setWriteWrapper($current_write_wrapper);
	$should_be = array();
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});

TEST::runTest("clear buffer empties buffer", function(){
	DEBUG::activate(true);
	DEBUG::setStyle("console");
	DEBUG::clearBuffer();
	DEBUG::writeln("this is a test");
	DEBUG::clearBuffer();

	DEBUG::setWriteWrapper($current_write_wrapper);
	$should_be = array();
	return TEST::assert_equal($should_be, DEBUG::$thebuffer);
});

DEBUG::$debug = $current_debug;
DEBUG::$buffer = $current_buffer;
DEBUG::$thebuffer = $current_the_buffer;
DEBUG::$writeWrapper = $current_write_wrapper;
DEBUG::$body_wrapper_open = $current_body_wrapper_open;
DEBUG::$body_wrapper_close = $current_body_wrapper_close;
DEBUG::$label_wrapper_open = $current_label_wrapper_open;
DEBUG::$label_wrapper_close = $current_label_wrapper_close;
