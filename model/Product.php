<?php

class Product extends DB {

	private $product = NULL;

	private $productID = NULL;
	private $name = NULL;
	private $price = 0;
	private $description = NULL;
	private $available = NULL;
	private $customerID = NULL;
	private $private = NULL;

	private $ingredients = array();
	

	public function __construct($id = NULL) {

		parent::__construct();
		
		// Produkt anhand ProductID laden
		if ($id !== NULL && is_numeric($id) && $id > 0) {
			$this->load($id);
		}
		// Produkt anhand eines Objektes laden
		else if ($id !== NULL && is_object($id)) {
			$this->loadFromObject($id);
		}
		
		return $this;
	}
	
	/*
	lädt ein Produkt anhand der ProductID
	*/
	private function load($product_id) {
		if ( ! is_numeric($product_id) || $product_id < 1) return FALSE;
		
		$sql = sprintf("SELECT * FROM `" . DB . "`.`" . TABLE_PRODUCT . "` 
			WHERE ProductID = '%s' LIMIT 1;",
			mysql_real_escape_string($product_id));
		
		$result = $this->doQuery($sql);
				
		if ( ! $result) return FALSE;
		
		$this->product = $result->fetch_object();
		
		$this->loadIngredients($product_id);
				
		$this->setInstanceVariables();
		
		return TRUE;
	}

	/*
	lädt einen Artikel anhand eines Datenbank Objektes
	*/
	private function loadFromObject($product) {
		if (is_null($product) || ! is_object($product)) return FALSE;
		
		$this->product = $product;
		
		$this->loadIngredients($product->ProductID);
		
		$this->setInstanceVariables();
		
		return TRUE;
	}
	
	private function loadIngredients($productid) {
		$sql = "SELECT `IngredientID` FROM `". DB ."`.`". TABLE_PRODUCT_HAS_INGREDIENT ."` WHERE ProductID = '$productid';";
		
		$result = $this->doQuery($sql);
		
		while($row = $result->fetch_object())
			array_push($this->ingredients, $row->IngredientID);
	}

	/*
	setzt die Instanz Variablen
	*/
	private function setInstanceVariables() {
		if ( is_null($this->product) ) return FALSE;	
		
		$this->productID = $this->product->ProductID;
		$this->name = $this->product->ProductName;
		$this->price = (double) $this->product->ProductPrice;
		$this->description = $this->product->Description;
		$this->private = $this->product->Private;

		$this->setAvailable( (boolean)$this->product->Available );
		
		if(isset($this->product->CustomerID))
			$this->customerID = $this->product->CustomerID;
	
		return TRUE;
	}

	/*
	 * gibt ein Array mit allen Produkten zurück:
	 *  >deren Verfügbarkeit TRUE ist,
	 *  >deren Verfügbarkeit aller Zutaten TRUE ist
	 *  >deren status auf "öffentlich" gesetzt ist
	 * 
	 * @return Array mit Produkten
	 *         FALSE, wenn keine Produkte geladen werden konnte
	*/
	public static function getAllPublicAvailableProducts() {
		// DB Referenz erstellen
		$db = new parent;
		
		$sql = "SELECT * FROM `" . DB . "`.`" . TABLE_PRODUCT . "` WHERE Private = 0;";		
		
		$result = $db->doQuery($sql);
		
		if ( ! $result ) return FALSE;
		
		$productArray = array();
		
		while ($row = $result->fetch_object()) {
			$product = new Product($row);
			if($product->isAvailable())
				$productArray[] = $product;
		}

		return $productArray;
	}

	// gibt die Produkte zurück, die einem Kunden zugeschrieben sind; unabhängig von öffentlich / privat
	public static function getCustomerProducts($customerID) {
		// DB Referenz erstellen
		$db = new parent;
	
		$sql = "SELECT * FROM `" . DB . "`.`" . TABLE_PRODUCT ."`  WHERE CustomerID =".$customerID.";";

	
		$result = $db->doQuery($sql);
		
		if ( ! $result ) return FALSE;
		
		$a = array();
		
		while ($row = $result->fetch_object())  $a[] = new Product($row);
		
		return $a;
	}

	private function allIngredientsAvailable() {
		foreach ($this->ingredients as $ing) {
			$ingredient = new Ingredient($ing);

			if( !$ingredient->getAvailability() )
				return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * getter und setter
	 */

	public function getID() {
		return $this->productID;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice($price) {
		$this->price = $price;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function getIngredients() {
		return $this->ingredients;
	}

	public function setIngredients($ingredients) {
		$this->ingredients = $ingredients;
	}

	public function isAvailable() {
		return $this->available;
	}

	private function setAvailable($available) {

		if( $available && $this->allIngredientsAvailable() )
			$this->available = TRUE;
		else
			$this->available = FALSE;
	}

	public function getCustomerID() {
		return $this->customerID;
	}

	public function setCustomerID($customerID) {
		$this->customerID = $customerID;
	}

	public function getPrivate() {
		return $this->private;
	}

	public function setPrivate($private) {
		$this->private = $private;
	}
}

//end of Product.php