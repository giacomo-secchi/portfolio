<?php


// Custom Language Switcher output
add_action( 'custom_language_span_output', function ( $args, $short_language_name, $language_name, $flag_link ) {
	return '<span>' . strtoupper( $short_language_name ) . '</span>';
 }, 10, 4 );



add_filter( 'blankspace_enable_dashicons', '__return_true' );




function render_menu( $block_content, $block ) {
    // If there are no inner blocks, return the original content
    if ( ! isset( $block['blockName'] ) || empty( $block['blockName'] ) ) {
        return $block_content;
    }

	// Check if the inner block is a 'core/navigation' block
	if ( 'core/navigation' === $block['blockName'] ) {

		// Use WP_HTML_Tag_Processor to process the inner block's content
		$nav = new WP_HTML_Tag_Processor( $block_content );


        // Find all <li> tags and apply the modifications
        while ( $nav->next_tag( 'li' ) ) {

            // Add the attribute for the dynamic class to the <li>
            $nav->set_attribute( 'data-wp-class--current-menu-item', 'state.isCurrentPage' );

            // Move to the <a> tag inside the <li>
            if ( $nav->next_tag('a') ) {

                // Check if the link does not have target="_blank"
                if ( '_blank' !== $nav->get_attribute('target') ) {
                    // Add the custom attribute for click handling
                    $nav->set_attribute( 'data-wp-on--click', 'actions.navigate' );
                }
            }
        }
		// Return the updated HTML content
		return $nav->get_updated_html();
    }
}

add_filter( 'render_block_core/navigation', 'render_menu', 10, 2 );

function render_menu_item( $block_content, $block ) {
    // If there are no inner blocks, return the original content
    if ( ! isset( $block['blockName'] ) || empty( $block['blockName'] ) ) {
        return $block_content;
    }

	// Check if the inner block is a 'core/navigation' block
	if ( 'core/navigation-link' === $block['blockName'] ) {

		// Use WP_HTML_Tag_Processor to process the inner block's content
		$nav = new WP_HTML_Tag_Processor( $block_content );


        // Find all <li> tags and apply the modifications
        while ( $nav->next_tag( 'li' ) ) {

            // Add the attribute for the dynamic class to the <li>
            // $nav->set_attribute( 'data-wp-class--current-menu-item', 'state.isCurrentPage' );
			// TODO: trovare l'ID di ogni pagina in base all'url del link (che è l'unico parametro univoco)
            // $nav->set_attribute( 'data-wp-context--page-id', '1' );

            // Move to the <a> tag inside the <li>
            if ( $nav->next_tag('a') ) {

                // Check if the link does not have target="_blank"
                if ( '_blank' !== $nav->get_attribute('target') ) {
                    // Add the custom attribute for click handling
                    $nav->set_attribute( 'data-wp-on--click', 'actions.navigate' );
                }
            }
        }
		// Return the updated HTML content
		return $nav->get_updated_html();
    }
}

add_filter( 'render_block_core/navigation-link', 'render_menu_item', 10, 2 );
