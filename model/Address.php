<?php

/**
 * Diese Klasse repräsentiert eine Adresse eines Kunden
 * @author Nicholas Rees, Steffen Schenk
 */

class Address extends DB{

	private $address = NULL;

	private $addressID = NULL;
	private $customerID = NULL;
	private $street = NULL;
	private $zip = NULL;
	private $city = NULL;
	private $distance = NULL;

	public function __construct($id) {

		parent::__construct();
		
		$sql = sprintf("SELECT * FROM `" . DB . "`.`" . TABLE_ADDRESS . "` 
			WHERE AddressID = '%s' LIMIT 1;",
			mysql_real_escape_string($id));
		
		$result = $this->doQuery($sql);
				
		if ( ! $result ) return FALSE;
		
		$this->address = $result->fetch_object();
				
		$this->setInstanceVariables();
		
		return TRUE;
	}

	public function setInstanceVariables() {
		$this->setAddressID($this->address->AddressID);
		$this->setCustomerID($this->address->CustomerID);
		$this->setStreet($this->address->Street);
		$this->setZip($this->address->ZIP);
		$this->setCity($this->address->City);
		$this->setDistance($this->address->Distance);
	}

	public function calculateCurrentDeliveryTime() {

		//setze addresse als ganzen string zusammen 
		$address_combined = sprintf("%s %s, %s",
			$this->getZip(),
			$this->getCity(),
			$this->getStreet());

		//bereite url für googlemaps api call vor
		$googlemaps_url = sprintf(GOOGLEMAPS_API_URL,
			$address_combined,
			MAIN_ADDRESS);

		//führe googlemaps api call durch
		if($xml=simplexml_load_file($googlemaps_url)){
			if( $xml->row->element->status == 'OK' ) {
				$time = preg_replace('/\skm/', '', $xml->row->element->duration->text);
				$time = preg_replace('/,/', '.', $time);
				return $time;
			} else
				return FALSE;
		}
	}
	
	public static function calculateDeliveryCosts($id) {

		$db = new DB();

		$sql = sprintf("SELECT * FROM `" . DB . "`.`" . TABLE_DIST_CLASS . "`");
		
		$result = $db->doQuery($sql);
				
		if ( ! $result ) return FALSE;
		
		$distClass1 = $result->fetch_object();
		$distClass2 = $result->fetch_object();
		$distClass3 = $result->fetch_object();

		$sql = sprintf("SELECT `Distance` FROM `" . DB . "`.`" . TABLE_ADDRESS . "`
			WHERE `AddressID` = %s",
			mysql_real_escape_string($id));
		
		$result = $db->doQuery($sql);
				
		if ( ! $result ) return FALSE;

		$distance = $result->fetch_object()->Distance;

		$deliveryCosts = 0;

		if($distance >= 0 && $distance <= $distClass1->Maximum)
			$deliveryCosts = $distClass1->Price;
		elseif ($distance > $distClass1->Maximum && $distance <= $distClass2->Maximum)
			$deliveryCosts = $distClass2->Price;
		elseif ($distance > $distClass2->Maximum && $distance <= $distClass3->Maximum)
			$deliveryCosts = $distClass3->Price;
		else
			return FALSE;

		return $deliveryCosts;
	}

	public function getAddressID() {
		return $this->addressID;
	}

	public function setAddressID($addressID) {
		$this->addressID = $addressID;
	}

	public function getCustomerID() {
		return $this->customerID;
	}

	public function setCustomerID($customerID) {
		$this->customerID = $customerID;
	}

	public function getStreet() {
		return $this->street;
	}

	public function setStreet($street) {
		$this->street = $street;
	}

	public function getZip() {
		return $this->zip;
	}

	public function setZip($zip) {
		$this->zip = $zip;
	}

	public function getCity() {
		return $this->city;
	}

	public function setCity($city) {
		$this->city = $city;
	}

	public function getDistance() {
		return $this->distance;
	}

	public function setDistance($distance) {
		$this->distance = $distance;
	}
	
}

// end of Address.php