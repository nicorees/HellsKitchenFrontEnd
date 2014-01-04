<?php
	if(isset($_GET['i']) && isset($_GET['r']) && Product::rateProduct($_GET['i'], $_GET['r']))
		header("Location: " . URL_BASE . "?p=displayAllProducts");
	else {
	// SET ERROR MESSAGE
	header("Location: " . URL_BASE);
	}
?>