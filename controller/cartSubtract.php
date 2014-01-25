<?php

/**
 * Dieser Controller vermindert die Anzahl eines Produkts
 * in dem Warenkorb um Eins.
 * @author Nicholas Rees
 */

$productID = $_GET['pid'];
$customerID = $_SESSION['customerID'];

if ( Order::subtractFromCart($customerID, $productID) )
	header("Location: " . URL_BASE . "?p=cart");
else
	//SET ERROR MESSAGE
	header("Location: " . URL_BASE);
?>