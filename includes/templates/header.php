<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html class="no-js">
	<head>
		<title><?php echo (isset($strPageTitle) ? $strPageTitle . ' | ' : '' ) . SITE_TITLE; ?></title>
		
		<!-- fun w/ IE -->
		<!--[if lt IE 8]><meta http-equiv="Content-Type" content="text/html; UTF-8"><![endif]-->
		<!--[if gt IE 7]><!--><meta charset="UTF-8" /><!--<![endif]-->
		
		<meta name="description" content="BlueCalc is a tool to calculate possible grid configurations for Blueprint CSS" />
		<meta name="keywords" content="grid, css, blueprint, layout, blueprinter" />
		<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon" />
		
		<?php 
		/**
		 * It's a multi-purpose header TEMPLATE, thus we'd like multiple inclusions ..
		 */
		$strSelfFileName = basename($_SERVER['PHP_SELF']);
		
		switch( $strSelfFileName ) {
			
			case 'blueprint.php': ?>
		<script type="text/javascript" src="sh/shCore.js"></script>
		<script type="text/javascript" src="sh/shBrushCss.js"></script>
		<link type="text/css" rel="stylesheet" href="sh/styles/shCore.css"/>
		<link type="text/css" rel="stylesheet" href="sh/styles/shThemeDefault.css"/>
		<script type="text/javascript">
			SyntaxHighlighter.config.clipboardSwf = 'sh/clipboard.swf';
			SyntaxHighlighter.all();
		</script>	
			
			<?php
				break;

			default: ?>

		<link rel="stylesheet" href="assets/css/main.css" type="text/css" />
		
		<script src="assets/js/head.min.js"></script>
		<script>
			head.js('http://code.jquery.com/jquery.min.js', 'assets/js/main2.js');
		</script>
		
		<!-- CSS for displaying the grid calculations -->
		<style type="text/css">
			.grid { width: <?php echo $target; ?>px; }
		</style>
		
		<?php break;
		} ?>
	</head>
	<body class="<?php echo implode(' ', $arrBodyClass); ?>">
