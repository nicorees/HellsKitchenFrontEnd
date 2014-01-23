<?php

	/**
	 * Diese View erlaubt es dem User seine Email Adresse zu ändern
	 * @author Andreas Nenning, Christian Vogg
	 */

	$customer = new Customer($_SESSION["customerID"]);

	if(isset($_POST['btn_edit']) && !empty($_POST['btn_edit'])) {
		
		// es muss eine E-Mail Adresse angegeben sein 
		if(!empty($_POST['txt_email'])) {
	
			$customer->setEmailAddress($_POST['txt_email']);
			
			if($customer->saveEMail()) {
				echo 'Deine E-Mail Adresse wurde wie gewünscht aktualisiert.<br/><br/>';
			} else {
				echo 'Es trat ein Fehler auf, bitte versuche es erneut.<br/><br/>';
			}	
		}	
		else {
			echo 'Es wurde keine E-Mail Adresse eingegeben. Bitte eintragen.<br/><br/>';
		}
	}	

?>

<h3>Hier kannst du deine E-Mail Adresse aktualisieren:</h3>
<br/>
	
<form id="Formular" action="?p=editEMail" method="post">	
	
	<label for="txt_email">E-Mail Adresse: </label> 
	<input class="textfield" type="text" name="txt_email" value="<?php echo $customer->getEmailAddress(); ?>" />
	<br/><br/>
	<input id="submit" class="button" type="submit" name="btn_edit" value="Speichern"/>	
	<a href="javascript:history.back()" class="button">Zurück</a>

</form>

<style type="text/css">
	label.error { 
	    color: #fff; 
	    background-color: red;
		margin-left: 50 px;
	    -moz-border-radius: 4px;
	    -webkit-border-radius: 4px;
	}	
</style>

<!-- Eigene Implementierung zum Überprüfen vom Formular  -->
<script type="text/javascript">
	
	$(document).ready(function(){	
		$("#Formular").validate({
		// Regeln
			rules: {
				// Email = Pflichtfeld
				txt_email: {
					required: true,
					email: true
				}
			},
			// Nachrichten, die bei jeweiligen Fall ausgegeben werden sollen.
			messages: {
				txt_email: {
					required: "Bitte Email Adresse eingeben!",
					email: "Bitte valide Email Adresse eingeben!"
				}
			},
			// wenn Button "submit" gedrückt wird, geb folgendes aus
			submitHandler: function(form) {
				form.submit();
			}
		});
	});

</script>