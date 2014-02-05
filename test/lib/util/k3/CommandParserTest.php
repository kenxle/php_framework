<?php

TEST::runTest("Test single switch", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v test", $output);
	$should_be = array(
		"",
		"var: v",
		"command: test",
		"run: I'm running test"
	);
	return TEST::assert_equal($should_be, $output);
});

TEST::runTest("Test long switch", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php --verbose test", $output);
	$should_be = array(
		"",
		"var: verbose",
		"command: test",
		"run: I'm running test"
	);
	return TEST::assert_equal($should_be, $output);
});

TEST::runTest("Test multi switch together", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -vq test", $output);
	$should_be = array(
		"",
		"var: v",
		"var: q",
		"command: test",
		"run: I'm running test"
	);
	return TEST::assert_equal($should_be, $output);
});

TEST::runTest("Test multi switch separate", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v -q test", $output);
	$should_be = array(
		"",
		"var: v",
		"var: q",
		"command: test",
		"run: I'm running test"
	);
	return TEST::assert_equal($should_be, $output);
});


TEST::runTest("Test long and short switch", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v --quiet test", $output);
	$should_be = array(
		"",
		"var: v",
		"var: quiet",
		"command: test",
		"run: I'm running test"
	);
	return TEST::assert_equal($should_be, $output);
});

TEST::runTest("Test global and command switch", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v --quiet package -a=foo", $output);
	$should_be = array(
		"",
		"var: v",
		"var: quiet",
		"command: package",
		"command_var: a=foo",
		"run: I'm running package"
	);
	return TEST::assert_equal($should_be, $output);
});


TEST::runTest("Test multiple global and command switches and flags", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v --quiet package -b -a=foo", $output);
	$should_be = array(
		"",
		"var: v",
		"var: quiet",
		"command: package",
		"command_var: a=foo",
		"command_var: b",
		"run: I'm running package"
	);
	return TEST::assert_equal($should_be, $output);
});


TEST::runTest("Test no global flags, yes command flags", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php package -b -a=foo", $output);
	$should_be = array(
		"",
		"command: package",
		"command_var: a=foo",
		"command_var: b",
		"run: I'm running package"
	);
	return TEST::assert_equal($should_be, $output);
});

TEST::runTest("Test command flag longs", function(){
	$output = array();
	exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php package --bootstrap", $output);
	$should_be = array(
		"",
		"command: package",
		"command_var: bootstrap",
		"run: I'm running package"
	);
	return TEST::assert_equal($should_be, $output);
});

