<?php
/**
 * A small script to calculate Blueprint CSS Grids
 *
 * @param int $target		The total width of your grid
 * @param int $col_min_width	Minimal column width
 * @param int $col_max_width	Maximal column width
 * @param int $mar_min_width	Minimal margin width
 * @param int $mar_max_width	Maximal margin width
 * 
 * @author Peter-Christoph Haider
 * @author Fabian Wolf (@link http://usability-idealist.de)
 * @version 2.4 
 * 
 * Fork me at github => @link https://github.com/ginsterbusch/bluecalc
 * 
 */

// init et all
define('SITE_NAME', 'Bluecalc2');
define('SITE_VERSION', '2.4');
define('SITE_TITLE', SITE_NAME . ' v' . SITE_VERSION . ' - A grid calculator for Blueprint CSS');

$arrBodyClass = array();

/**
 * Initializes a requested parameter
 *
 * @param string $param The paramter to read
 * @param int $default The default value
 */
 

function getParam($param, $default) {
	return (array_key_exists($param, $_REQUEST) && (int)$_REQUEST[$param] > 0) ? (int)$_REQUEST[$param] : $default;
}

$target			= getParam('width', 960);
$col_min_width	= getParam('col_min_width', 10);
$col_max_width	= getParam('col_max_width', 50);
$mar_min_width	= getParam('mar_min_width', 3);
$mar_max_width	= getParam('mar_max_width', 10);
$max 			= 3000;

if ($target > $max || $col_min_width > $max || $col_max_width > $max || $mar_min_width > $max || $mar_max_width > $max) {
	$error = 'You can only calculate grids with a maximum width of '.$max.'!<br />Gosh, what kind of screen do you have?!';
	$arrBodyClass += array('error', 'max-width');
} elseif ($col_min_width > $col_max_width) {
	$error = 'Maximum column width must be larger than minimum column width.';
	$arrBodyClass += array('error', 'min-col-width');
} elseif ($mar_min_width > $mar_max_width) {
	$error = 'Maximum margin must be larger than minimum margin.';
	$arrBodyClass += array('error', 'min-margin');
} else {
	
	
	$calc = true;
	$body = '';
	$count = 0;
	for ($width = $col_min_width ; $width <= $col_max_width ; $width++) {
		for ($margin = $mar_min_width ; $margin <= $mar_max_width ; $margin++) {
			if ( ($target + $margin) % ($width + $margin) == 0) { // use modulo for detecting integer numbers
				$count++;
				$cols = ($target + $margin) / ($width + $margin);
				
				$body .= '<div class="grid">'
						."\n\t".'<span class="head">';
						
				
						
				/** NOTE: Out with the old ..
						
						.'<input type="button" value="Download grid.css" onclick="window.location=\'blueprint.php?width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'\';" />'
						.'<input type="button" value="Download grid.png" onclick="window.location=\'gridimg.php?span='.$width.'&margin='.$margin.'\';" />'
						.'<input type="button" value="Preview" onclick="window.open(\'blueprint.php?preview=1&width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'\');" />';
				*/
				
				/**
				 * NOTE: ... in with the new!
				 */
				$body .= "\t" . '<ul>' . "\n\t\t"
						/* show grid source */
				
							.'<li><a href="blueprint.php?preview=1&width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'">Source code</a></li>' . "\n\t"
						
						/* download grid.css */
						
							.'<li><a href="blueprint.php?width='.$target.'&columns='.$cols.'&span='.$width.'&margin='.$margin.'">Download grid.css</a></li>' . "\n\t\t"
						
						/* download grid image */
						
							.'<li><a href="gridimg.php?span='.$width.'&margin='.$margin.'">Download grid.png</a></li>' . "\n\t\t"

						."</ul>\n\t";
				
				$body .= 'Columns: <strong>'.$cols.'</strong>; Column Width: <strong>'.$width.'</strong>; Margin Width: <strong>'.$margin.'</strong>'
						.'</span>'."\n\t";
						
				for ($c = 1; $c < $cols; $c++) {
					$body .= '<div class="'.($c % 2 ? 'odd' : 'even').'" style="width: '.$width.'px; margin-right: '.$margin.'px;"></div>';
				}
				
				$body .= '<div class="'.($c % 2 ? 'odd' : 'even').'" style="width: '.$width.'px;"></div>'."\n".'<span></span>'."\n".'</div>'."\n";
			}
		}
	}
	
	$arrBodyClass += array('calculation', 'results', 'results-number' . $count );
	
}

// main


// include header
require_once('includes/templates/header.php');

// main content
?>

		<div id="top">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="frmCalc">
				<div class="beta">
					<div class="calc">
						<table border="0" cellpadding="3" cellspacing="5">
							
							<tr>
								<td><label for="">Total grid width:</label></td><td><input type="text" value="<?php echo $target; ?>" size="6" name="width" id="width" /></td>
								<td colspan="2"><button type="submit" style="width: 100%;" id="btnSubmit">Calculate grids</button></td>
							</tr><!-- /grid width + submit button -->
							
							<!-- column width -->
							<tr>
								<td><label for="">Minimum column width:</label></td><td><input type="text" value="<?php echo $col_min_width; ?>" size="6" name="col_min_width" id="col_min_width" /></td>
								
								<td><label for="">Maximum column width:</label></td><td><input type="text" value="<?php echo $col_max_width; ?>" size="6" name="col_max_width" id="col_max_width" /></td>
							</tr><!-- /column width -->
							
							<!-- margin width -->
							<tr>
								
								
								<td><label for="">Minimum margin width:</label></td><td><input type="text" value="<?php echo $mar_min_width; ?>" size="6" name="mar_min_width" id="mar_min_width" /></td>
								
								<td><label for="">Maximum margin width:</label></td><td><input type="text" value="<?php echo $mar_max_width; ?>" size="6" name="mar_max_width" id="mar_max_width" /></td>
								
							</tr><!-- /margin width -->
							
						</table>
					</div>
				</div>
			</form>
		</div>

<?php
if (getParam('width', false) || getParam('col_min_width', false) || getParam('col_max_width', false) || getParam('mar_min_width', false) || getParam('mar_max_width', false)) {
	?>
		<div class="preview">
		
		<?php
		if ($calc) {
			if ($count > 0) {
				echo '<div class="msg success">'.$count.' possible grid configurations calculated!</div>'."\n";
				echo $body;
			} else {
				echo '<div class="msg error">There are no possible grid configurations for the specified parameters!</div>'."\n";
			}
		} else {
			echo '<div class="msg error">'.$error.'</div>'."\n";
		} ?>
		
		</div>
	<?php
}



/**
 * load only on front page
 * NOTE: QUERY_STRING usually is ALWAYS set, so checking with !isset will NEVER be false! .. and thus the entirely wrong choice
 */
if( empty($_SERVER['QUERY_STRING']) != false ) {
	
	if( file_exists('news.inc.php') && filesize('news.inc.php') > 0) {
?>
		<div class="box news">
			<?php include('news.inc.php'); ?>
			
		</div>
<?php 
	}
	
} ?>

		<div class="row info" id="info">
			<h2>What's this?</h2>
		
			<p>This tool helps you to calculate an individual grid layout for your Blueprint-based web site or web application.
				Being a static grid framework you usually start with defining the total with of your grid and then seperate your workspace
				into columns. By default Blueprint comes with a 950px grid, which is devided into 24 columns. However, often this configuration 
				is not sufficient to create the kind of layout you have in mind.<br />
				
				The original author of BlueCalc found a 
				<a href="http://kematzy.com/blueprint-generator/">Blueprint CSS generator</a> on the web, which actually worked pretty nice, but lacked the functionality to preview the grid and to compare different grid configuration. 
				As he was tired of the guesswork required to calculate a reasonable grid, he created this little tool to calculate and compare different grid configurations.</p>
		</div>
		
		<div class="row feedback" id="feedback">
			<p><strong>Give me your feedback!</strong> Is something missing? Have you discovered a bug? <a href="http://usability-idealist.de/en/contact">Just let me know!</a></p>
		
		</div>
		
		<div class="box about" id="about">
			<h2>About Blueprint</h2>
			<p><em>&quot;Blueprint is a CSS framework, which aims to cut down on your development time. It gives you a solid foundation to 
			build your project on top of, with an easy-to-use grid, sensible typography, useful plugins, and even a stylesheet for printing.&quot;</em> 
			<span style="font-size: 9px; margin-left: 10px;">Source: <a href="http://www.blueprintcss.org">www.blueprintcss.org</a></span></p>
			<p>Blueprint is licensed unter the GPL and the MIT License. Using this tool is absolutely free! Thanks to the Blueprint team for the awsome work!</p>
		</div>
			
		<div class="box how" id="how">
			<h2>How does this work?</h2>
			
			<ol>
				<li>Enter the width of your grid.</li>
				<li>Add some constraints for your margin and column width.</li>
				<li>Hit the <i>calculate</i> button to calculate all possible configurations.</li>
				<li>Preview/download the <i>grid.css</i> file and add it to your <a href="http://www.blueprintcss.org">Blueprint library</a>.</li>
				<li>Optionally: <a href="https://github.com/ginsterbusch/bluecalc">Fork this project on GitHub</a> ;)</li>
			</ol>
			
		</div>
		
		<div class="row powered-by">
			<div class="powered-by-logo">
				<a href="http://usability-idealist.de/"><img src="http://usability-idealist.de/images/ui25-logo-button-wb180x70.png" /></a>
			</div>
			<div class="powered-by-text">
				<p>This project is proudly powered by <a href="http://usability-idealist.de/">Usability Idealist</a> - web &amp; frontend development, focused on Usability, WordPress and E-Commerce solutions. More info at: <a href="http://usability-idealist.de/">http://usability-idealist.de/</a></p>
			</div>
			
		</div>
		
<?php 
// include footer
require_once('includes/templates/footer.php'); 
?>
