<?php

/**
 * Alle Models erben von dieser Klasse, sie erm�glicht
 * Datenbank Operationen
 * @author Nicholas Rees
 */

class DB {
	
	private $con;
	
	public function __construct() {
		$this->con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB);
		$this->con->query("SET NAMES 'utf8'");
	}
	
	/*
	f�hrt die angegebene SQL Query durch
	*/
	public function doQuery($sql) {
		return $this->con->query($sql);
	}
	
	/*
	gibt die zuletzt eingef�gte Auto-Increment ID zur�ck
	*/
	public function insert_id() {
		return $this->con->insert_id;
	}
}

// end of DB.php