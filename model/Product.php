<?php

/**
 * Diese Klasse repräsentiert ein Produkt
 * @author Andreas Nenning, Nicholas Rees, Steffen Schenk
 */

class Product extends DB {

	private $product = NULL;

	private $productID = NULL;
	private $name = NULL;
	private $price = 0;
	private $description = NULL;
	private $available = NULL;
	private $customerID = NULL;
	private $private = NULL;
	private $rating = 0;
	private $numberOfRatings = 0;

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
	/*
	Lade Zutaten einer Pizza
	*/
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
		$this->setPrice((double) $this->product->ProductPrice);
		$this->description = $this->product->Description;
		$this->private = $this->product->Private;
		$this->rating = @($this->product->SumOfRating / $this->product->NumOfRater);
		$this->numberOfRatings = $this->product->NumOfRater;

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
	// Gibt alle verfügbaren Zutaten zurück
	private function allIngredientsAvailable() {
		foreach ($this->ingredients as $ing) {
			$ingredient = new Ingredient($ing);

			if( !$ingredient->getAvailability() )
				return FALSE;
		}
		return TRUE;
	}

	/*
	Speichert eine Pizza
	*/
	public function saveProduct(){
	
		//Starte Transaktion
		$this->doQuery("START TRANSACTION");
		$customer = new customer($this->getCustomerID());
			
		//Erstelle insert-Statement
		$sql_save_product = sprintf("INSERT INTO `" . TABLE_PRODUCT . "`
				(`ProductName`, `ProductPrice`, `Private`,`Description`,`Available`,`CustomerID`)
				VALUES ('%s', '%s', '%s', '%s','%s','%s')",
				mysql_real_escape_string($this->getName()),
				mysql_real_escape_string($this->getPrice()),
				mysql_real_escape_string($this->getPrivate()),
				mysql_real_escape_string($this->getDescription()),
				mysql_real_escape_string(1),
				mysql_real_escape_string($this->getCustomerID()));

		$result_save_product = $this->doQuery($sql_save_product);
		$this->setID($this->insert_id());
				
		$ingredients = $this->getIngredients();
		// Verknüpfe Pizza und Zutaten
		foreach ($ingredients  as $i) {
			
			$sql_save_ingredient = sprintf("INSERT INTO `" . TABLE_PRODUCT_HAS_INGREDIENT . "`
					(`IngredientID`, `ProductID`)
					VALUES ('%s', '%s')",
					mysql_real_escape_string($i),
					mysql_real_escape_string($this->getID()));			
					
			$result_save_ingredient = $this->doQuery($sql_save_ingredient);				
		}
		
		//wenn alles glatt lief, commit, ansonsten rollback
		if ($result_save_product && $result_save_ingredient) {
		    $this->doQuery("COMMIT");
		    return TRUE;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}		
	}
	// Ändert die Sichtbarkeit einer Pizza
	public static function changeProductVisibility($productID) {
		
		$product = new Product($productID);
		// negiere Attribut "private" 
		$private = ($product->getPrivate()) ? 0 : 1;

		$sql = sprintf("UPDATE `" . DB . "`.`" . TABLE_PRODUCT . "` 
			SET `Private` = '%s'
			WHERE `ProductID` = '%s';",
			mysql_real_escape_string($private),
			mysql_real_escape_string($productID));
		
		$db = new DB();
				
		return $db->doQuery($sql);
	}
	// Bewerte ein Produkt
	public static function rateProduct($productID, $rating) {
		
		$sql = sprintf("UPDATE `" . DB . "`.`" . TABLE_PRODUCT . "` 
			SET `NumOfRater` = NumOfRater + 1, `SumOfRating` = SumOfRating + %s
			WHERE `ProductID` = '%s';",
			mysql_real_escape_string($rating),
			mysql_real_escape_string($productID));
		
		$db = new DB();

		return $db->doQuery($sql);
	}
	
	/*
	 * getter und setter
	 */

	public function getID() {
		return $this->productID;
	}

	public function setID($productID) {
		$this->productID = $productID;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
	// Falls der Name leer ist, Bennen Pizza nach Kundenname
		if(empty($name)) {
			$customer = new Customer($this->getCustomerID());
			$name = $customer->getFirstname() . "'s Custom Pizza";
		}

		$this->name = substr($name,0,45);
	}

	public function getPrice() {
		return $this->price;
	}
	// Setze den Preis der Pizza aus den Zutatenpreisen + Grundpreis zusammen 
	public function setPrice($price) {
		foreach ($this->ingredients as $ing) {
			$ingredient = new Ingredient($ing);

			$price += $ingredient->getPrice();
		}

		$this->price = $price;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		// Fall die Beschreibung leer ist, füge Standardbeschreibung ein
		if(empty($description)) {
			$customer = new Customer($this->getCustomerID());
			$description = "Erstellt von " .  $customer->getFirstname() . " " . $customer->getLastname();
		}

		$this->description = substr($description,0,45);
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

	public function getRating() {
		return $this->rating;
	}

	public function setRating($rating) {
		$this->rating = $rating;
	}

	public function getNumberOfRatings() {
		return $this->numberOfRatings;
	}

	public function setNumberOfRatings($numberOfRatings) {
		$this->numberOfRatings = $numberOfRatings;
	}
}

//end of Product.php