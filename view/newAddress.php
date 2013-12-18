<style type="text/css">
	label.error { 
	    color: #fff; 
	    background-color: red;
		margin-left: 50 px;
	    -moz-border-radius: 4px;
	    -webkit-border-radius: 4px;
	}	
	input{float:left;}
	#submit { margin-left: 5em; }	

</style>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

<!-- Eigene Implementierung zum Überprüfen vom Formular  -->
<script type="text/javascript">
	
	$(document).ready(function(){	
		$("#Formular").validate({
		// Regeln
			rules: {
				// Straße = Pflichtfeld (= required)
				txt_strasse: "required",
				// Nachname: Pflichtfeld

				//PLZ = Pflichtfeld
				txt_plz:{
					required: true,
					minlength: 5,
					maxlength: 5,
					digits: true
				},

				// Stadt = Pflichtfeld
				txt_stadt: {
					required: true,
					city: true			
				}
			},
			// Nachrichten, die bei jeweiligen Fall ausgegeben werden sollen.
			messages: {
				txt_strasse: "Bitte Straße eingeben!",

				txt_plz: {
					digits: "Bitte nur numerische Werte eingeben",
					required: "Bitte PLZ eingeben!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!")

				},

				txt_stadt: {
					required: "Bitte Wohnort eingeben!",
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
				alert("ok...");
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

<?php	

	if(isset($_POST['txt_strasse'])) {

		if(!empty($_POST['txt_strasse']) && !empty($_POST['txt_plz']) && !empty($_POST['txt_stadt'])) {
		
			$customer = new Customer($_SESSION["customerID"]);

			//setze alle Instanzvariablen für eine Adresse
			$customer->setStreet($_POST['txt_strasse']);
			$customer->setZip($_POST['txt_plz']);
			$customer->setCity($_POST['txt_stadt']);

			// neue Addresse hinzufügen
			if($customer->insertNewAddress()) {
				echo '<script>alert("Deine Adresse wurde hinzugefügt!")</script>';
			} else {
				echo '<script>alert("Es trat ein Fehler auf, bitte versuche es erneut!")</script>';
			}	
		}
		else {
				echo 'Alle Felder müssen ausgefüllt sein.';
		}
	}
?>

<p>Hier kannst du deine neue Adresse angeben:</p> <br/>

<form id="Formular" action="<?php URL_BASE . "/?p=newAddress" ?>" method="post">	
			<label for="txt_strasse">Straße: </label> 
			<input class="textfield" type="text" name="txt_strasse" value="" /> <br />

			<label for="txt_plz">PLZ: </label>
			<input class="textfield" type="text" name="txt_plz" value="" /> <br />

			<label for="txt_stadt">Stadt: </label>
			<input class="textfield" type="text" name="txt_stadt" value="" /> <br /> <br />
			
			<input class="button" type="submit" name="btn_save" value="speichern" />			
</form> 

<a href=".?p=editData" class="button" style="float: right;">zurück</a>