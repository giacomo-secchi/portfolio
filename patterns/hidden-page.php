<?php
/**
 * Title: Page
 * Slug: personal-website/hidden-page
 * Inserter: no
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

?>

<!-- wp:group {"className":"main-container","layout":{"type":"constrained"}} -->
<div class="wp-block-group main-container">
<!-- wp:create-block/router {"align":"wide"} -->
<div class="wp-block-create-block-router alignwide">


<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|90","margin":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--80);margin-bottom:var(--wp--preset--spacing--80)">
			<!-- wp:column {"verticalAlignment":"stretch"} -->
			<div class="wp-block-column is-vertically-aligned-stretch">
				<!-- wp:template-part {"slug":"sidebar","area":"sidebar"} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:template-part {"slug":"main-content","area":"content"} /-->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:create-block/router -->

</div>
<!-- /wp:group -->


<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

