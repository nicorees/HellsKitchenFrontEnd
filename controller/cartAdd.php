<?php

	/**
	 * Dieser Controller fügt dem Warenkorb ein Produkt hinzu
	 * @author Nicholas Rees
	 */

	$customerID = $_SESSION['customerID'];
	$products = array();

	foreach ($_POST as $key => $value) {		
		if(is_numeric($key))
			array_push($products, $key);
	}

	if(Order::addToCart($customerID, $products))
		header("Location: " . URL_BASE . "?p=cart");
	else {
		// SET ERROR MESSAGE
		header("Location: " . URL_BASE);
	}
?>