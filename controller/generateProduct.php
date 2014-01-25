<?php

	/**
	 * Dieser Controller legt ein neues vom Kunden erstelltes Produkt an
	 * @author Andreas Nenning, Steffen Schenk
	 */

	if(isset($_POST['btn_erstellen'])) {
		
		// erzeuge neues Produkt
		$product = new Product();
		
		// bekomme alle ausgewälten Zutaten als Arry
		if(isset($_POST['IngredientID']))
			$id = $_POST['IngredientID'];
		else
			$id = array();

		// Setze den Namen aus der POST-Variable
		$name = $_POST['txt_pizzaname'];

		// Setze die Beschreiben aus der POST-Variable
		$description = $_POST['txt_pizzadesc'];
		
		// Grundpreis € 3.00, welcher in TABLE_Product eingepflegt wird
		$price = 3.00;
		
		// öffentlich oder nicht öffentlich?
		if(isset($_POST['private']))
			$private =  $_POST['private'];
		else
			$private = TRUE;

		if(empty($name) || empty($description) || empty($id))
			header("Location: " . URL_BASE . "?p=configurePizza&e=productCreationFailed");
		
		// Besitzer / Ersteller
		$customerID = $_SESSION['customerID'];

		$product->setCustomerID($customerID);
		$product->setName($name);
		$product->setPrice($price);
		$product->setIngredients($id);
		$product->setPrivate($private);
		$product->setDescription($description);
		
		if($product->saveProduct())
			header("Location: " . URL_BASE . "?p=displayPrivateProducts&e=productCreated");
		else {
			header("Location: " . URL_BASE . "?p=configurePizza&e=productCreationFailed");
		}
	}
?>