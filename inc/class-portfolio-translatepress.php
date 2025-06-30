<?php
/**
 * Portfolio Child Theme Class
 *
 * @since    0.0.1
 * @package  Portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Portfolio_Translatepress' ) ) :

	/**
	 * The main Portfolio class
	 */
	class Portfolio_TranslatePress {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {} 
	}
endif;

return new Portfolio_TranslatePress();
