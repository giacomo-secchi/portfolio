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
			add_filter( 'wp_body_open',  array( $this, 'popup_template' ), 10, 2 );

			add_filter( 'render_block_core/read-more', array( $this, 'modify_readmore_blocks' ), 10, 2 );

			add_action( 'wp_ajax_get_project',  array( $this, 'get_project_data' ), 10, 2 );
			add_action( 'wp_ajax_nopriv_get_project',  array( $this, 'get_project_data' ), 10, 2 );

			add_filter( 'render_block_core/post-terms', array( $this, 'remove_taxonomy_links' ), 10, 2 );

			add_filter( 'init', function () {
				add_filter( 'pre_render_block',  array( $this, 'load_scripts' ), 10, 2 );
				add_filter( 'rest_jetpack-portfolio_query', array( $this, 'rest_project_date' ), 10, 2 );
			}, 10, 2 );
		}


		public function load_scripts( $pre_render, $parsed_block ) {
			wp_enqueue_script(
				'main',
				get_theme_file_uri( '/assets/js/main.js' ),
				array( 'wp-api-fetch' ),
				'1.0.0',
				true
			);
		}

		function modify_readmore_blocks( $block_content, $block ) {
			if ( isset( $block['blockName'] ) && 'core/read-more' === $block['blockName'] ) {
				global $post;
				
				if ( $post && isset( $post->ID ) ) {
					// Add the custom class to the block content using the HTML API.
					$processor = new WP_HTML_Tag_Processor( $block_content );
					
					if ( $processor->next_tag( 'a' ) ) {
						$processor->set_attribute( 'data-project-id', $post->ID );
						
						return $processor->get_updated_html();
					}
				}
			}	
			
			return $block_content;
		}


		public function remove_taxonomy_links($block_content, $block) {
			if (isset($block['attrs']['term']) ) {
				// Remove all tag links from the block content.
				$block_content = preg_replace('/<a\b[^>]*>(.*?)<\/a>/i', '$1', $block_content);
			}
			
			return $block_content;
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

			// Show all posts
			$query['nopaging'] = true;

			// Skip SQL_CALC_FOUND_ROWS for performance (no pagination).
			$query['no_found_rows'] = true;

			// also likely want to set order by this key in desc so more recent project are listed first.
			$query['orderby'] = 'meta_value_num';
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
		 * Add a custom template to the footer.
		 *
		 * @since 1.0
		 */
		public function popup_template() {
			echo '
                <div class="popup d-none">
                   	<button type="button" class="popup-close" data-dismiss="modal" aria-label="' . esc_html__( 'Close', 'twenties' ) . '">
						<span aria-hidden="true">×</span>
					</button>		
                </div>';
		}

		function get_project_data() {
			check_ajax_referer( 'popup_nonce', 'nonce' );
			
			$project_id = intval( $_POST['project_id'] );
			$project = get_post($project_id);

			if (!$project) {
				wp_send_json_error(['message' => 'Project not found'], 404);
			}

			wp_send_json_success([
				'title'   => get_the_title($project),
				'content' => apply_filters('the_content', $project->post_content)
			]);
		}
	}
endif;

return new Twenties_Child_Jetpack();
