<?php

class Order extends DB {

	private $order = NULL;

	private $orderID = NULL;
	private $customerID = NULL;
	private $statusID = NULL;
	private $totalPrice = 0;
	private $time = NULL;
	private $pickup = NULL;
	private $addressID = NULL;

	private $statusText = NULL;
	private $orderLines = array();

	public function __construct($id) {

		parent::__construct();
		
		$sql = sprintf("SELECT * FROM `" . DB . "`.`" . TABLE_ORDERS . "` 
			WHERE OrderID = '%s' LIMIT 1;",
			mysql_real_escape_string($id));
		
		$result = $this->doQuery($sql);
				
		if ( ! $result ) return FALSE;
		
		$this->order = $result->fetch_object();
				
		$this->setInstanceVariables();
		
		return TRUE;
	}

	private function setInstanceVariables() {
		
		$this->setOrderID($this->order->OrderID);
		$this->setCustomerID($this->order->CustomerID);
		$this->setStatusID($this->order->StatusID);
		$this->setTotalPrice($this->order->TotalPrice);
		$this->setTime($this->order->Time);
		$this->setPickup($this->order->Pickup);
		$this->setAddressID($this->order->AddressID);

		$this->setStatusText();
		$this->setOrderLines();
	}

	public static function getCart($customerID) {

		$sql = sprintf("SELECT OrderID FROM `" . DB . "`.`" . TABLE_ORDERS . "` 
			WHERE CustomerID = '%s'
			AND StatusID = 1
			LIMIT 1;",
			mysql_real_escape_string($customerID));
		
		$db = new DB();

		$result = $db->doQuery($sql);
				
		if ( ! $result ) return FALSE;

		$obj = $result->fetch_object();

		return new Order($obj->OrderID);
	}

	public static function removeFromCart($customerID, $productID) {

		$sql = sprintf("SELECT OrderID FROM `" . DB . "`.`" . TABLE_ORDERS . "` 
			WHERE CustomerID = '%s'
			AND StatusID = 1
			LIMIT 1;",
			mysql_real_escape_string($customerID));
		
		$db = new DB();

		$result = $db->doQuery($sql);
				
		if ( ! $result ) return FALSE;

		$orderID = $result->fetch_object()->OrderID;

		$sql = sprintf("DELETE FROM `" . DB . "`.`" . TABLE_ORDERLINE . "` 
			WHERE OrderID = '%s'
			AND ProductID = '%s'
			LIMIT 1;",
			mysql_real_escape_string($orderID),
			mysql_real_escape_string($productID));

		$result = $db->doQuery($sql);

		if ( ! $result ) return FALSE;

		return TRUE;
	}

	public static function addToCart($customerID, $products) {

		$sql = sprintf("SELECT OrderID FROM `" . DB . "`.`" . TABLE_ORDERS . "` 
			WHERE CustomerID = '%s'
			AND StatusID = 1
			LIMIT 1;",
			mysql_real_escape_string($customerID));
		
		$db = new DB();

		$result = $db->doQuery($sql);
				
		if ( ! $result ) return FALSE;

		$orderID = $result->fetch_object()->OrderID;

		$cart = self::getCart($customerID);

		foreach ($products as $i => $productID) {

			$product = new Product($productID);
			$quantity = 1;

			foreach ($cart->getOrderLines() as $j => $orderline) {
				
				if($orderline->getProductID() == $productID) {
					
					$quantity = $orderline->getQuantity() + 1;

					$sql = sprintf("UPDATE `" . DB . "`.`" . TABLE_ORDERLINE . "` 
						SET `Quantity`='%s'
						WHERE `OrderID`='%s'
						AND `ProductID`='%s';",
						mysql_real_escape_string($quantity),
						mysql_real_escape_string($orderID),
						mysql_real_escape_string($productID));

					$result = $db->doQuery($sql);

					if ( ! $result ) return FALSE;

					// we are finished with this product at this point,
					// so jump to end of this (outer) loop iteration.
					goto bottom;
				}
			}

			$sql = sprintf("INSERT INTO `" . DB . "`.`" . TABLE_ORDERLINE . "` 
				VALUES ('%s','%s','%s','%s');",
				mysql_real_escape_string($orderID),
				mysql_real_escape_string($productID),
				mysql_real_escape_string($product->getPrice()),
				"1");

			$result = $db->doQuery($sql);

			if ( ! $result ) return FALSE;

			//label for goto
			bottom:
		}

		return TRUE;
	}

	/*
	 * GETTER UND SETTER
	 */
	public function getOrderID() {
		return $this->orderID;
	}

	public function setOrderID($orderID) {
		$this->orderID = $orderID;
	}

	public function getCustomerID() {
		return $this->customerID;
	}

	public function setCustomerID($customerID) {
		$this->customerID = $customerID;
	}

	public function getStatusID() {
		return $this->statusID;
	}

	public function setStatusID($statusID) {
		$this->statusID = $statusID;
	}

	public function getTotalPrice() {
		return $this->totalPrice;
	}

	public function setTotalPrice($totalPrice) {
		$this->totalPrice = $totalPrice;
	}

	public function getTime() {
		return $this->time;
	}

	public function setTime($time) {
		$this->time = $time;
	}

	public function getPickup() {
		return $this->pickup;
	}

	public function setPickup($pickup) {
		$this->pickup = $pickup;
	}

	public function getAddressID() {
		return $this->addressID;
	}

	public function setAddressID($addressID) {
		$this->addressID = $addressID;
	}

	public function getStatusText() {
		return $this->statusText;
	}

	private function setStatusText() {
		$sql = sprintf("SELECT `Statusname` 
				FROM `". DB ."`.`". TABLE_STATUS ."` 
				WHERE StatusID = '%s';", 
				mysql_real_escape_string($this->getStatusID()));
		
		$result = $this->doQuery($sql)->fetch_object();
		
		$this->statusText = $result->Statusname;
	}

	public function getOrderLines() {
		return $this->orderLines;
	}

	private function setOrderLines() {
		$sql = sprintf("SELECT `OrderID`, `ProductID` 
				FROM `". DB ."`.`". TABLE_ORDERLINE ."` 
				WHERE OrderID = '%s';", 
				mysql_real_escape_string($this->getOrderID()));
		
		$result = $this->doQuery($sql);

		if ( $result === FALSE ) {
			return;
		}
		
		while($row = $result->fetch_object())
			array_push($this->orderLines, new Orderline($row->OrderID, $row->ProductID));
	}

}