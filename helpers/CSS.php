<?php

class CSS {
	
	public static function get($css) {
		return self::get_custom(URL_BASE . "assets/css/$css");
	}
	
	public static function get_custom($css) {
		return "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css\" />";
	
	}
	
}

// end of CSS.php