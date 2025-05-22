<?php
/**
 * Twenties Child Theme
 *
 * @package Twenty_Twenty_Child
 */




$twenties = (object) array(

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-twenties.php',
);

/**
 * Initialize Jetpack compatibility.
 */
if ( class_exists( 'Jetpack' ) ) {
	$twenties->jetpack = require 'inc/class-twenties-jetpack.php';
}

/**
 * Initialize TranslatePress - Multilingual compatibility.
 */
if (class_exists( 'TRP_Translate_Press' ) ) {
	$twenties->translatepress = require 'inc/class-twenties-translatepress.php';
}


// require 'inc/class-twenties-ajax.php';
