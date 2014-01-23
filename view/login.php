<?php

	if(isset($_POST['btn_login'])) {

		$email = $_POST['txt_email'];
		$password = $_POST['txt_password'];

		$customerid = Customer::authenticate($email,$password);

		if($customerid == FALSE) {
		?>
			<script type="text/javascript">
			    $.Dialog({
			    	flat: true,
			    	draggable: true,
			        shadow: true,
			        overlay: true,
			        icon: '<span class="icon-blocked fg-crimson"></span>',
			        title: '<span class="fg-crimson">Login fehlgeschlagen</span>',
			        padding: 10,
			        width: 500,
			        content: '<span class="readable-text">Ihr Versuch sich einzuloggen schlug fehl, <br/>bitte überprüfen Sie Ihre Anmeldedaten.</span><br/><br/>' +
			        	'<span class="readable-text">Falls Sie noch kein Benutzerkonto haben, <br/>können Sie sich jederzeit registrieren</span>'
			    });
			</script>
		<?php
		} else {
			$_SESSION['customerID'] = $customerid;

			$db = new DB();

			$sql = "SELECT firstname from customer where customerid='$customerid'";

			$user = $db->doQuery($sql)->fetch_object();

			$first = $user->firstname;

			$_SESSION['customerFirst'] = $first;

			header("Location: " . URL_BASE . "?p=welcome");
		}
	}
?>

<h3>Login:</h3>
<form action="" method="post">
	<p>
	Email: <br />
	<input class="textfield" type="text" name="txt_email" value="" />	
	</p>
	<p>
	Passwort: <br />
	<input class="textfield" type="password" name="txt_password" value="" />		
	</p>
	<input class="button" type="submit" name="btn_login"
	value="Login" />
</form>
<p> Noch kein Benutzer? - <a href=".?p=register">Registriere dich jetzt!</a></p>