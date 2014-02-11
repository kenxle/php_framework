<?php

require_once realpath(__DIR__ . "/../../../../config/config.inc.php");


class CommandParserTest extends PHPUnit_Framework_TestCase{
	public function testSingleSwitch(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v test", $output);
		$should_be = array(
			"",
			"var: v",
			"command: test",
			"run: I'm running test"
		);
		$this->assertEquals($should_be, $output);
	}

	public function testLongSwitch(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php --verbose test", $output);
		$should_be = array(
			"",
			"var: verbose",
			"command: test",
			"run: I'm running test"
		);
		$this->assertEquals($should_be, $output);
	}

	public function testMultiSwitchTogether(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -vq test", $output);
		$should_be = array(
			"",
			"var: v",
			"var: q",
			"command: test",
			"run: I'm running test"
		);
		$this->assertEquals($should_be, $output);
	}

	public function testMultiSwitchSeparate(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v -q test", $output);
		$should_be = array(
			"",
			"var: v",
			"var: q",
			"command: test",
			"run: I'm running test"
		);
		$this->assertEquals($should_be, $output);
	}


	public function testLongAndShortSwitch(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php -v --quiet test", $output);
		$should_be = array(
			"",
			"var: v",
			"var: quiet",
			"command: test",
			"run: I'm running test"
		);
		$this->assertEquals($should_be, $output);
	}

	public function testGlobalAndCommandSwitch(){
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
		$this->assertEquals($should_be, $output);
	}


	public function testMultipleGlobalAndCommandSwitchesAndFlags(){
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
		$this->assertEquals($should_be, $output);
	}

	
	public function testNoGlobalFlagsYesCommandFlags(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php package -b -a=foo", $output);
		$should_be = array(
			"",
			"command: package",
			"command_var: a=foo",
			"command_var: b",
			"run: I'm running package"
		);
		$this->assertEquals($should_be, $output);
	}

	public function testCommandFlagLongs(){
		$output = array();
		exec(BASE_PATH."test/lib/util/k3/CommandParserTestHelper.php package --bootstrap", $output);
		$should_be = array(
			"",
			"command: package",
			"command_var: bootstrap",
			"run: I'm running package"
		);
		$this->assertEquals($should_be, $output);
	}
}

