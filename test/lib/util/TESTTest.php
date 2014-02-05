<?php
TEST::runTest("Test assert true bool", function(){
	return TEST::assert(true);
});
TEST::runTest("Test assert expression", function(){
	return TEST::assert(false === false);
});
TEST::runTest("Test assert true closure", function(){
	return TEST::assert(function(){
		return true;
	});
});

TEST::runTest("Test assert_equal num", function(){
	return TEST::assert_equal(2, 2);
});


TEST::runTest("Test assert_equal array", function(){
	return TEST::assert_equal(array("a", "b", "c"), array("a", "b", "c"));
});


TEST::runTest("Test assert_equal assoc_array", function(){
	return TEST::assert_equal(
		array(
		"a" => "b",
		"c" => "d"
		),
		array(
		"a" => "b",
		"c" => "d"
		)
	);
});

TEST::runTest("Test assert_equal assoc_array with func for ===", function(){
	return TEST::assert_equal(
		array(
		"a" => "b",
		"c" => "d"
		),
		array(
		"a" => "b",
		"c" => "d"
		),
		function($a, $b){
			return $a === $b;
		}
	);
});

TEST::runTest("Test assert_not_equal assoc_array with func for ===", function(){
	return TEST::assert_not_equal(
		array(
		"a" => "b",
		"c" => "d"
		),
		array(
		"c" => "d",
		"a" => "b"
		),
		function($a, $b){
			return $a === $b;
		}
	);
});

TEST::runTest("Test assert_equal assoc_array out of order", function(){
	return TEST::assert_equal(
		array(
		"a" => "b",
		"c" => "d"
		),
		array(
		"c" => "d",
		"a" => "b"
		)
	);
});

TEST::runTest("Test assert_not_equal assoc_array simple", function(){
	return TEST::assert_not_equal(
		array(
		"a" => "b",
		"c" => "d"
		),
		array(
		"c" => "de",
		"a" => "b"
		)
	);
});

TEST::runTest("Test assert_not_equal num", function(){
	return TEST::assert_not_equal(0, -1);
});

TEST::runTest("Test assert_not_equal 0/null with ===", function(){
	return TEST::assert_not_equal(0, null, function($a, $b){return $a === $b;});
});
TEST::runTest("Test assert_equal 0/null", function(){
	return TEST::assert_equal(0, null);
});

TEST::runTest("Test assert_equal strings", function(){
	return TEST::assert_equal("hello", "hello");
});
TEST::runTest("Test assert_not_equal strings", function(){
	return TEST::assert_not_equal("ello", "hello");
});

TEST::runTest("Test assert_empty strings", function(){
	return TEST::assert_empty("");
});
TEST::runTest("Test assert_empty array", function(){
	return TEST::assert_empty(array());
});

TEST::runTest("Test assert_empty 0", function(){
	return TEST::assert_empty(0);
});
TEST::runTest("Test assert_empty false", function(){
	return TEST::assert_empty(false);
});
TEST::runTest("Test assert_not_empty 1", function(){
	return TEST::assert_not_empty(1);
});
TEST::runTest("Test assert_not_empty string", function(){
	return TEST::assert_not_empty("hello");
});
TEST::runTest("Test assert_not_empty array", function(){
	return TEST::assert_not_empty(array("yo"));
});
