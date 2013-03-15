<?php

/**
 * Creates the grid-background-image grid.png
 *
 * @param int $column	Width of the column
 * @param int $margin	Width of the margin
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
 
$span 	= getParam('span', 0);
$margin	= getParam('margin', 0);
$height	= 20;

if ($span < 500 && $margin < 500) {
	header("Content-type: image/png");
	header('Content-Disposition: attachment; filename="grid.png"');
	$img = @imagecreate(($span + $margin), $height) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($img, 251, 253, 253);
	$blue = imagecolorallocate($img, 191, 212, 217);
	imagefilledrectangle($img, 0, 0, $span-1, $height, $blue);	
	imagepng($img);
	imagedestroy($img);
} else
	echo 'Parameters out of range';

?>