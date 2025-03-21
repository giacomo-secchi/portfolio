<?php
/**
 * Title: Main navigation
 * Slug: blankspace/nav-main
 */
?>

<!-- wp:navigation {"overlayMenu":"never","className":"main-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","justifyContent":"left","orientation":"vertical"},"setCascadingProperties":true,"ariaLabel":"<?php esc_attr_e( 'Main Menu', 'personal-website' ); ?>"} -->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'About Me', 'personal-website' ); ?>","url":"<?php echo esc_url( home_url( 'about/' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Portfolio', 'personal-website' ); ?>","url":"<?php echo esc_url( home_url( 'portfolio/' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Resume', 'personal-website' ); ?>","opensInNewTab":true,"url":"https://resume.giacomosecchi.com/","kind":"custom"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Servizi Offerti', 'personal-website' ); ?>","url":"<?php echo esc_url( home_url( 'servizi/' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Blog', 'personal-website' ); ?>","url":"<?php echo esc_url( home_url( 'blog/' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'WritePoetry', 'personal-website' ); ?>","opensInNewTab":true,"type":"page","url":"https://write-poetry.com/","kind":"custom"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Get in Touch', 'personal-website' ); ?>","type":"custom","url":"<?php echo esc_url( home_url( 'contact-me/' ) ); ?>","kind":"post-type","isTopLevelLink":true} /-->
<!-- /wp:navigation -->



