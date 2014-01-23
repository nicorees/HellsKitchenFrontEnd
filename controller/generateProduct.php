<?php

	/**
	 * Dieser Controller legt ein neues vom Kunden erstelltes Produkt an
	 * @author Andreas Nenning, Steffen Schenk
	 */

	if(isset($_POST['bnt_bestellen'])) {
		
		// erzeuge neues Produkt
		$product = new Product();
		
		// bekomme alle ausgewälten Zutaten als Arry
		if(isset($_POST['IngredientID']))
			$id = $_POST['IngredientID'];
		else
			$id = array();

		// Setze den Namen aus der POST-Variable
		$name = $_POST['txt_pizzaname'];
		
		// Grundpreis 3.00 €, welcher in TABLE_Product eingepflegt wird
		$price = 3.00;
		
		// öffentlich oder nicht öffentlich?
		if(isset($_POST['private']))
			$private =  $_POST['private'];
		else
			$private = TRUE;
		
		// Besitzer / Ersteller
		$customerID = $_SESSION['customerID'];	

		$product->setCustomerID($customerID);
		$product->setName($name);
		$product->setPrice($price);
		$product->setIngredients($id);
		$product->setPrivate($private);
		$product->setDescription($_POST['txt_pizzadesc']);
		
		if($product->saveProduct())
			header("Location: " . URL_BASE . "?p=displayPrivateProducts");
		else {
			// SET ERROR MESSAGE
			header("Location: " . URL_BASE);
		}
	}
?>