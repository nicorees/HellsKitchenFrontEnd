<?php

	/**
	 * Diese View erlaubt es dem User sein Passwort zu ändern
	 * @author Andreas Nenning, Christian Vogg
	 */

	$customer = new Customer($_SESSION["customerID"]);

	if(isset($_POST['btn_edit']) && !empty($_POST['btn_edit'])) {
		$pw0 = md5($_POST['txt_pw0']);
		$pw1 = md5($_POST['txt_pw1']);
		$pw2 = md5($_POST['txt_pw2']);


		// alle Felder müssen gefüllt sein
		if(!empty($_POST['txt_pw0']) && !empty($_POST['txt_pw1'])
		   && !empty($_POST['txt_pw2'])) {

			// altes Passwort abfragen
			$db = new DB();
			$sql = "SELECT Password FROM `" . DB . "`.`" . TABLE_CUSTOMER . "` 
					WHERE CustomerID = '" . $_SESSION["customerID"] . "' LIMIT 1;";
			$result = $db->doQuery($sql);
			$pwOld = $result->fetch_object()->Password;


			// das alte Passwort muss korrekt sein
			if($pwOld == $pw0) {
				$customer->setPassword($_POST['txt_pw1']);

				if($customer->savePassword()) {
					echo 'Dein Passwort wurde wie gewünscht aktualisiert.<br/><br/>';

				} else {
					echo 'Es trat ein Fehler auf, bitte versuche es erneut.<br/><br/>';
				}
			}
			else {
				echo 'Dein altes Passwort ist nicht korrekt!<br/><br/>';
			}	
		}
		else {
			echo 'Bitte alle Felder ausfüllen.<br/><br/>';
		}
	}	

?>

<h3>Hier kannst du dein Passwort ändern:</h3>
<br/>
	
<form id="Formular" action="?p=editPassword" method="post">
	<label for="txt_pw0">Altes Passwort: </label>
	<input class="textfield" type="password" name="txt_pw0" value="" /> <br/>

	<label for="txt_pw1">Neues Passwort: </label>
	<input id="txt_pw1" class="textfield" type="password" name="txt_pw1" value="" /> <br/>

	<label for="txt_pw2">Neues Passwort wiederholen: </label>
	<input class="textfield" type="password" name="txt_pw2" value="" /> <br/> <br/>

	<input class="button" type="submit" name="btn_edit" value="Speichern" />
	&nbsp;<a href="javascript:history.back()" class="button" >Zurück</a>
</form>

<style type="text/css">
	label.error { 
	    color: #fff; 
	    background-color: red;
		margin-left: 50 px;
	    -moz-border-radius: 4px;
	    -webkit-border-radius: 4px;
	}	
	input{
	float:left;
	}
	#submit { margin-left: 5em; }	
</style>

<!-- Eigene Implementierung zum Überprüfen vom Formular  -->
<script type="text/javascript">
	
	$(document).ready(function(){	
		$("#Formular").validate({
		// Regeln
			rules: {
				// Passwörter = Pflichtfelder
				txt_pw1: {
					required: true,
					minlength: 4,
					maxlength: 10			
				},

				txt_pw2: {
					required: true,
					minlength: 4,
					maxlength: 10,
					equalTo: "#txt_pw1"
				}
			},
			// Nachrichten, die bei jeweiligen Fall ausgegeben werden sollen.
			messages: {
				txt_pw1: {
					required: "Bitte Passwort eingeben!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!")
				},

				txt_pw2: {
					required: "Bitte gleiches Passwort wie oben eingeben!",
					minlength: jQuery.format("mindestens {0} Zeichen eingeben!"),
					maxlength: jQuery.format("maximal {0} Zeichen eingeben!"),
					equalTo: "Die Passwörter müssen übereinstimmen!"
				}

			},
			// wenn Button "submit" gedrückt wird, geb folgendes aus
			submitHandler: function(form) {
				form.submit();
			}
		});
	});

</script>