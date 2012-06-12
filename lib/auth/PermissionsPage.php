<?php
class PermissionsPage extends BaseTable{
	
	static $table_name = "permissions_pages";
	
	static $fields = array(
		"id",
		"page_url",
		"pp_mask"
	);
	static $field_nicks_map = array(
		"id" => "id",
		"url" => "page_url",
		"mask" => "pp_mask"
	);
	static $searchable_nicks = array(
		"url",
		"mask"
	);
	static $sortable_nicks = array(
		"url",
		"mask"
	);
	static $editable_nicks = array(
		"url",
		"mask"
	);
	
	public function hasPermission($permission_mask){
		return (((int)$permission_mask & (int)$this->mask) > 0);// bitwise AND for integer masking
	}
	
	/**
	 * 
	 * @param unknown_type $url
	 */
	public static function getPermissionMask($url){
		DEBUG::rollcall();
		$mask = -1;
		// strip http:// and www
		$clean_url = str_replace(array("http://", "https://", "www."), "", $url);
		$url_tokens = explode("/", $clean_url);
		
		do {
			DEBUG::lvar_dump("url_tokens: ", $url_tokens);
			$pages = static::search(array('url' => implode("/", $url_tokens)));
			if(!empty($pages)){ //check first, since the first search is done outside the loop
				DEBUG::lvar_dump("searched url was found: ", $pages);
				$mask = $pages[0]->mask;// the first found page is the most restrictive
				break;
			}
			$token = array_pop($url_tokens);
			DEBUG::lvar_dump("token test: ", $token);
		} while(isset($token));
		
		DEBUG::writeln("mask found for url $url ($mask)");
		return $mask;
	}
	
}