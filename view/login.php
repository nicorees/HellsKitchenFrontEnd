<?php

	if(isset($_POST['btn_login'])) {

		$email = $_POST['txt_email'];
		$password = $_POST['txt_password'];

		$customerid = Customer::authenticate($email,$password);

		if($customerid === FALSE) {
			header("Location: .");
		} else {
			$_SESSION['customerID'] = $customerid;

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