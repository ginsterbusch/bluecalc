<?php
/**
 * A small script to calculate Blueprint.css Grids
 *
 * @param int $target		The total width of your grid
 * @param int $col_min_width	Minimal column width
 * @param int $col_max_width	Maximal column width
 * @param int $mar_min_width	Minimal margin width
 * @param int $mar_max_width	Maximal margin width
 */

/**
 * Initializes a requested parameter
 *
 * @param string $param The paramter to read
 * @param int $default The default value
 */
function getParam($param, $default) {
	return (array_key_exists($param, $_REQUEST) && (int)$_REQUEST[$param] > 0) ? (int)$_REQUEST[$param] : $default;
}

// Get contact form
$options = parse_ini_file('config.ini');
if (isset($options['host']) && isset($_REQUEST['name']) && isset($_REQUEST['email'])) {
	require_once('class.phpmailer.php');
	require_once('class.smtp.php');
	$mail = new PHPMailer();
	$mail -> IsSMTP();
	$mail -> Host = $options['host'];
	$mail -> SetFrom($options['fromaddress'], $options['fromname']);
	$mail -> AddAddress($options['toaddress'], $options['toname']);
	$mail -> Subject = '[BlueCalc] Feedback from '.$_REQUEST['name'];
	$mail -> Body = '======================= BlueCalc Feedback Form =======================
	Name:  '.$_REQUEST['name'].'
	Email: '.$_REQUEST['email'].'
	Message:
	'.$_REQUEST['message'];
	if($mail->Send())
		die('TRUE');
	else
		die($mail -> ErrorInfo);
}

$target			= getParam('width', 960);
$col_min_width	= getParam('col_min_width', 10);
$col_max_width	= getParam('col_max_width', 50);
$mar_min_width	= getParam('mar_min_width', 3);
$mar_max_width	= getParam('mar_max_width', 10);
$max 			= 3000;

if ($target > $max || $col_min_width > $max || $col_max_width > $max || $mar_min_width > $max || $mar_max_width > $max)
	$error = 'You can only calculate grids with a maximum width of 3000px!<br />Gosh, what kind of screen do you have?!';
elseif ($col_min_width > $col_max_width)
	$error = 'Maximum column width must be larger than minimum column width.';
elseif ($mar_min_width > $mar_max_width)
	$error = 'Maximum margin must be larger than minimum margin.';
else {
	$calc = true;
	$body = '';
	$count = 0;
	for ($width = $col_min_width ; $width <= $col_max_width ; $width++) {
		for ($margin = $mar_min_width ; $margin <= $mar_max_width ; $margin++) {
			if ( ($target + $margin) % ($width + $margin) == 0) {
				$count++;
				$cols = ($target + $margin) / ($width + $margin);
				$body .= '<div class="grid">'
						."\n\t".'<span class="head">'
						.'<input type="button" value="Download grid.css" onclick="window.location=\'blueprint.php?width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'\';" />'
						.'<input type="button" value="Download grid.png" onclick="window.location=\'gridimg.php?span='.$width.'&margin='.$margin.'\';" />'
						.'<input type="button" value="Preview" onclick="window.open(\'blueprint.php?preview=1&width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'\');" />'
						.'Columns: <b>'.$cols.'</b>; Column Width: <b>'.$width.'</b>; Margin Width: <b>'.$margin.'</b>'
						.'</span>'."\n\t";
				for ($c = 1 ; $c < $cols ; $c++)
					$body .= '<div style="width: '.$width.'px; margin-right: '.$margin.'px;"></div>';
				$body .= '<div style="width: '.$width.'px;"></div>'."\n".'<span></span>'."\n".'</div>'."\n";
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>BlueCalc - A grid calculator for blueprint.css</title>
	<meta name="copyright" content="July 2009" />
	<meta http-equiv="charset" content="utf-8" />
	<meta name="author" content="Peter-Christoph Haider" />
	<meta name="description" content="BlueCalc is a tool to calculate possible grid configurations for Blueprint.css" />
	<meta name="keywords" content="grid, css, blueprint, layout" />
	<link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon"></link>
	<link rel="icon" href="assets/favicon.ico" type="image/x-icon"></link>
	<link type="text/css" rel="stylesheet" href="assets/main.css"/>
	<script type="text/javascript" src="assets/mootools-1.2.4-core.js"></script>
	<script type="text/javascript" src="assets/main.js"></script>
	<style type="text/css">
		.grid { width: <?php echo $target; ?>px; }
	</style>
</head>
<body>

<div id="modal"></div>
<div id="popup">
	<label>Your Email:</label>
	<input type="text" id="txtEmail" />
	<label>Your Name:</label>
	<input type="text" id="txtName" />
	<label>Message:</label>
	<textarea id="txtMessage"></textarea>
	
	<div style="margin-top: 10px">
		<div id="status"></div>
		<input type="button" value="Send" id="btnSend" />
		<input type="button" value="Close" id="btnClose" />
		<div style="clear: both;"></div>
	</div>
</div>

<center>
<form action="index.php" method="POST" id="frmCalc">
	<div class="beta">
		<div class="calc">
			<table border="0" cellpadding="3" cellspacing="5">
				<tr>
					<td>Minimum column width: </td><td><input type="text" value="<?php echo $col_min_width; ?>" size="10" name="col_min_width" id="col_min_width" /></td>
					<td>Minimum margin width: </td><td><input type="text" value="<?php echo $mar_min_width; ?>" size="10" name="mar_min_width" id="mar_min_width" /></td>
					<td>Total grid width: </td><td><input type="text" value="<?php echo $target; ?>" size="10" name="width" id="width" /></td>
				</tr>
				<tr>
					<td>Maximum column width: </td><td><input type="text" value="<?php echo $col_max_width; ?>" size="10" name="col_max_width" id="col_max_width" /></td>
					<td>Maximum margin width: </td><td><input type="text" value="<?php echo $mar_max_width; ?>" size="10" name="mar_max_width" id="mar_max_width" /></td>
					<td colspan="2"><input type="submit" value="Calculate" style="width: 100%;" id="btnSubmit" /></td>
				</tr>
			</table>
		</div>
	</div>
</form>
<?php
if (getParam('width', false) || getParam('col_min_width', false) || getParam('col_max_width', false) || getParam('mar_min_width', false) || getParam('mar_max_width', false)) {
	echo '<div class="preview">'."\n";
	if ($calc) {
		if ($count > 0) {
			echo '<div class="msg success">'.$count.' possible grid configurations calculated!</div>'."\n";
			echo $body;
		} else
			echo '<div class="msg error">There are no possible grid configurations for the specified parameters!</div>'."\n";
	} else
		echo '<div class="msg error">'.$error.'</div>'."\n";
	echo '</div>'."\n";
}
?>
</center>
<div class="footer">
	<table border="0" cellspacing="0" cellpadding="20" width="100%"><tr>
		<td style="background: #ECE0EB; color: #0F010E;" valign="top" align="left" colspan="2">
			<div style="margin: 10px auto; width: 80%; border: 3px dotted #f62020; background: #FFF;">
				<div style="background-color: #EEE; font-size: 1.2em; font-weight: bold; padding: 5px;">Recent update: March 15th 2013!</div>
				<div style="padding: 10px;">
					<ul style="list-style: disc; list-style-position: inside;">
						<li>BlueCalc is published on GitHub now! Feel free to perform your own changes, forks and addons</li>
						<li><a href="https://github.com/zeyon/bluecalc">https://github.com/zeyon/bluecalc</a></li>
					</ul>
				</div>
			</div>
		
			<h1 style="background: #0F010E; color: #ECE0EB;">What's this?</h1>
			<p>This tool helps you to calculate an individual grid layout for your Blueprint-based web site or web application.
			Being a static grid framework you usually start with defining the total with of your grid and then seperate your workspace
			into columns. By default Blueprint comes with a 950px grid, which is devided into 24 columns. However, often this configuration 
			is not sufficient to create the kind of layout you have in mind. On the web I found a 
			<a href="http://kematzy.com/blueprint-generator/" target="_blank">Blueprint CSS generator</a>, which actually worked pretty nice
			but lacked the functionality to preview the grid and to compare different grid configuration. 
			As I was tired of the guesswork required to calculate a reasonable grid, I created this little tool to calculate and compare different grid configurations.</p>
			
			<div style="margin: 7px; padding: 10px; background: #FFF; color: #404040;"><b>Give me your feedback!</b> Is something missing? Have you discovered a bug? <a href="#" id="btnMail">Just let me know!</a></div>
		</td>
	</tr></tr>
		<td style="background: #E2E2D7; color: #28281B;" width="55%" valign="top" align="left">
			<h1 style="background: #28281B; color: #E2E2D7;">About Blueprint</h1>
			<p><i>"Blueprint is a CSS framework, which aims to cut down on your development time. It gives you a solid foundation to 
			build your project on top of, with an easy-to-use grid, sensible typography, useful plugins, and even a stylesheet for printing."</i> 
			<span style="font-size: 9px; margin-left: 10px;">Source: <a href="http://www.blueprintcss.org" target="_blank">www.blueprintcss.org</a></span></p>
			<p>Blueprint is licensed unter the GPL and the MIT License. Using this tool is absolutely free! Thanks to the Blueprint team for the awsome work!</p>
		</td>
		<td style="background: #F3E2D8; color: #40332B;" width="45%" valign="top" align="left">
			<h1 style="background: #40332B; color: #F3E2D8;">How does this work?</h1>
			<ul>
				<li>Enter the width of your grid.</li>
				<li>Add some constraints for your margin and column width.</li>
				<li>Hit the <i>calculate</i> button to calculate all possible configurations.</li>
				<li>Preview/download the <i>grid.css</i> file and add it to your <a href="http://www.blueprintcss.org" target="_blank">Blueprint library</a>.</li>
			</ul>
		</td>
	</tr></table>
	<table border="0" cellpadding="20" cellspacing="0" width="100%"><tr>
		<td width="126" valign="top" align="left">
			<img src="assets/zeyon.png" width="200" height="133" border="0" />
		</td>
		<td valign="top" align="left" style="font-size: 1.3em;">
			<p>This project is hosted and sponsored by <a href="http://www.zeyon.net" target="_blank"><b>Zeyon</b></a>.</p>
			<p>We develop smart software products to streamline and optimize business processes. Visit us on <a href="http://www.zeyon.net" target="_blank"><b>www.zeyon.net</b></a>.</p>
		</td>
	</tr></table>
</div>
<div class="change">
	Version 2.3; Last change: 2013-03-15<br />
	Copyright &copy; 2009-2013 by Peter-Christoph Haider.<br />
	All rights reserved. All trademarks held by their respective owners. Zeyon and the Zeyon logo belong to Zeyon Technologies, Inc.
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16894439-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>