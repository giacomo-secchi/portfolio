<?php
/**
 * Twenties Child Theme
 *
 * @package Twenty_Twenty_Child
 */

/**
 * Get theme version.
 */
$theme_version  = wp_get_theme( get_stylesheet() )->get( 'Version' );
$version_string = is_string( $theme_version ) ? $theme_version : false;



$twenties = (object) array(
	'version'    => $theme_version,

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
