<?php

TEST::runTest("Test buffer not empty after write", function(){
	ERROR::activate(true);
	ERROR::clearBuffer();
	ERROR::writeln("fuck off");
	
	return TEST::assert_not_empty(ERROR::$thebuffer);
});


TEST::runTest("Test buffer not empty after lvar_dump", function(){
	ERROR::activate(true);
	ERROR::clearBuffer();
	ERROR::lvar_dump("fuck off", array("yo" => "work"));
	
	return TEST::assert_not_empty(ERROR::$thebuffer);
});

ERROR::activate();