<?php
/** ACF COMPONENTS
* This file houses optional Advanced Custom Fields theme components.
* To use this file, un-comment line 63 in functions.php.
*
* Components include a global options page, global banner image or content area to place a slideshow,
* page-specific banner/slideshow areas, and a global optin bar.
*
* To use these fields, import the  acf .json export file included in this directory into the Pro plugin.
* /


/**
* Hide ACF menu in admin
* Comment this filter out to edit the custom fields.
*
* @author Shannon MacMillan
*/
//add_filter('acf/settings/show_admin', '__return_false');


/**
* Add NGNG specific theme options page
* Needs Advanced Custom Fields Pro plugin to function.
*
* @author Shannon MacMillan
*/
if ( function_exists('acf_add_options_page') ) {

   acf_add_options_page( 'NGNG Options' );

}


 /**
 * Add either a global header or a page-specific header.
 *
 * @return string The markup for the header.
 *
 * @author Shannon MacMillan
 */
function bsc_do_ngng_header() {

	// If we don't have ACF installed, get out of here to avoid a fatal error.
	if ( ! class_exists( 'acf' ) ) {
		 return false;
	}

	 // Get the post so we can grab our custom fields.
	 global $post;

	 // Custom header options for this page.
	 $hide_banner = get_field( 'hide_page_header' );
	 $choose_header = get_field( 'choose_page_header' );

	 // Get the title of our specific header so we can check against the global.
	 $page_specific_header_title = $choose_header->post_title;

	 // Let's grab our global header if we have one.
	 $global_header = get_page_by_title( 'Global Header', OBJECT, 'page_header' );

	if ( true != $hide_banner ) {

		// If we've chosen a page-specific header...
		if ( $choose_header && 'Global Header' !== $page_specific_header_title ) {

			// We're going to display the specific page header.
			$header_post = $choose_header;

		} elseif ( $global_header ) {

			//  We're going to display the global header post.
			$header_post = $global_header;
		}

		// Override $post with our header data.
		setup_postdata( $header_post );

		?>
			<div id="banner" class="banner">
				<?php the_content(); ?>
			</div><!-- .banner -->

		<?php

		wp_reset_postdata();
	}
}
add_action( 'us_after_template:templates/l-header', 'bsc_do_ngng_header', 12 );
