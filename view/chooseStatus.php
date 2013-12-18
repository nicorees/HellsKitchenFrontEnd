<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script> 
$(document).ready(function(){
  $("#flip").click(function(){
    $("#panel").slideToggle("slow");
  });
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

<?php
	$customer = new Customer($_SESSION["customerID"]);

	echo 'Triff deine Auswahl! <br/><br/>';

	echo '<form name="dropdown" action="#" method="post">';	
	echo '<input id="flip" type="checkbox" name="pickup" checked /> Selbstabholung? <br/><br/>';

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

	echo '<a href=".?p=newAddress">neue Adresse anlegen?</a><br/><br/>';

	echo '</div> <br/>';

	echo '<input type="submit" value="wählen" name="addressID" />';
	echo '</form><br/>';


	// wenn 'wählen' gedrückt wird 
	if(isset($_POST['addressID'])) {	
		// setzen der Session Variablen für Adresse und Selbstabholung
		if ( isset($_POST['pickup']) && $_POST['pickup'] == 'on' ) {
			$_SESSION['pickup'] = true;
			$_SESSION['addressID'] = NULL;
		} else {
			$_SESSION['pickup'] = false;
			$_SESSION['addressID'] = $_POST['dropdown'];
		}  
	}

?>
