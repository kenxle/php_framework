<?php

class TEST extends DEBUG {
	
	static $debug = false;
	static $buffer = false;
	static $thebuffer = array();
	
	public function assert($clause1, $clause2, $eq=null){
//		static::fvar_dump($clause1);
//		static::fvar_dump($clause2);
		if($eq)
			static::lvar_dump("equality function: ", $eq);
		
		if($eq){
			$result = $eq($clause1, $clause2);
		}else{
			$result = $clause1 == $clause2;
		}
		
		static::lvar_dump("RESULT: ", $result);
		return $result;
	}
}