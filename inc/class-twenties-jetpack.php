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
		const CUSTOM_POST_TYPE       = 'jetpack-portfolio';
		const CUSTOM_TAXONOMY_TYPE   = 'jetpack-portfolio-type';
		const CUSTOM_TAXONOMY_TAG    = 'jetpack-portfolio-tag';


		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'pre_render_block',  array( $this, 'projects_pre_render_block' ), 10, 2 );
			// add_filter( 'rest_jetpack-portfolio_query', array( $this, 'rest_project_date' ), 10, 2 );
			// add_filter( 'the_content', array( $this, 'print_metadata' ) );
			// add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );

			add_action( 'init', array( $this, 'register_block_bindings' ) );
			add_filter( 'wp', function () {
				add_action( 'template_redirect', array( $this, 'redirect_single_posts_to_not_found' ), 10, 2 );
				add_filter( 'is_post_type_viewable', array( $this, 'change_post_type_visibility' ), 10, 2 );
			}, 10, 2 );
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
			if ( isset( $parsed_block['attrs']['namespace'] )  && 'jetpack/projects-list' === $parsed_block['attrs']['namespace'] ) {

				add_filter( 'query_loop_block_query_vars',

					function( $query, $block ) use ( $parsed_block ) {

						$query['post_type'] = 'jetpack-portfolio';

						// the meta key was portfolio_creation_date, compare to today to get event's from today or later
						$query['meta_key'] = 'writepoetry_project_client';

						// also likely want to set order by this key in ASC so next event listed first
						$query['orderby'] = 'meta_value';
						$query['order'] = 'ASC';

						return $query;
				}, 10, 2 );
			}

		  	return $pre_render;
		}


		/**
		 * Change post type visibility.
		 *
		 * @param bool $is_viewable Whether the post type is viewable.
		 * @param object $post_type The post type object.
		 * @return bool
		 */
		public function change_post_type_visibility( $is_viewable, $post_type ) {
			if ( false == $is_viewable || self::CUSTOM_POST_TYPE === $post_type->name ) {
				return false;
			}

			return $is_viewable;
		}

		/**
		 * Redirect single posts to 404.
		 */
		public function redirect_single_posts_to_not_found() {
			global $wp_query;

			if ( ! is_singular( self::CUSTOM_POST_TYPE ) ) {
				return;
			}

			$wp_query->set_404();
			status_header( 404 );
			get_template_part( '404' );
		}

		public function editor_assets() {
			wp_enqueue_script( 'twenties-block-variations', get_stylesheet_directory_uri() . '/assets/js/block-variations.js', array( 'wp-blocks' ) );
		}

		public function register_block_bindings() {
			register_block_bindings_source( 'twenties/jetpack-projects-infos', array(
				'label'              => __( 'Project Informations', 'twenties' ),
				'get_value_callback' => array( $this, 'get_project_types' ),
				'uses_context'       => [ 'postId', 'postType' ]
			) );
		}

		public function get_project_types( $source_args, $block_instance, $attribute_name ) {
			// If no key or user ID argument is set, bail early.
			if ( ! isset( $source_args['key'] ) ) {
				return null;
			}

			// Get the post ID.
			$post_id = $block_instance->context["postId"];

			// Define the taxonomy type based on the key argument.
			$taxonomy = null;

			// Return the data based on the key argument.
			switch ( $source_args['key'] ) {
				case 'types':
					$taxonomy  = self::CUSTOM_TAXONOMY_TYPE;
					break;
				case 'tags':
					$taxonomy = self::CUSTOM_TAXONOMY_TAG;
					break;
				default:
					return null;
			}

			//Returns All Term Items for the specified taxonomy.
			$terms = wp_get_post_terms( $post_id, $taxonomy );

			if ( is_wp_error( $terms ) ) {
				return 'Error retrieving terms';
			}

			return implode( ', ', array_column( $terms, 'name' ) );
		}
	}
endif;

return new Twenties_Child_Jetpack();
