<?php

class Template{
	var $file;
	var $fields = array();
	
	public function __construct($pArr){
		extract(FPX::contract(array(
			"required" => array("file"),
			"optional" => array("fields")
		)));
		$this->file = $file;
		$this->fields = FPX::pdefault($fields, array(), "falsy");
	}
	
	public function toString($pArr){
		extract(FPX::contract(array(
			"optional" => array("fields")
		)));
		
		FPX::pdefault($fields, array(), "falsy");
		if(!empty($fields)){
			$this->fields = $fields;
		}
		
		extract($this->fields);
		ob_start();
				include($this->file);
		$string = ob_get_clean();
		return $string;
	}
	
	public function toStdOut($pArr){
		extract(FPX::contract(array(
			"optional" => array("fields")
		)));
		
		FPX::pdefault($fields, array(), "falsy");
		if(!empty($fields)){
			$this->fields = $fields;
		}
		
		extract($this->fields);
		return include($this->file);
	}
	
	public function setFields($fields){
		$this->fields = $fields;
	}
}