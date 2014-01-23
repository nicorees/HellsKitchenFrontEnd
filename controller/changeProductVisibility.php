<?php

/**
 * Dieser Controller ändert die Sichtbarkeit eines Produkts
 * @author Nicholas Rees
 */

if ( isset($_GET['pid']) && Product::changeProductVisibility($_GET['pid']) )
	header("Location: " . URL_BASE . "?p=displayPrivateProducts");
else
	//SET ERROR MESSAGE
	header("Location: " . URL_BASE);

?>