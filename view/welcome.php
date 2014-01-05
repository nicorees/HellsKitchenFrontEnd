<?php

$customerID = $_SESSION['customerID'];

$db = new DB();

$sql = "SELECT firstname, lastname from customer where customerid='$customerID'";

$user = $db->doQuery($sql)->fetch_object();

$first = $user->firstname;
$last = $user->lastname;

echo "<h2>Hallo $first! Was möchtest du tun?</h2>";
echo "<br/>";

?>
<div class="span8">
	<a href='?p=configurePizza'>
		<div class="tile double bg-crimson">
		    <div class="tile-content icon">
		        <i class="icon-fire"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Pizza Erstellen</span>
		    </div>
		</div>
	</a>
	<a href='?p=displayPrivateProducts'>
		<div class="tile bg-amber">
		    <div class="tile-content icon">
		        <i class="icon-pie"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Meine Pizzen</span>
		    </div>
		</div>
	</a>
	<a href='?p=displayAllProducts'>
		<div class="tile bg-orange">
		    <div class="tile-content icon">
		        <i class="icon-database"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Alle Pizzen</span>
		    </div>
		</div>
	</a>
	<a href='?p=cart'>
	<div class="tile bg-crimson">
	    <div class="tile-content icon">
	        <i class="icon-cart-2"></i>
	    </div>
	    <div class="tile-status">
	        <span class="name">Warenkorb</span>
	    </div>
	</div>
	</a>
	<a href='?p=displayAllOrders'>
		<div class="tile bg-orange">
		    <div class="tile-content icon">
		        <i class="icon-layers"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Meine Bestellungen</span>
		    </div>
		</div>
	</a>
	<a href='?p=editData'>
		<div class="tile double bg-dark">
		    <div class="tile-content icon">
		        <i class="icon-user"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Daten Bearbeiten</span>
		    </div>
		</div>
	</a>
	<a href='?p=editEMail'>
		<div class="tile double bg-amber">
		    <div class="tile-content icon">
		        <i class="icon-mail"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Email Adresse ändern</span>
		    </div>
		</div>
	</a>
	<a href='?p=editPassword'>
		<div class="tile bg-crimson">
		    <div class="tile-content icon">
		        <i class="icon-key"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Passwort ändern</span>
		    </div>
		</div>
	</a>
	<a href='?p=logout'>
		<div class="tile bg-dark">
		    <div class="tile-content icon">
		        <i class="icon-reply"></i>
		    </div>
		    <div class="tile-status">
		        <span class="name">Abmelden</span>
		    </div>
		</div>
	</a>
</div>