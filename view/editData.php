<?php
	$customer = new Customer($_SESSION["customerID"]);

	if(isset($_POST['txt_vorname'])) {

		// alle Felder müssen gefüllt sein
		if(!empty($_POST['txt_vorname']) && !empty($_POST['txt_nachname'])
			&& !empty($_POST['txt_strasse']) && !empty($_POST['txt_plz'])
			&& !empty($_POST['txt_stadt'])) {
	
			$customer->setFirstname($_POST['txt_vorname']);
			$customer->setLastname($_POST['txt_nachname']);
			$customer->setStreet($_POST['txt_strasse']);
			$customer->setZip($_POST['txt_plz']);
			$customer->setCity($_POST['txt_stadt']);
			
			$customeraddress_combined = sprintf("%s %s, %s",
			$_POST['txt_plz'],
			$_POST['txt_stadt'],
			$_POST['txt_strasse']);
			$googlemaps_url = sprintf(GOOGLEMAPS_API_URL,
			$customeraddress_combined,
			MAIN_ADDRESS);
			if($xml=simplexml_load_file($googlemaps_url)){
				if( $xml->row->element->status == 'OK'  ) {
				
					if($customer->save($_SESSION['addressID'])) {
						echo 'Deine Daten wurden wie gewünscht aktualisiert.<br/><br/>';
					} else {
						echo 'Es trat ein Fehler auf, bitte versuche es erneut.<br/><br/>';
					}
				}
				else{
				echo '<p style="color: red">Deine neunen Adressdaten scheinen nicht valide! </br></p>'; 
				}
			}
		}	
		else {
			echo 'Du musst alle Felder ausfüllen.<br/><br/>';
		}
	}	

	echo '<h3>Hier hast du die Möglichkeit, deine allgemeinen Daten zu bearbeiten:</h3>';
	echo '<br/>';
	echo '<p>';
		echo '<a href="?p=editEMail" style="margin-right: 10px;">Email Adresse ändern</a>';
		echo '<a href="?p=editPassword">Passwort ändern</a>';
	echo '</p>';
	echo '<br/>';

	// erstellt Dropdown Menü zur Auswahl der Adresse
	$db = new DB();

	$sql = "SELECT AddressID, Street, Zip, City FROM `" . DB . "`.`" . TABLE_ADDRESS . "` 
	JOIN `" . DB . "`.`" . TABLE_CUSTOMER . "`USING(CustomerID)
	WHERE CustomerID = '" . $_SESSION["customerID"] . "';";
		
	$result = $db->doQuery($sql);

	echo '<form name="Dropdown" action="#" method="post">';	
	echo '<select name="dropdown" onchange="this.form.submit()" class="addressselect">';
	echo '<option> --- Wähle eine Adresse --- 
	</option>';
	while($obj = $result->fetch_object()) {
		echo '<option value="'. $obj->AddressID . '">';
        printf ("%s, %s, %s", $obj->Street, $obj->Zip, $obj->City);
       	echo '</option>';
	}

	echo '</select>';
	echo '<a href=".?p=newAddress" class="button">Neue Adresse anlegen</a>';
	echo '</form>';


	// aktualisiert die Session Variable für addressID
	if(isset($_POST['dropdown'])) {	
		$_SESSION['addressID'] = $_POST['dropdown'];	

		if( !$customer->setAddressData($_SESSION['addressID']) )
			echo 'Es ist ein Fehler aufgetreten!';
	}
	else {
		$_SESSION['addressID'] = $customer->getAddressID();
	}

?>
		
<form id="Formular" action="#" method="post">	
	<label for="txt_vorname">Vorname: </label> 
	<input class="textfield" type="text" name="txt_vorname" value="<?php echo $customer->getFirstname(); ?>" /> <br/>
	
	<label for="txt_nachname">Nachname: </label>
	<input class="textfield" type="text" name="txt_nachname" value="<?php echo $customer->getLastname(); ?>" /> <br/>

	<br/>

	<label for="txt_strasse">Straße: </label>
	<input class="textfield" type="text" name="txt_strasse" value="<?php echo $customer->getStreet(); ?>" /> <br/>				
	
	<label for="txt_plz">PLZ: </label>
	<input class="textfield" type="text" name="txt_plz" value="<?php echo $customer->getZip(); ?>" /> <br/>
	
	<label for="txt_stadt">Stadt: </label>
	<input class="textfield" type="text" name="txt_stadt" value="<?php echo $customer->getCity(); ?>" /> <br/> <br />

	<input class="button" type="submit" name="btn_edit" value="Speichern" />
</form>

<style type="text/css">
	label.error { 
	    color: #fff; 
	    background-color: red;
		margin-left: 50 px;
	    -moz-border-radius: 4px;
	    -webkit-border-radius: 4px;
	}	
	input {
		float:left;
	}
</style>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<!-- Eigene Implementierung zum Überprüfen vom Formular  -->
<script type="text/javascript">
	
	$(document).ready(function(){	
		$("#Formular").validate({
		// Regeln
			rules: {
			// Vorname: Pflichtfeld (= required)
				txt_vorname: "required",
				// Nachname: Pflichtfeld
				txt_nachname: "required",

				// Straße = Pfichtfeld
				txt_strasse: "required",

				//PLZ
				txt_plz:{
					required: true,
					minlength: 5,
					maxlength: 5,
					digits: true
				},

				txt_stadt: {
					required: true,
					city: true			
				}
			},
			// Nachrichten, die bei jeweiligen Fall ausgegeben werden sollen.
			messages: {
				txt_vorname: "Bitte Vornamen eingeben!",
				txt_nachname: "Bitte Nachnamen eingeben!",

				txt_strasse: "Bitte Straße eingeben!",

				txt_plz: {
					digits: "Bitte nur numerische Werte eingeben!",
					required: "Bitte PLZ eingeben!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!")

				},

				txt_stadt: {
					required: "Bitte Wohnort eingeben!",
					city: "Bitte keine Zahlen eingeben!"
				}
			},
			// prüfe Adresse auch auf Plausibilität
			submitHandler: function(form) {
				var geocoder;
				geocoder = new google.maps.Geocoder();
				var address = document.getElementsByName('txt_plz')[0].value
					+" "+ document.getElementsByName('txt_stadt')[0].value +
					", " + document.getElementsByName('txt_strasse')[0].value;
				geocoder.geocode( { 'address': address}, function(results, status) {
					// Ort gefunden, sende Daten ab
					if (status == google.maps.GeocoderStatus.OK) {
						form.submit();
					} else {
					// Ort nicht gefunden, Fehlermeldung
						alert("Bitte Prüfen Sie ihre Daten, Ort nicht gefunden!");
					}
				});
			}
		});
	});

</script>