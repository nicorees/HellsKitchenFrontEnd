<?php
	if(isset($_POST['bnt_bestellen'])) {
		
		// erzeuge neues Produkt
		$product = new Product();
		// bekomme alle ausgewälten Zutaten als Arry
		$id = $_POST['IngredientID'];
		// Setze den Namen aus der POST-Variable
		$name = $_POST['txt_pizzaname'];
		// Grundpreis 3.00 €, welcher in TABLE_Product eingepflegt wird
		$price = 3.00;
		// öffentlich oder nicht öffentlich?
		$private = $_POST['private'];
		// Besitzer / Ersteller
		$customerID = $_SESSION['customerID'];		

		$product->setName($name);
		$product->setPrice($price);
		$product->setIngredients($id);
		$product->setPrivate($private);
		$product->setCustomerID($customerID);
		
		if($product->saveProduct())
			header("Location: " . URL_BASE . "?p=displayPrivateProducts");
		else {
		// SET ERROR MESSAGE
		header("Location: " . URL_BASE);
		}
	}
?>