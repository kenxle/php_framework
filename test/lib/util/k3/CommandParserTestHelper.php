#!/usr/bin/php
<?php
require_once "lib/util/k3/CommandParser.php";
require_once "lib/util/DEBUG.php";
require_once "lib/util/TEST.php";
require_once "lib/util/ERROR.php";

$opts = new CommandParser($argv, array(
	"desc" => "CommandParserTestHelper is a mock command line program for testing",

	"version" => "0.0.1",
		
	"commands" => array(

		"test" => array(
			"help" => "Help text for test command",
			"desc" => "Run the test suite for this app",
			"options" => array(),
			"run" => function($args){
				echo "\nrun: I'm running test";
			}
		),
		
		"package" => array(
			"help" => "Help text for test command",
			"desc" => "Package the deploy for this app",
			"options" => array(
				"a" => array(
					"also" => "assemble",
					"type" => "required_param",
					"desc" => "Assemble the listed package"
				),
				"b" => array(
					"also" => "bootstrap",
					"type" => "optional_param",
					"desc" => "Bootstrap the package with standard, or file listed"
				)
			),
			"run" => function($args){
				echo "\nrun: I'm running package";
			}
		)

	),
	
	"options" => array(
		"v" => array(
			"also" => "verbose",
			"type" => "switch",
			"desc" => "Run in verbose mode"
		),
		"q" => array(
			"also" => "quiet",
			"type" => "switch",
			"desc" => "Run in quiet mode"
		)
	)
));

if($opts->hasGlobalArg("v")){
	echo "\nvar: v";
}
if($opts->hasGlobalArg("verbose")){
	echo "\nvar: verbose";
}
if($opts->hasGlobalArg("q")){
	echo "\nvar: q";
}
if($opts->hasGlobalArg("quiet")){
	echo "\nvar: quiet";
}
if($opts->command){
	echo "\ncommand: ".$opts->command;
}
if($opts->hasCommandArg("a")){
	echo "\ncommand_var: a=". $opts->getCommandArg("a");
}
if($opts->hasCommandArg("b")){
	echo "\ncommand_var: b";
}
if($opts->hasCommandArg("assemble")){
	echo "\ncommand_var: assemble";
}
if($opts->hasCommandArg("bootstrap")){
	echo "\ncommand_var: bootstrap";
}

$opts->run();