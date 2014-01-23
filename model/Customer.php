<?php

/**
 * Diese Klasse repräsentiert einen Kunden
 * @author Andreas Nenning, Christian Vogg, Nicholas Rees, Steffen Schenk
 */

class Customer extends DB {

	private $customer = NULL;

	private $customerid = NULL;
	private $firstname = NULL;
	private $lastname = NULL;
	private $emailaddress = NULL;
	private $password = NULL;
	private $street = NULL;
	private $city = NULL;
	private $zip = NULL;
	private $distance = NULL;

	private $addressID = NULL;

	//Konstruktor zum laden mit id oder Customer Objekt
	public function __construct($param = NULL) {

		parent::__construct(); 
			
			// Kunden anhand einer CustomerID laden
			if ($param !== NULL && is_numeric($param) && $param > 0) {
				$this->load($param);
			}
			// Kunden anhand eines Objektes laden
			else if ($param !== NULL && is_object($param)) {
				$this->loadFromObject($param);
			}
		
		return $this;
	}

	//Funktion zum anlegen eines neuen Customers
	public function register() {

		//bereite statement für einfügen in tabelle Customer vor
		$sql_insert_customer = sprintf("INSERT INTO `" . DB . "`.`" . TABLE_CUSTOMER . "`
				(`Email`, `Password`, `Firstname`,`Lastname`)
				VALUES ('%s', '%s', '%s', '%s')",
				mysql_real_escape_string($this->getEmailAddress()),
				mysql_real_escape_string($this->getPassword()),
				mysql_real_escape_string($this->getFirstname()),
				mysql_real_escape_string($this->getLastname()));

		//starte transaktion um rollback durchführen zu können, falls was schief geht
		$this->doQuery("START TRANSACTION");

		//füge in tabelle Customer ein
		$result_insert_customer = $this->doQuery($sql_insert_customer);

		//hole die zugehörige customerID und setze Sie im Objekt
		$this->setCustomerID($this->insert_id());

		//berechne Distanz
		if ( !$this->calculateDistance() )
			return FALSE;

		//bereite statement für einfügen in tabelle Address vor
		$sql_insert_address = sprintf("INSERT INTO `" . DB . "`.`" . TABLE_ADDRESS . "`
				(`CustomerID`, `Street`, `ZIP`, `City`, `Distance`)
				VALUES ('%s', '%s', '%s', '%s', '%s')",
				mysql_real_escape_string($this->getCustomerID()),
				mysql_real_escape_string($this->getStreet()),
				mysql_real_escape_string($this->getZip()),
				mysql_real_escape_string($this->getCity()),
				mysql_real_escape_string($this->getDistance()));


		//füge in tabelle address ein
		$result_insert_address = $this->doQuery($sql_insert_address);

		//wenn alles glatt lief, commit, ansonsten rollback
		if ($result_insert_customer && $result_insert_address) {
		    $this->doQuery("COMMIT");
		    return $this->customerid;;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}
	}

	/*
	Authentifiziert einen Kunden anhand von email und passwort
	@return boolean, FALSE wenn die Authentifizierung fehlschlug
	@return Int, CustomerID wenn die Authentifizierung erfolgreich war
	*/
	public static function authenticate($email, $password) {
		if ( empty($email) || empty($password) ) return FALSE;

		$password = md5($password);
		
		$db = new DB();

		$sql = sprintf("SELECT `CustomerID` FROM `" . DB . "`.`" . TABLE_CUSTOMER . "` 
						WHERE `email` = '%s' AND `password` = '%s' LIMIT 1", 
						mysql_real_escape_string($email),
						mysql_real_escape_string($password));

		$result = $db->doQuery($sql);
		
		if ( $result->num_rows == 0 ) {
			return FALSE;
		} else {
			return $result->fetch_object()->CustomerID;
		}
	}

	/*
	lädt einen Kunden anhand einer CustomerID
	*/
	private function load($id) {
		if ( !is_numeric($id) || $id < 1) return FALSE;
		
		$sql = "SELECT * FROM `" . DB . "`.`" . TABLE_CUSTOMER . "` 
		JOIN `" . DB . "`.`" . TABLE_ADDRESS . "`USING(CustomerID)
		WHERE CustomerID = '" . $id . "' LIMIT 1;";

		$result = $this->doQuery($sql);
		
		if ( ! $result) return FALSE;
		
		$this->customer = $result->fetch_object();
		$this->setInstanceVariables();
		
		return TRUE;
	}

	/*
	lädt einen Kunden anhand eines Datenbank Objektes
	*/
	private function loadFromObject($customer) {
		if (is_null($customer) || ! is_object($customer)) return FALSE;
		
		$this->customer = $customer;
		$this->setInstanceVariables();
		
		return TRUE;
	}

	/*
	speichert das aktuelle Customer Objekt in der Datenbank
	@return true, wenn der Kunde erfolgreich gespeichert werden konnte
			false, wenn der Kunde nicht erfolgreich gespeichert werden konnte
	*/
	public function save($addressID) {		
		
		//query for updating the customer's firstname and lastname
		$sql_update_customer = sprintf("UPDATE `" . DB . "`.`" . TABLE_CUSTOMER . "` 
			SET firstname = '%s',
			lastname = '%s' 
			WHERE CustomerID = '%s';",
			mysql_real_escape_string($this->getFirstname()),
			mysql_real_escape_string($this->getLastname()),
			mysql_real_escape_string($this->getCustomerID()));

		if( !$this->calculateDistance() )
			return FALSE;

		//query for updating the customer's street, city, zip
		$sql_update_address = sprintf("UPDATE `" . DB . "`.`" . TABLE_ADDRESS . "` 
			SET street = '%s',
			zip = '%s',
			city = '%s',
			distance = '%s'  
			WHERE CustomerID = '%s'
			AND AddressID = '%s';",
			mysql_real_escape_string($this->getStreet()),
			mysql_real_escape_string($this->getZip()),
			mysql_real_escape_string($this->getCity()),
			mysql_real_escape_string($this->getDistance()),
			$this->getCustomerID(),
			$addressID);

		//start a transaction so we can roll back in case something goes wrong
		$this->doQuery("START TRANSACTION");
		
		//execute the two update queries
		$result_update_customer = $this->doQuery($sql_update_customer);
		$result_update_address = $this->doQuery($sql_update_address);

		//if everything went well, commit, else perform a rollback
		if ($result_update_customer && $result_update_address) {
		    $this->doQuery("COMMIT");
		    return TRUE;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}
	}


	/*
	speichert die neue E-Mail Adresse in der Datenbank
	@return true, wenn die E-Mail Adresse erfolgreich gespeichert werden konnte
			false, wenn sie nicht erfolgreich gespeichert werden konnte
	*/
	public function saveEMail() {		
		//query for updating the customer's email address
		$sql_update_customer = "UPDATE `" . DB . "`.`" . TABLE_CUSTOMER . "` 
			SET Email = '" . $this->getEmailAddress() . "'
			WHERE CustomerID = '" . $this->getCustomerID() . "';";

		//start a transaction so we can roll back in case something goes wrong
		$this->doQuery("START TRANSACTION");
		
		//execute the two update queries
		$result_update_customer = $this->doQuery($sql_update_customer);

		//if everything went well, commit, else perform a rollback
		if ($result_update_customer) {
		    $this->doQuery("COMMIT");
		    return TRUE;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}
	}

	/*
	speichert das neue Passwort in der Datenbank
	@return true, wenn das Passwort erfolgreich gespeichert werden konnte
			false, wenn es nicht erfolgreich gespeichert werden konnte
	*/
	public function savePassword() {		
		//query for updating the customer's email address
		$sql_update_customer = "UPDATE `" . DB . "`.`" . TABLE_CUSTOMER . "` 
			SET Password = '" . $this->getPassword() . "'
			WHERE CustomerID = '" . $this->getCustomerID() . "';";

		//start a transaction so we can roll back in case something goes wrong
		$this->doQuery("START TRANSACTION");
		
		//execute the two update queries
		$result_update_customer = $this->doQuery($sql_update_customer);

		//if everything went well, commit, else perform a rollback
		if ($result_update_customer) {
		    $this->doQuery("COMMIT");
		    return TRUE;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}
	}

	/*
	speichert die neue Adresse eines Kunden in die Datenbank
	@return true, wenn die Addresse erfolgreich gespeichert werden konnte
			false, wenn die Addresse nicht erfolgreich gespeichert werden konnte
	*/
	public function insertNewAddress() {

		//starte transaktion um rollback durchführen zu können, falls was schief geht
		$this->doQuery("START TRANSACTION");

		//setze customer addresse als ganzen string zusammen 
		$customeraddress_combined = sprintf("%s %s, %s",
			$this->getZip(),
			$this->getCity(),
			$this->getStreet());

		//bereite url für googlemaps api call vor
		$googlemaps_url = sprintf("http://maps.googleapis.com/maps/api/distancematrix/xml?origins=%s+DE&destinations=%s+DE&mode=driving&language=de-DE&sensor=false",
			$customeraddress_combined,
			MAIN_ADDRESS);

		//führe googlemaps api call durch
		if($xml=simplexml_load_file($googlemaps_url)){
			if($xml->status=='OK') {
				$distance = preg_replace('/\skm/', '', $xml->row->element->distance->text);
				$distance = preg_replace('/,/', '.', $distance);
				$distance = (float)$distance;
			} else
				return false;
		}

		//bereite statement für einfügen in tabelle Address vor
		$sql_insert_address = sprintf("INSERT INTO `" . TABLE_ADDRESS . "`
				(`CustomerID`, `Street`, `ZIP`, `City`, `Distance`)
				VALUES ('%s', '%s', '%s', '%s', '%s')",
				mysql_real_escape_string($this->getCustomerID()),
				mysql_real_escape_string($this->getStreet()),
				mysql_real_escape_string($this->getZip()),
				mysql_real_escape_string($this->getCity()),
				mysql_real_escape_string($distance));

		//füge in tabelle address ein
		$result_insert_address = $this->doQuery($sql_insert_address);

		//wenn alles glatt lief, commit, ansonsten rollback
		if ($result_insert_address) {
		    $this->doQuery("COMMIT");
		    return $this->customerid;
		} else {        
		    $this->doQuery("ROLLBACK");
		    return FALSE;
		}
	}

	/*
	Setzt die aktuellen Adressdaten im Customer-Objekt
	@return boolean, FALSE wenn es fehlschlug
	@return boolean, TRUE wenn es erfolgreich war
	*/
	public function setAddressData($address_ID) {
		if ( empty($address_ID) ) return FALSE;

		$db = new DB();

		$sql = sprintf("SELECT `Street`, `Zip`, `City` FROM `" . DB . "`.`" . TABLE_ADDRESS . "` 
						JOIN `" . DB . "`.`" . TABLE_CUSTOMER . "`USING(CustomerID)
						WHERE `CustomerID` = '" . $this->getCustomerID() . "' AND `AddressID` = '%s' LIMIT 1;", 
						mysql_real_escape_string($address_ID));

		$result = $db->doQuery($sql);
		
		if ( $result->num_rows == 0 ) {
			return FALSE;
		} else {
			$obj = $result->fetch_object();

			$this->setStreet($obj->Street);
			$this->setZip($obj->Zip);		
			$this->setCity($obj->City);	
			$this->setAddressID = $address_ID;

			return TRUE;
		}
	}

	/*
	Berechnet die Distanz von der Pizzeria zum Kunden mittels der GoogleMapsAPI
	*/
	private function calculateDistance() {
		//setze customer addresse als ganzen string zusammen 
		$customeraddress_combined = sprintf("%s %s, %s",
			$this->getZip(),
			$this->getCity(),
			$this->getStreet());

		//bereite url für googlemaps api call vor
		$googlemaps_url = sprintf(GOOGLEMAPS_API_URL,
			$customeraddress_combined,
			MAIN_ADDRESS);

		//führe googlemaps api call durch
		if($xml=simplexml_load_file($googlemaps_url)){
			if( $xml->row->element->status == 'OK' ) {
				$distance = preg_replace('/\skm/', '', $xml->row->element->distance->value);
				$distance = preg_replace('/,/', '.', $distance);
				$distance = (float)$distance /1000;
			} else
				return FALSE;
		}

		$this->setDistance($distance);
		return TRUE;
	}

	/*
	setzt die Instanz Variablen
	*/
	private function setInstanceVariables() {
		if ( is_null($this->customer) ) return FALSE;
		
		$this->setCustomerID($this->customer->CustomerID);
		$this->setEmailAddress($this->customer->Email);
		$this->setFirstname($this->customer->Firstname);
		$this->setLastname($this->customer->Lastname);
		$this->setStreet($this->customer->Street);
		$this->setCity($this->customer->City);
		$this->setZip($this->customer->ZIP);
		$this->setAddressID($this->customer->AddressID);
	
		return TRUE;
	}

	#startregion getters

		/*
		gibt die CustomerID des jeweiligen Kunden zurück
		@return String
		*/
		public function getCustomerID() {
			return $this->customerid;
		}

		/*
		gibt den Vornamen des jeweiligen Kunden zurück
		@return String
		*/
		public function getFirstname() {
			return $this->firstname;
		}

		/*
		gibt den Nachnamen des jeweiligen Kunden zurück
		@return String
		*/
		public function getLastname() {
			return $this->lastname;
		}

		/*
		gibt die Email Addresse des jeweiligen Kunden zurück
		@return String
		*/
		public function getEmailAddress() {
			return $this->emailaddress;
		}

		/*
		gibt das Passwort des jeweiligen Kunden zurück
		@return String
		*/
		public function getPassword() {
			return $this->password;
		}

		/*
		gibt die Straße des jeweiligen Kunden zurück
		@return String
		*/
		public function getStreet() {
			return $this->street;
		}

		/*
		gibt die Stadt des jeweiligen Kunden zurück
		@return String
		*/
		public function getCity() {
			return $this->city;
		}

		/*
		gibt die Postleitzahl des jeweiligen Kunden zurück
		@return String
		*/
		public function getZip() {
			return $this->zip;
		}

		/*
		gibt die Distanz des jeweiligen Kunden zurück
		@return float
		*/
		public function getDistance() {
			return $this->distance;
		}

		/*
		gibt die jeweilige Adress ID des Kunen zurück
		@return int
		*/
		public function getAddressID() {
			return $this->addressID;
		}
	
	#endregion getters

	#startregion setters

		/*
		setzt die CustomerID des jeweiligen Kunden
		*/
		public function setCustomerID($customerid) {
			if(empty($customerid) || is_null($customerid))
				return;

			$this->customerid = $customerid;
		}

		/*
		setzt den Vornamen des jeweiligen Kunden
		*/
		public function setFirstname($firstname) {
			if(empty($firstname) || is_null($firstname))
				return;

			$this->firstname = $firstname;
		}

		/*
		setzt den Nachnamen des jeweiligen Kunden
		*/
		public function setLastname($lastname) {
			if(empty($lastname) || is_null($lastname))
				return;

			$this->lastname = $lastname;
		}

		/*
		setzt die Email Adresse des jeweiligen Kunden
		*/
		public function setEmailAddress($emailaddress) {
			if(empty($emailaddress) || is_null($emailaddress))
				return;

			$this->emailaddress = $emailaddress;
		}

		/*
		setzt das Passworts des Kunden als md5 Hash
		*/
		public function setPassword($password) {
			if(empty($password) || is_null($password))
				return;

			$this->password = md5($password);
		}

		/*
		setzt die Straße des jeweiligen Kunden
		*/
		public function setStreet($street) {
			if(empty($street) || is_null($street))
				return;

			$this->street = $street;
		}

		/*
		setzt die Stadt des jeweiligen Kunden
		*/
		public function setCity($city) {
			if(empty($city) || is_null($city))
				return;

			$this->city = $city;
		}

		/*
		setzt die Postleitzahl des jeweiligen Kunden
		*/
		public function setZip($zip) {
			if(empty($zip) || is_null($zip))
				return;

			$this->zip = $zip;
		}

		/*
		setzt die Distanz des jeweiligen Kunden
		private, weil nur aus calculateDistance() gesetzt werden soll.
		*/
		private function setDistance($distance) {
			if(empty($distance) || is_null($distance))
				return;

			$this->distance = $distance;
		}


		/*
		setzt die jeweilige Adress ID des Kunden
		*/
		public function setAddressID($addressID) {
			if(empty($addressID) || is_null($addressID))
				return;

			$this->addressID = $addressID;
		}

	#endregion setters
}

//end of Product.php