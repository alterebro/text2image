<?php

function gradientcircle($image, $cx, $cy, $size, $color) {
	$color = array(0xFF & ($color >> 0x10), 0xFF & ($color >> 0x8), 0xFF & $color);
	$steps =  60;
	$step_alpha = 127 / $steps;
	$step_size = $size / $steps;

	for ($i=0; $i<$steps ; $i++) {
		$c = imagecolorallocatealpha($image, $color[0], $color[1], $color[2], 127 - ($step_alpha*($steps-$i)/8)  );
		imagefilledellipse($image, $cx, $cy, $step_size*$i, $step_size*$i, $c);
	}
}

function text2image( $str ) {

	$str_hash = md5( $str );
	$str_hash_blocks = str_split($str_hash, 6);

	$canvas_size = 100;
	$canvas_resize = 400;

	// Background Solid
	$im = imagecreatetruecolor($canvas_size, $canvas_size);
	$im_bg = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, $canvas_size, $canvas_size, $im_bg);

	// Background Alpha
	$bg_alpha = floor((255-hexdec($str_hash_blocks[5]))/2);
	$bg_color_blocks = str_split($str_hash_blocks[4], 2);
	$bg_color = imagecolorallocatealpha(
		$im,
		hexdec($bg_color_blocks[0]),
		hexdec($bg_color_blocks[1]),
		hexdec($bg_color_blocks[2]),
		$bg_alpha
	);
	imagefilledrectangle($im, 0, 0, $canvas_size, $canvas_size, $bg_color);

	// Gradients
	for ( $i=0; $i<4; $i++ ) {
		switch ($i) {
			case 3: $x = 0; $y = $canvas_size; break;
			case 2: $x = $canvas_size; $y = $canvas_size; break;
			case 1: $x = $canvas_size; $y = 0; break;
			case 0: $x = 0; $y = 0; break;
			default: $x = 0; $y = 0; break;
		}

		$c_blocks = str_split($str_hash_blocks[$i], 2);
		$c_grad = imagecolorallocate($im, hexdec($c_blocks[0]), hexdec($c_blocks[1]), hexdec($c_blocks[2]));
		gradientcircle($im, $x, $y, $canvas_size+($canvas_size), $c_grad);
	}

	// Blur
	imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
	imagefilter($im, IMG_FILTER_SELECTIVE_BLUR);
	imagefilter($im, IMG_FILTER_SMOOTH, -4);

	// Resize
	$_im = imagecreatetruecolor($canvas_resize, $canvas_resize);
	imagecopyresized($_im, $im, 0, 0, 0, 0, $canvas_resize, $canvas_resize, $canvas_size, $canvas_size);

	// Convert to data
	ob_start ();
		imagepng($_im);
		$image_data = ob_get_contents();
	ob_end_clean ();
	$image_data_base64 = 'data:image/png;base64,' . base64_encode($image_data);

	// Release
	imagedestroy($im);
	imagedestroy($_im);

	return [
		'string' 	=> $str,
		'hash' 		=> $str_hash,
		'image' 	=> $image_data_base64
	];
}

?>
