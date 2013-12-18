<?php	
	if(isset($_POST['btn_register'])){
		
		//lege neuen Customer an
		$customer = new Customer();

		//setze alle Instanzvariablen
		$customer->setFirstname($_POST['txt_vorname']);
		$customer->setLastname($_POST['txt_nachname']);
		$customer->setEmailaddress($_POST['txt_email']);
		$customer->setPassword($_POST['txt_password']);
		$customer->setStreet($_POST['txt_strasse']);
		$customer->setZip($_POST['txt_plz']);
		$customer->setCity($_POST['txt_stadt']);

		//lege neuen Customer in der DB an, gibt ID zurück wenn erfolgreich, FALSE wenn nicht
		$customerid = $customer->register();

		//prüfe ob registration erfolgreich, wenn ja leite auf welcome weiter, wenn nicht auf login
		if(is_numeric($customerid)) {
			$_SESSION['customerID'] = $customerid;
			header("Location: " . URL_BASE . "?p=welcome");
		}
		else
			header("Location: " . URL_BASE);
	}
?>

<!-- Eigene Implementierung zum Überprüfen vom Formular  -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript">
	
	$(document).ready(function(){	
		$("#Formular").validate({
		// Regeln
			rules: {
			// Vorname: Pflichtfeld (= required)
				txt_vorname: "required",
				// Nachname: Pflichtfeld
				txt_nachname: "required",
				// Passwort: Pflichtfeld, min. 5, max 10 Zeichen
				txt_password: {
					required: true,
					minlength: 5,
					maxlength: 10			
				},
				// Email: Pflichtfeld, adäquates Emailformat
				txt_email: {
					required: true,
					email: true				
				},
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
				txt_vorname: "Bitte Vornamen angeben!",
				txt_nachname: "Bitte Nachnamen angeben!",
				txt_password: {
					required: "Bitte Passwort angeben!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!")
				},
				txt_plz: {
					digits: "Bitte nur numerische Werte eingeben",
					required: "Bitte geben Sie Ihre PLZ ein!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!")

				},
				txt_email: {
					required: "Bitte E-Mail-Adresse eingeben!",
					email: "E-Mail im Format name@domain.de eingeben!"	
				},
				txt_stadt: {
					required: "Bitte geben Sie Ihren Wohnort ein!",
					city: "Bitte keine Zahlen eingeben!"
				}
			},
			// wenn Button "submit" gedrückt wird, prüfe Adresse auf plausibilität
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

<form id="Formular" action="<?php URL_BASE . "/?p=register" ?>" method="post">		

			<label for="txt_vorname">Vorname: </label><br /> <input class="textfield" type="text"
				name="txt_vorname" value="" /> <br />
		
		
			<label for="txt_nachname">Nachname: </label><br /> <input class="textfield" type="text"
				name="txt_nachname" value="" /> <br />
		
		
			<label for="txt_email">Email: </label><br /> <input class="textfield" type="text" name="txt_email"
				value="" /> <br />
		 
		
			<label for="txt_strasse">Straße: </label><br /> <input class="textfield" type="text" name="txt_strasse"
				value="" /> <br />
		 
		
			<label for="txt_plz">PLZ: </label><br /> <input class="textfield" type="text" name="txt_plz"
				value="" /> <br />
		
		
			<label for="txt_stadt">Stadt: </label> <br /> <input class="textfield" type="text" name="txt_stadt"
				value="" /> <br />
				
			<label for="txt_password">Passwort: </label> <br /> <input class="textfield" type="password" name="txt_password"
				value="" /> <br />		

			<input class="button" type="submit" name="btn_register" value="register" />		
</form>