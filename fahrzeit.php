<?php

		// address used for distance calculation
		define("MAIN_ADDRESS", "89415 Lauingen, Herzog-Georg-Strasse 16");

		//URL for googlemaps API call with placeholders for sprintf()		
		define("GOOGLEMAPS_API_URL", "http://maps.googleapis.com/maps/api/distancematrix/xml?origins=%s+DE&destinations=%s+DE&mode=driving&language=de-DE&sensor=false");

		$customeraddress_combined = '89407 Dillingen';
		$googlemaps_url = sprintf(GOOGLEMAPS_API_URL,
			$customeraddress_combined,
			MAIN_ADDRESS);
			
			if($xml=simplexml_load_file($googlemaps_url)){
			if( $xml->row->element->status == 'OK' ) {
				$distance = preg_replace('/\skm/', '', $xml->row->element->duration->value);
				$distance = preg_replace('/,/', '.', $distance);
				$distance = (float)$distance;
			} else
				return FALSE;
				
			
		}
		echo "Fahrzeit".$distance. "sek";
		
