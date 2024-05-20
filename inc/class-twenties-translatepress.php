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

if ( ! class_exists( 'Twenties_Child_Translatepress' ) ) :

	/**
	 * The main Twenties class
	 */
	class Twenties_Child_TranslatePress {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_block_bindings' ) );
		}


		public function register_block_bindings() {
			register_block_bindings_source( 'twenties/translatepress-language-switcher', array(
				'label'              => __( 'Translatepress Language Switcher', 'twenties' ),
				'get_value_callback' => array( $this, 'custom_language_switcher' )
			) );
		}

		public function custom_language_switcher() {
			$array = trp_custom_language_switcher();
			// IMPORTANT! You need to have data-no-translation on the wrapper with the links or TranslatePress will automatically translate them in a secondary language. -->
			$html = '<span data-no-translation style="color: var(--wp--preset--color--cyan-bluish-gray);">';

			// Check whether TranslatePress can run on the current path or not. If the path is excluded from translation, trp_allow_tp_to_run will be false -->
			if ( apply_filters( 'trp_allow_tp_to_run', true ) ) {
				$index = 0;
				foreach ( $array as $name => $item ) {
					$html .= 	'<a href="' . $item['current_page_url'] . '"><span style="text-transform: uppercase;">' . $item['short_language_name'] . '</span></a>';
					if ( $index < count( $array ) - 1 ) {
						$html .= '&nbsp;&#47;&nbsp;';
					}

					$index++;
				}
			}

			$html .= '</span>';

			return $html;
		}
	}
endif;

return new Twenties_Child_TranslatePress();
