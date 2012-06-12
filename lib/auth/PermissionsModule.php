<?php
class PermissionsModule extends BaseTable{

	static $table_name = "permissions_modules";
	
	static $fields = array(
		"id",
		"module_name",
		"pm_mask"
	);
	static $field_nicks_map = array(
		"id" => "id",
		"module_name" => "module_name",
		"mask" => "pm_mask"
	);
	static $searchable_nicks = array(
		"module_name",
		"mask"
	);
	static $sortable_nicks = array(
		"module_name",
		"mask"
	);
	static $editable_nicks = array(
		"module_name",
		"mask"
	);
}