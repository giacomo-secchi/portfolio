<?php
/**
 * Title: Default Footer
 * Slug: twentytwentythree/footer-default
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--40)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"fontSize":"small"} -->
			<p class="has-small-font-size"><?php esc_html_e( '&copy; ' . get_bloginfo( 'name' ) . ' ' . gmdate( 'Y' ) ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:paragraph {"align":"right"} -->
		<p class="has-text-align-right">
			<?php
			/* Translators: WordPress link. */
			$wordpress_link = '<a href="' . esc_url( __( 'https://wordpress.org', 'twentytwentyfour' ) ) . '" rel="nofollow">WordPress</a>';
			echo sprintf(
				/* Translators: Designed with WordPress */
				esc_html__( 'Designed with %1$s', 'twentytwentyfour' ),
				$wordpress_link
			);
			?>
		</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
