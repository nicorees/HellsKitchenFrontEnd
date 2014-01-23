<?php

	/**
	 * Dieser Controller wandelt den Warenkorb in eine Bestellung um
	 * @author Nicholas Rees
	 */

	$cart = Order::getCart($_SESSION['customerID']);
	$cart->setStatusID(2);

	if(!empty($cart->getOrderLines()) && $cart->save())
		header("Location: ?p=displayAllOrders");
	else {
		//SET ERROR MSG
		header("Location: .");
	}
?>