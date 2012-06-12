<?php
class BannedUser extends BaseTable{

	static $table_name = "users_banned";
	
	static $fields = array(
		"username",
		"timestamp"
	);
	static $field_nicks_map = array(
		"username" => "username",
		"timestamp" => "timestamp"
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