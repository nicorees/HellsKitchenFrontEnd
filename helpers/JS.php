<?php

class JS {
	
	public static function get($js) {
		return self::get_custom(URL_BASE . "assets/js/$js");
	}
	
	public static function get_custom($js) {
		return "<script type=\"text/javascript\" src=\"$js\"></script>";
	}
	
}

// end of JS.php