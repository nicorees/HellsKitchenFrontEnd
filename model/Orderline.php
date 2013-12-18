<?php

class Orderline extends DB {

	private $orderline = NULL;

	private $orderID = NULL;
	private $productID = NULL;
	private $price = 0;
	private $quantity = 0;

	public function __construct($oid, $pid) {

		parent::__construct();

		$sql = sprintf("SELECT * FROM `" . DB . "`.`" . TABLE_ORDERLINE . "` 
						WHERE OrderID = '%s'
						AND ProductID = '%s'
						LIMIT 1;",
						mysql_real_escape_string($oid),
						mysql_real_escape_string($pid));
		
		$result = $this->doQuery($sql);
				
		if ( ! $result ) return FALSE;
		
		$this->orderline = $result->fetch_object();
				
		$this->setInstanceVariables();
		
		return TRUE;
	}

	private function setInstanceVariables() {
		
		$this->setOrderID($this->orderline->OrderID);
		$this->setProductID($this->orderline->ProductID);
		$this->setPrice($this->orderline->Price);
		$this->setQuantity($this->orderline->Quantity);
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

	public function getProductID() {
		return $this->productID;
	}

	public function setProductID($productID) {
		$this->productID = $productID;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setPrice($price) {
		$this->price = $price;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

}