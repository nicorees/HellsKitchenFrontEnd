<?php

	/**
	 * Dieser Controller fügt dem Warenkorb ein Produkt hinzu
	 * @author Nicholas Rees
	 */

	$customerID = $_SESSION['customerID'];
	$products = array();
	
	if (isset($_GET['pid'])) {
		if(is_numeric($_GET['pid']))
			array_push($products, $_GET['pid']);
	} elseif (isset($_POST)) {
		foreach ($_POST as $key => $value) {		
			if(is_numeric($key))
				array_push($products, $key);
		}
	} else {
		header("Location: .");
	}

	if(Order::addToCart($customerID, $products))
		header("Location: " . URL_BASE . "?p=cart");
	else {
		// SET ERROR MESSAGE
		header("Location: " . URL_BASE);
	}
?>