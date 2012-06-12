<?php

class User extends BaseTable{
	
	static $table_name = "users";
	static $constructor_key = "username";
	
	/**
	 * The page to redirect to for all logins. 
	 * @var unknown_type
	 */
	static $login_location = "/login"; 
	
	/**
	 * Loaded when needed, but cached here so 
	 * we don't have to hit the db over and over again. 
	 * @var unknown_type
	 */
	static $all_permissions = array(); 
	
	static $fields = array(
		"username",
		"password",
		"user_token",
		"user_level",
		"email",
		"created"
	);
	static $field_nicks_map = array(
		"username" => "username",
		"password" => "password",
		"user_token" => "user_token",
		"user_perms" => "user_level",
		"email" => "email",
		"created" => "created"
	);
	static $searchable_nicks = array(
		"username",
		"password",
		"user_token",
		"user_perms",
		"email",
		"created"
	);
	static $sortable_nicks = array(
		"username",
		"password",
		"user_id",
		"user_perms",
		"email",
		"created"
	);
	static $editable_nicks = array(
		"username",
		"password",
		"user_id",
		"user_perms",
		"email",
	);
	
	/**
	 * 
	 * Starts a session, then continues as normal. 
	 * 
	 * @param $id
	 * @param $load
	 * @param $row
	 */
	public function __construct($id, $load=true, $row=null){
		session_start();
		return parent::__construct($id, $load, $row);
	}
	
	/**
	 * Usage: 
	 * $user = new User($username);
	 * $user->login($password_or_token);
	 * 
	 * @param $password
	 */
	public function login($password_or_token, $remember_me=false){
		DEBUG::rollcall();
		$valid = false;
		
		// check password
		if(md5($password_or_token) == $this->password) $valid = true;
		
		// check token
		if($password_or_token == $this->user_token) $valid = true;
		
		// if valid set session
		if($valid){
			$_SESSION[SESSION_UN] = $this->username;
			$_SESSION[SESSION_TOKEN] = $this->user_token;
		}else return false;
		
		// if "remember password" set cookie with token
		if($remember_me){
			$expiration = time() + COOKIE_EXPIRATION;
			setcookie(COOKIE_UN, $this->username, $expiration, COOKIE_PATH);
			setcookie(COOKIE_TOKEN, $this->user_token, $expiration, COOKIE_PATH); 
		}
		
		$this->setAsActiveUser();
		return true;
	}
	
	
	
	public function logout (){
		DEBUG::rollcall();
		// unset session
		unset($_SESSION[SESSION_UN]);
		unset($_SESSION[SESSION_TOKEN]);
		// unset cookie
		$expiration = time() -99;
		setcookie(COOKIE_UN, '', $expiration, COOKIE_PATH);
		setcookie(COOKIE_TOKEN, '', $expiration, COOKIE_PATH);
	}
	
	
	
	public function getPermissionLabels(){
		static::loadAllPermissions();
		$perms_arr = array();
		$user_mask = $this->user_perms;
		foreach(static::$all_permissions as $permission){
			if($this->hasPermission($permission->mask)){
				DEBUG::writeln("adding permission: $permission->name for 
						user: $this->username because permission mask: $permission->mask 
						and user mask: $user_mask  ($permission->mask & $user_mask = ". 
						($permission->mask & $user_mask) . ")");
				$perms_arr[] = $permission->name;	
			}
		}
		
		return $perms_arr;
	}
	
	public function hasPermission($permission_mask){
		DEBUG::rollcall();
		DEBUG::writeln("this->user_perms: $this->user_perms");
		return ($permission_mask == 0) ||
				(((int)$permission_mask & (int)$this->user_perms) > 0);// bitwise AND for integer masking
	}
	
	public static function loadAllPermissions(){
		if(empty(static::$all_permissions)){
			static::$all_permissions = Permission::search("%");
		}
		
		return static::$all_permissions;
	}
	
	public static function checkLogin(){
		DEBUG::rollcall();
		session_start();
		DEBUG::lvar_dump("SESSION: ", $_SESSION);
		DEBUG::lvar_dump("COOKIE: ", $_COOKIE);
		
		$valid = false;
		
		if(isset($_SESSION[SESSION_UN]) && isset($_SESSION[SESSION_TOKEN])){
			$existing_user = User::search(array(
				"username" => $_SESSION[SESSION_UN],
				"user_token" => $_SESSION[SESSION_TOKEN]
			));
			// if session is set with un and token, valid
			if(!empty($existing_user)){
				$valid = true;
			}
		}else{ // if the session is already valid, don't let the cookie overwrite it
			
			if(isset($_COOKIE[COOKIE_UN]) && isset($_COOKIE[COOKIE_TOKEN])){
				// if cookie is set with un and token, valid
				$existing_user = User::search(array(
					"username" => $_COOKIE[COOKIE_UN],
					"user_token" => $_COOKIE[COOKIE_TOKEN]
				));
				if(!empty($existing_user)){
					$valid = true;
					//copy the cookie into the session
					$_SESSION[SESSION_UN] = $_COOKIE[COOKIE_UN];
					$_SESSION[SESSION_TOKEN] = $_COOKIE[COOKIE_TOKEN];
				}
			}
		}
		
		if($valid) {
			$existing_user[0]->setAsActiveUser();// update 
			return $existing_user[0];
		} else return false;
	}
	/**
	 * Register a new user.
	 * 
	 * Checks to see if the username already exists. 
	 * MD5s the password, creates a token, then creates the record. 
	 * 
	 * @param unknown_type $knownData
	 */
	public static function register($knownData){
		extract(FPX::contract(array(
			'required' => array('username', 'password'),
			'optional' => array_keys(static::$field_nicks_map)
		)));
		
		// check if username exists
		if($existing_user = User::search(array("username" => $knownData['username']))){
			return false;
		}
		
		$knownData['password'] = md5($knownData['password']);
		$knownData['user_token'] = static::createToken($knownData);
		
		return static::create($knownData);
	}
	
	public static function check_permissions(){
//		DEBUG::activate();
		DEBUG::rollcall();
		$ret = false;
		$user = User::checkLogin();// get the active user
		$cur_url = curPageURL();
		$page_mask = PermissionsPage::getPermissionMask($cur_url);// get the page's permissions
		$_SESSION['previous_location'] = $_SESSION['location'];
		$_SESSION['location'] = $cur_url; 
		DEBUG::writeln("setting _SESSION['previous_location'] to {$_SESSION['location']} and _SESSION['location'] to $cur_url"); 
		if(!$user && $page_mask > 0) { // not logged in and the page has restrictions
			header("Location: ". static::$login_location);
			exit;
		}else if(!$user && ($page_mask == 0 || $page_mask == -1)){ // not logged in, but no restrictions on the page
			$ret = 0; // falsy, but not false
		}else{
		
			DEBUG::writeln("user $user->username ($user->user_perms) and url $cur_url ($page_mask)");
			if($user->hasPermission($page_mask)){ 
				DEBUG::writeln("user permissions accepted. ($user->username $user->user_perms , $cur_url $page_mask)");
				$ret = $user;
			}else{
				echo "Improper Permissions";
				die();
			}
		}
		return $ret;
	}
	
	
	protected static function createToken($knownData){
		$time = time();
		$data = implode('', array_values($knownData));
		$rand = rand(0, 100000);
		$token = md5($time . $data . $rand);
		return $token;
	}
	
	
	protected function setAsActiveUser(){
		
	}
	
	
	
}
