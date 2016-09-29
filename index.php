<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'backend/functions.php';

// ----------------------------------

define ('DEV_MODE', false); // Development Mode : false to minify and gzip.
define ('ROOT', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST']);
define ('URL', ROOT . $_SERVER['REQUEST_URI']);

$default_image = [
	'string' 	=> 'empty string',
	'hash' 		=> 'd41d8cd98f00b204e9800998ecf8427e',
	'image' 	=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAABO0lEQVQoUxXMO0tcQRgG4Pf9LjPnnHV1jYRASptACIi/0QVZCyGBFKnTpk6b1tJfINjYiOsl6l7mzHxi//Dw8svJqxws1/bpbm0f7wpmy8rdB0T/pJCtt1Y61Nrx4tt8KXuPRfbvRz+439qH22bTZzRpGmheWV0ijP+O5jcyeQ6Z/q82exh92IQOW1i3YQAWgDJA/j0+vWZehey8NE8F0o2w1MJTpeaR7w4A+ed4cUUroWlLsYCmBlciJQlPATNBNCF/Hy2uCIQYoAomBdQFLg5XDReD0MhfX8+uBRYi2twIpSCbwehwyUitF0Uivx+e3wg1TKWqWs2mYe8bOnod1MaJ5jaQi88/lq4czVgse8lq1dnDx16t7Hja7LqWiXG+/3PlfaxS5tqTlCRdzTHA6qBpM3V93et0PeveAIo9ePbB4aq2AAAAAElFTkSuQmCC"
];
$url_str = ((count($_GET) > 0) && isset($_GET['s'])) ? htmlspecialchars($_GET['s'], ENT_HTML5) : false;
$image = ($url_str) ? text2image($url_str) : $default_image;


// ----------------------------------
// ----------------------------------

if (!DEV_MODE) { ob_start(); }
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Text to Image Generator. MD5 visualization</title>
	<meta name="description" content="Generate beautiful gradient images based on the MD5 hash values of any given text" />
	<meta name="author" content="Jorge Moreno aka moro, moro.es (@alterebro)" />
	<meta name="MobileOptimized" content="width" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#222" />
	<meta name="twitter:title" property="og:title" itemprop="title name" content="Text to Image Generator. MD5 visualization" />
	<meta name="twitter:description" property="og:description" itemprop="description" content="Generate beautiful gradient images based on the MD5 hash values of any given text" />
	<meta name="twitter:url" property="og:url" itemprop="url" content="<?php echo URL; ?>" />
	<meta name="twitter:image" property="og:image" itemprop="image" content="<?php echo ROOT; ?>/frontend/images/text-to-image.jpg" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@alterebro" />
	<meta name="twitter:creator" content="@alterebro" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="636040175" />
	<meta property="article:author" content="https://www.facebook.com/alterebro" />
	<meta property="article:publisher" content="https://www.facebook.com/alterebro" />
	<link rel="me" href="https://twitter.com/alterebro" />
	<style media="screen"><?php include (DEV_MODE) ? 'frontend/styles/app.css' : 'frontend/styles/app.min.css'; ?></style>
</head>
<body>
<div id="app">
	<header>
		<h1><a href="/">Text to Image Generator</a></h1>
		<h2>Convert any String to Gradient Image</h2>
		<hr />
	</header>

	<main>
		<p>
			Create beautiful gradient abstract images using your own string as seed.
			Insert your text below and generate an image based on its MD5 hash value.
		</p>
		<form id="f" method="get" autocomplete="off">
			<p>
				<label for="s">Text Input</label>
				<input type="text" name="s" id="s" placeholder="Insert your Text Here" spellcheck="false" required />
				<input type="submit" value="generate" />
			</p>
		</form>
		<p id="o">
			<img id="i" src="<?php echo $image['image'] ?>" alt="text2image" width="400" />
		</p>

		<?php if ($url_str) : ?>
		<ul id="dl">
			<li>[ <a id="l" href="<?php echo $image['image'] ?>" download="text2image.moro.es-<?php echo substr($image['hash'], 0, 8) ?>.png">Download Generated Image</a> ]</li>
			<li><strong>String</strong> : <em id="ds"><?php echo $image['string'] ?></em></li>
			<li><strong>MD5 Hash</strong> : <em id="dh"><?php echo $image['hash'] ?></em></li>
		</ul>
		<?php endif; ?>
	</main>

	<footer>
		<hr />
		<p>
			<strong>Text 2 Image</strong> created by : <a href="//twitter.com/alterebro">@alterebro</a> &copy; 2016.
			Source Code on <a href="//github.com/alterebro/text2image">GitHub</a>
		</p>
	</footer>
</div>

<?php
if (DEV_MODE) {

	echo '<script type="text/javascript">';
	include 'frontend/scripts/md5.js';
	include 'frontend/scripts/app.js';
	echo '</script>';

} else {

	echo '<script type="text/javascript">';
	include 'frontend/scripts/md5.min.js';
	include 'frontend/scripts/app.min.js';
	echo '</script>';
}
?>
</body>
</html>
<?php

// ----------------------------------
// ----------------------------------

if (!DEV_MODE) {

	$html_data = ob_get_contents();
	ob_end_clean ();

		$minify_search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
		$minify_replace = array('>', '<', '\\1');
		$output = preg_replace($minify_search, $minify_replace, $html_data);

		$output = str_replace(' />', '>', $output);

	ob_start("ob_gzhandler");
	echo $output;
	ob_end_flush();

}
?>
