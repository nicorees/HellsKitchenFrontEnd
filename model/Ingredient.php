<?php

class Ingredient extends DB {

	private $ingredient = NULL;
	private $ingredientID = null;
	private $name = NULL;
	private $price = 0;
	private $available = NULL;
	private $picture = NULL;

	public function __construct($id = NULL) {

		parent::__construct();
		
		// Zutat anhand ingredientID laden
		if ($id !== NULL && is_numeric($id) && $id > 0) {
			$this->load($id);
		}
		// Zutat anhand eines Objektes laden
		else if ($id !== NULL && is_object($id)) {
			$this->loadFromObject($id);
		}
		
		return $this;

	}
	
	/*
	gibt den Namen der jeweiligen Zutat zurück
	@return String
	*/
	public function getName() {
		return $this->name;
	}

	/*
	gibt den Preis des jeweiligen Zutat zurück
	*/
	public function getPrice() {
		return $this->price;
	}
	
		/*
	gibt die ID der jeweiligen Zutat zurück
	*/
	public function getID() {
		return $this->ingredientID;
	}
	
	/*
	gibt an ob eine Zutat verfügbar ist
	*/
	public function getAvailability() {
		return $this->available;
	}

	public function getPicture(){
		return $this->picture;
	}
	
	/*
	löscht eine Zutat
	*/
	public function deleteIngredient($ingredient_id){
	
	$db = new parent;
	
	$sql = "DELETE * FROM `" . DB . "`.`" . TABLE_INGREDIENT . "` WHERE IngredientID = '$ingredient_id';";
	
	$result = $this->doQuery($sql);
	 	
	}
	

	/*
	lädt eine Zutat anhand der IngredientID
	*/
	private function load($ingredient_id) {
		if ( ! is_numeric($ingredient_id) || $ingredient_id < 1) return FALSE;
		
		$sql = "SELECT * FROM `" . DB . "`.`" . TABLE_INGREDIENT . "` WHERE IngredientID = '$ingredient_id' LIMIT 1;";
		
		$result = $this->doQuery($sql);
		
		if ( ! $result) return FALSE;
		
		$this->ingredient = $result->fetch_object();
		$this->setInstanceVariables();
		
		return TRUE;
	}

	/*
	lädt eine Zutat anhand eines Datenbank Objektes
	*/
	private function loadFromObject($ingredient) {
		if (is_null($ingredient) || ! is_object($ingredient)) return FALSE;
		
		$this->ingredient = $ingredient;
		$this->setInstanceVariables();
		
		return TRUE;
	}
	
	

	/*
	setzt die Instanz Variablen
	*/
	private function setInstanceVariables() {
		if ( is_null($this->ingredient)) return FALSE;
		
		$this->name = $this->ingredient->IngredientName;
		$this->price = (double) $this->ingredient->Price;
		$this->available = (boolean) $this->ingredient->Available;
		$this->ingredientID = (int) $this->ingredient->IngredientID;
	
		return TRUE;
	}

		/*
	gibt ein Array mit allen Zutaten zurück
	@return Array mit  Zutatan 
			false, wenn keine Zutat geladen werden konnte
	*/
	public static function getAllIngredients() {
		// DB Referenz erstellen
		$db = new parent;
		
		$sql = "SELECT * FROM `" . DB . "`.`" . TABLE_INGREDIENT . "`;";
		
		$result = $db->doQuery($sql);
		
		if ( ! $result) return FALSE;
		
		$a = array();
		
		while ($row = $result->fetch_object()) $a[] = new ingredient($row);
		
		return $a;
	}

}

//end of Ingredient.php