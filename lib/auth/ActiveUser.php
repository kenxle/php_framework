<?php
class ActiveUser extends BaseTable{

	static $table_name = "users_active";
	
	static $fields = array(
		"username",
		"timestamp"
	);
	static $field_nicks_map = array(
		"username" => "username",
		"timestamp" => "timestampe"
	);
	static $searchable_nicks = array(
		"username",
		"timestamp"
	);
	static $sortable_nicks = array(
		"username",
		"timestamp"
	);
	static $editable_nicks = array(
		"username",
		"timestamp"
	);
}