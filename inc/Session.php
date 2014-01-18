<?php

class Session {
	
	public function __construct() {
		session_start();
	}
	
	public static function init() {
		return new Session();
	}
	
	public static function destroy() {
		session_destroy();
	}
	
}

// end of Session.php