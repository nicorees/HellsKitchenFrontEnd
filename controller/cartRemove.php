<?php

$productID = $_GET['pid'];
$customerID = $_SESSION['customerID'];

if ( Order::removeFromCart($customerID, $productID) )
	header("Location: " . URL_BASE . "?p=cart");
else
	//SET ERROR MESSAGE
	header("Location: " . URL_BASE);
?>