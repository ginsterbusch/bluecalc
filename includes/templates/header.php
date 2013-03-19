<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html class="no-js">
	<head>
		<title><?php echo SITE_TITLE; ?></title>
		
		<!-- fun w/ IE -->
		<!--[if lt IE 8]><meta http-equiv="Content-Type" content="text/html; UTF-8"><![endif]-->
		<!--[if gt IE 7]><!--><meta charset="UTF-8" /><!--<![endif]-->
		
		<meta name="description" content="BlueCalc is a tool to calculate possible grid configurations for Blueprint CSS" />
		<meta name="keywords" content="grid, css, blueprint, layout, blueprinter" />
		<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="assets/css/main.css" type="text/css" />
		
		<script src="assets/js/mootools-1.2.4-core.js"></script>
		<script src="assets/js/main.js"></script>
		
		<!-- CSS for displaying the grid calculations -->
		<style type="text/css">
			.grid { width: <?php echo $target; ?>px; }
		</style>
	</head>
	<body class="<?php echo implode(' ', $arrBodyClass); ?>">
