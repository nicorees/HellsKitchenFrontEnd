<?php

class Session {
	
	public function __construct() {
		session_start();
	}
	
	public static function init() {
		return new Session();
	}
	
	public static function set($key, $value) {
		$_SESSION[$key] = base64_encode($value);
	}
	
	public static function get($key) {
		if (isset($_SESSION[$key])) return base64_decode($_SESSION[$key]);
		
		return FALSE;
	}
	
	public static function remove($key) {
		if (isset($_SESSION[$key])) unset($_SESSION[$key]);
		
		return TRUE;
	}
	
	public static function destroy() {
		session_destroy();
	}
	
}

// end of Session.php