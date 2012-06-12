<?php
class Permission extends BaseTable{
	var $mask;
	var $name;
	
	static $table_name = "permissions";
	static $constructor_key = "mask";
	
	static $fields = array(
		"p_mask",
		"name",
		"display_name"
	);
	static $field_nicks_map = array(
		"mask" => "p_mask",
		"name" => "name",
		"display_name" => "display_name"
	);
	static $searchable_nicks = array(
		"mask",
		"name",
		"display_name"
	);
	static $sortable_nicks = array(
		"mask",
		"name",
		"display_name"
	);
	static $editable_nicks = array(
		"mask",
		"name",
		"display_name"
	);
}