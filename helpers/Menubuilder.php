<?php

/*
 * Helperklasse fürs Bauen des Navigationmenüs 
*/

class Menubuilder {
	
	//mehrfach verschachteltes assoziatives array mit menü-namen und links
	private static $menulinks = array(
		"Home" => "?p=welcome",
		"Pizzen" => array(
			"Pizza Erstellen" => "?p=configurePizza",
			"Meine Pizzen" => "?p=displayPrivateProducts",
			"Alle Pizzen" => "?p=displayAllProducts",
			),
		"Bestellungen" => array(
			"Warenkorb" => "?p=cart",
			"Meine Bestellungen" => "#"
			),
		"Profil" => array(
			"Bearbeiten" => array(
				"Allgemeine Daten bearbeiten" => "?p=editData",
				"Email Addresse ändern" => "?p=editEMail",
				"Passwort ändern" => "?p=editPassword"
				),
			"Abmelden" => "?p=logout"
			),
	);

	public static function getMenuLinks() {
		return self::$menulinks;
	}

	/*
	 * Iteriert über das obige Array und baut so das Menü auf.
	 * Wenn ein $value der $key => $value Assoziation ein Array ist,
	 * baut die Funktion ein weiteres Submenü.
	 */
	public static function buildMenu() {

		foreach (self::$menulinks as $level1 => $level2) {
			
			print("<div class='element'>");

			if(!is_array($level2)) {
				print("<a class='fg-white' href='$level2'>$level1</a>");
			} else {

				print("<a class='dropdown-toggle' href='#'>$level1</a>");
				print("<ul class='dropdown-menu inverse' data-role='dropdown'>");

				foreach ($level2 as $level3 => $level4) {
				
					if(!is_array($level4)) {

						print("<li><a href='$level4'>$level3</a></li>");

					} else {
						
						print("<li>");
						
							print("<a class='dropdown-toggle' href='#'>$level3</a>");
							print("<ul class='dropdown-menu inverse' data-role='dropdown'>");
							
							foreach ($level4 as $level5 => $level6) {
								print("<li><a href='$level6'>$level5</a></li>");							
							}
							
							print("</ul>");
						
						print("</li>");

					}
				}
				
				print("</ul>");
			}

			print("</div>");
		}	
	}	
}
// end of Menubuilder.php