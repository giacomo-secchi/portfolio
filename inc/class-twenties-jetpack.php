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
			add_filter( 'rest_jetpack-portfolio_query', array( $this, 'rest_project_date' ), 10, 2 );
			add_filter( 'wpseo_exclude_from_sitemap_by_post_ids',  array( $this, 'exclude_posts_from_xml_sitemaps' ), 10, 2 );
			// add_action( 'init', array( $this, 'register_block_bindings' ) );

			add_filter( 'wp', function () {
				add_action( 'template_redirect', array( $this, 'redirect_single_posts_to_not_found' ), 10, 2 );

				add_filter( 'is_post_type_viewable', array( $this, 'change_post_type_visibility' ), 10, 2 );
			}, 10, 2 );
		}



		public function rest_project_date( $args, $request ) {

			// add same meta query arguments for rest api (backend).
			$args = $this->filter_query();
			return $args;
		}


		private function filter_query() {
			$queried_object = get_queried_object();

			if ( $queried_object instanceof WP_Term ) {
				$query['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => get_queried_object()->taxonomy,
						'field'    => 'slug',
						'terms'    => get_queried_object()->slug,
					),
				);
			}

			$query['post_type'] = 'jetpack-portfolio';

			// The meta key would be the writepoetry_project_year field assigned to the jetpack-portfolio CPT
			$query['meta_key'] = 'writepoetry_project_year';

			// number of post to show per page. Use 'posts_per_page'=>-1 to show all posts
			$query['posts_per_page'] = -1;

			// also likely want to set order by this key in desc so more recent project are listed first.
			$query['orderby'] = 'meta_value';
			$query['order'] = 'desc';

			return $query;
		}

		public function projects_pre_render_block( $pre_render, $parsed_block ) {
			// Verify it's the block that should be modified using the namespace.
			if ( isset( $parsed_block['attrs']['namespace'] )  && 'jetpack/projects-list' === $parsed_block['attrs']['namespace'] ) {
				// Filters the arguments which will be passed to WP_Query for the Query Loop Block.
				add_filter( 'query_loop_block_query_vars',
					function( $query, $block ) use ( $parsed_block ) {
						// add same meta query arguments for front-end.
						$query = $this->filter_query();
                		return $query;
					},
					10,
					2
				);
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

		/**
		 * Excludes posts from XML sitemaps.
		 *
		 * @return array The IDs of posts to exclude.
		 */
		public function exclude_posts_from_xml_sitemaps() {
			$args = array(
				'post_type'      => self::CUSTOM_POST_TYPE,
				'posts_per_page' => -1,
				'fields'         => 'ids', // Only get post IDs
			);

			$post_ids = get_posts( $args );

			// $post_ids now contains an array of post IDs
			return $post_ids;
		}
	}
endif;

return new Twenties_Child_Jetpack();
