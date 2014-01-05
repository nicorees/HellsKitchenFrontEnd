<?php
	$cart = Order::getCart($_SESSION['customerID']);
	$cart->setStatusID(2);

	if(!empty($cart->getOrderLines()) && $cart->save())
		header("Location: .");
	else {
		//SET ERROR MSG
		header("Location: .");
	}
?>