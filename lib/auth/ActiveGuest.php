<?php
class ActiveGuest extends BaseTable{

	static $table_name = "users_active_guest";
	
	static $fields = array(
		"ip",
		"timestamp"
	);
	static $field_nicks_map = array(
		"ip" => "ip",
		"timestamp" => "timestamp"
	);
	static $searchable_nicks = array(
		"ip",
		"timestamp"
	);
	static $sortable_nicks = array(
		"ip",
		"timestamp"
	);
	static $editable_nicks = array(
		"ip",
		"timestamp"
	);
}