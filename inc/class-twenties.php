<?php
/**
 * Twenties Child Class
 *
 * @since    0.0.1
 * @package  Twenty_Twenty_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Twenties_Child' ) ) :

	/**
	 * The main Twenties class
	 */
	class Twenties_Child {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'setup' ) );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/storefront-it_IT.mo.
			load_theme_textdomain( 'twenties', trailingslashit( WP_LANG_DIR ) . 'themes' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'twenties', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/storefront/languages/it_IT.mo.
			load_theme_textdomain( 'twenties', get_template_directory() . '/languages' );




			/**
			 * Add support for Block Styles.
			 */
			add_theme_support( 'wp-block-styles' );



			/**
			 * Add support for editor styles.
			 */
			add_theme_support( 'editor-styles' );


			/**
			 * Enqueue editor styles.
			 */
			// add_editor_style( 'style.css' );


			/**
			 * Add support for responsive embedded content.
			 */
			add_theme_support( 'responsive-embeds' );
 		}

	}
endif;

return new Twenties_Child();
