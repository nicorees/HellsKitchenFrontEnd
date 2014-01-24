<?php

	/**
	 * Diese View repräsentiert den zweiten Schritt des Abschlusses
	 * einer Bestellung
	 * @author Nicholas Rees
	 */

	$cart = Order::getCart($_SESSION['customerID']);
	
	if(empty($cart->getOrderLines())) {
		header("Location: .");
	}

	if(isset($_POST['ordertime']))
		$_SESSION['orderTime'] = $_POST['ordertime'];
	else
		header("Location: ?p=checkout1");
?>

<h1>Bestellung abschließen</h1>
<br/>

<h2>Schritt2: Lieferung oder Selbstabholung</h2>
<br/>

<?php
	echo '<form name="dropdown" action="?p=checkout3" method="post">';	
	echo '<input id="flip" type="checkbox" name="pickup" checked />Selbstabholung<br/><br/>';

	// erstellt Dropdown Menü zur Auswahl der Adresse
	$db = new DB();

	$sql = "SELECT AddressID, Street, Zip, City FROM `" . DB . "`.`" . TABLE_ADDRESS . "` 
	JOIN `" . DB . "`.`" . TABLE_CUSTOMER . "`USING(CustomerID)
	WHERE CustomerID = '" . $_SESSION["customerID"] . "';";
		
	$result = $db->doQuery($sql);

	echo '<div id="panel">';
	echo 'Wähle deine Lieferadresse aus!<br/>';
	echo '<select name="dropdown">';

	while($obj = $result->fetch_object()) {
		echo '<option value="'. $obj->AddressID . '">';
        printf ("%s, %s, %s", $obj->Street, $obj->Zip, $obj->City);
       	echo '</option>';
	}

	echo '</select> <br/><br/>';

	echo '</div> <br/>';

	echo '<input type="submit" id="submit" name="submit" value="Weiter" /> ';
	echo '<a class="button" href="?p=checkout1">Zurück</a>';
	echo '</form><br/>';

?>

<!-- RESOURCES FOR PICKUP AND ADDRESS CHOOSER -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script> 
	$(document).ready(function(){
	  $("#flip").click(function(){
	    $("#panel").slideToggle("slow");
	  });
	});

	$(document).ready(function(){
	    var el = document.getElementById("flip");

	    if (!el.checked)
	    	el.checked = true;
	});
</script>
 
<style type="text/css"> 
#panel,#flip
{
	padding:5px;
	text-align:center;
	background-color:#e5eecc;
	border:solid 1px #c3c3c3;
	border-radius: 4px;
}
#panel
{
	padding:50px;
	display:none;
}
</style>