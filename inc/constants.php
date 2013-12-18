<?php

// database connection settings
define("DB_HOST", "localhost:3306");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB", "wfprj_15");

// database tables
define("TABLE_ADDRESS", "address");
define("TABLE_CUSTOMER", "customer");
define("TABLE_DIST_CLASS", "dist_class");
define("TABLE_INGREDIENT", "ingredient");
define("TABLE_ORDERLINE", "orderline");
define("TABLE_ORDERS", "orders");
define("TABLE_PRODUCT", "product");
define("TABLE_PRODUCT_HAS_INGREDIENT", "product_has_ingredient");
define("TABLE_STATUS", "status");

// address used for distance calculation
define("MAIN_ADDRESS", "89415 Lauingen, Herzog-Georg-Strasse 16");

//URL for googlemaps API call with placeholders for sprintf()
define("GOOGLEMAPS_API_URL", "http://maps.googleapis.com/maps/api/distancematrix/xml?origins=%s+DE&destinations=%s+DE&mode=driving&language=de-DE&sensor=false");

// system constants
define("URL_BASE", "http://localhost:90/hellskitchen/");
define("DOC_ROOT", "C:/Users/Nico/Documents/GitHub/HellsKitchenFrontEnd/");

// end of constants.php