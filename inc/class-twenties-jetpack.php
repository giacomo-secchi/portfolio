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

if ( ! class_exists( 'Twenties_Child_Jetpack' ) ) :

	/**
	 * The main Twenties class
	 */
	class Twenties_Child_Jetpack {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
			add_filter( 'pre_render_block',  array( $this, 'projects_pre_render_block' ), 10, 2 );
			add_filter( 'rest_jetpack-portfolio_query', array( $this, 'rest_project_date' ), 10, 2 );
			add_filter( 'the_content', array( $this, 'print_metadata' ) );

		}

		public function print_metadata( $content ) {
			$value1 = get_post_meta( get_the_ID(), '_mcf_project_year', true );
			$value2 = get_post_meta( get_the_ID(), '_mcf_project_client', true );
			// check value is set before outputting
			if ( $value2 ) {
				return sprintf( "%s (%s)", $content, esc_html( $value2 ) );
			} else {
				return $content;
			}
 		}


		public function editor_assets() {
			wp_enqueue_script( 'twenties-block-variations', get_stylesheet_directory_uri() . '/assets/js/block-variations.js', array( 'wp-blocks' ) );
		}



		public function rest_project_date( $args, $request ) {

			// $dateFilter = $request->get_param( 'test' );

			// add same meta query arguments
			if ( isset( $request['_mcf_project_year'] ) ) {
				$args['meta_key'] = '_mcf_project_year';
				$args['orderby'] = 'meta_value_num';
				$args['order'] = 'ASC';
			}

			return $args;
		}




		public function projects_pre_render_block( $pre_render, $parsed_block ) {
		 	 // Verify it's the block that should be modified using the namespace
		 	 if ( ! isset( $parsed_block['attrs']['namespace'] ) ) {
				return;
			}

			if ( 'jetpack/projects-list' !== $parsed_block['attrs']['namespace'] ) {
				return;
			}

			add_filter( 'query_loop_block_query_vars',
				function( $query, $block ) use ( $parsed_block ) {
					// the meta key was portfolio_creation_date, compare to today to get event's from today or later
					$query['meta_key'] = '_mcf_project_year';

					// also likely want to set order by this key in ASC so next event listed first
					$query['orderby'] = 'meta_value_num';
					$query['order'] = 'ASC';



					return $query;
			}, 10, 2 );

		  	return $pre_render;
		}
	}
endif;

return new Twenties_Child_Jetpack();
