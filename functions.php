<?php
/**
 * portfolio Website Child Theme
 *
 * @package Portfolio
 */


$portfolio = (object) array(

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-portfolio.php',
);

/**
 * Initialize Jetpack compatibility.
 */
if ( class_exists( 'Jetpack' ) ) {
	$portfolio->jetpack = require 'inc/class-portfolio-jetpack.php';
}

/**
 * Initialize TranslatePress - Multilingual compatibility.
 */
if (class_exists( 'TRP_Translate_Press' ) ) {
	$portfolio->translatepress = require 'inc/class-portfolio-translatepress.php';
}


