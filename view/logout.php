<?php

	/**
	 * Diese View erlaubt es dem User sich auszuloggen
	 * @author Andreas Nenning, Nicholas Rees <3
	 */

     session_destroy();
     header("Location: " . URL_BASE);