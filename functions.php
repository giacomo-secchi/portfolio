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



$storefront = (object) array(
	'version'    => $theme_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-twenties.php',
);



if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/class-twenties-jetpack.php';
}
