<?php

/**
 * Alle Models erben von dieser Klasse, sie ermöglicht
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
	führt die angegebene SQL Query durch
	*/
	public function doQuery($sql) {
		return $this->con->query($sql);
	}
	
	/*
	gibt die zuletzt eingefügte Auto-Increment ID zurück
	*/
	public function insert_id() {
		return $this->con->insert_id;
	}
}

// end of DB.php