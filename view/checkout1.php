<?php
	$cart = Order::getCart($_SESSION['customerID']);
	
	if(empty($cart->getOrderLines())) {
		header("Location: .");
	}
?>

<h1>Bestellung abschließen</h1>
<br/>

<h2>Schritt1: Datum und Uhrzeit wählen</h2>
<br/>
<form action="?p=checkout2" method="post">
	<p><input type="text" id="datepicker" name="ordertime" value=""></p>
	<br/>
	<input type="submit" id="submit" name="submit" value="Weiter" />
</form>

<!-- RESOURCES FOR DATETIMEPICKER -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> 
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="assets/js/jquery/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="assets/js/jquery/jquery-ui-sliderAccess.js"></script>
<script>
  $(function() {
    $( "#datepicker" ).datetimepicker();
    document.getElementById('datepicker').value = dateFormat(new Date(), "mm/dd/yyyy HH:MM");
  });
</script>