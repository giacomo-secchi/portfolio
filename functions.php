<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */



 

/**
 * Enqueue styles.
 *
 * @since Twenty Twenty-Two 1.0
 *
 * @return void
 */
function twentytwentythreechild_scripts() {
	// Register theme stylesheet.
	$theme_version = wp_get_theme()->get( 'Version' );

	$version_string = is_string( $theme_version ) ? $theme_version : false;
	// wp_register_script(
	// 	'twentytwentythreechild-index',
	// 	get_stylesheet_directory_uri() . '/build/index.js',
	// 	array(),
	// 	$version_string,
	// 	true
	// );

	if ( is_page_template( 'landing-page' ) ) :
		wp_enqueue_script( 'parallax-js', get_stylesheet_directory_uri() . '/assets/js/parallax.min.js', array(  ), '3.1.0', true );

		wp_enqueue_style( 'landing', get_stylesheet_directory_uri() . '/assets/css/landing.css', array(), $version_string );

	endif;

	wp_enqueue_script( 'twentytwentythreechild-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array( 'parallax-js' ), $version_string, true );

}

add_action( 'wp_enqueue_scripts', 'twentytwentythreechild_scripts' );


if ( ! function_exists( 'twentytwentythreechild_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentythreechild_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentythreechild_support' );

 

 


