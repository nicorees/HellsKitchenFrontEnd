<!--TEMPLATE FÜR DEN HEADER JEDER SEITE -->
<!--@AUTHOR Andreas Nenning, Christian Vogg, Nicholas Rees, Steffen Schenk -->

<!DOCTYPE html>
<html lang="en">
	<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
	
	<!-- CSS -->
	<?php echo CSS::get("base.css"); ?>
	<?php echo CSS::get("metro-bootstrap.css"); ?>
	<?php echo CSS::get("metro-bootstrap-responsive.css"); ?>
	<!-- End of CSS -->

	<!-- JavaScript libraries -->
	<?php echo JS::get("jquery/jquery.min.js"); ?>
	<?php echo JS::get("jquery/jquery.validate.js"); ?>
	<?php echo JS::get("jquery/jquery.widget.min.js"); ?>
	<!-- End of JavaScript libraries -->

	<!-- Metro UI JavaScript plugins -->
	<?php echo JS::get("metro/metro-drag-tile.js"); ?>
	<?php echo JS::get("metro/metro-loader.js"); ?>
	<?php echo JS::get("metro/metro-plugin-template.js"); ?>
	<?php echo JS::get("metro/metro-stepper.js"); ?> 	
	<!-- End of Metro UI JavaScript plugins -->

	<link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

	<title>HELLS KITCHEN - BEST PIZZAS IN TOWN</title>
	
	</head>

	<body class="metro">

		<header class="bg-dark">
			<nav class="navigation-bar fixed-top dark">
				<div class="navigation-bar-content container">
						<?php
							if(isset($_SESSION['customerID']))
								Menubuilder::buildAuthMenu();
							else
								Menubuilder::buildUnAuthMenu();
						?>
				</div>
			</nav>
		</header>
		<div class="wrapper">
			<div class="grid">
				<div class="row">
					<div class="banner">
						<img width="560" height="100" src="assets/img/banner.png"/>
					</div>
					<div class="span12">