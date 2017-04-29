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
add_filter('acf/settings/show_admin', '__return_false');


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
 * Add the banner image after the fixed header.
 * The banner image and optins should be handled by an ACF options page or hardcoded here.
 * @return string The banner and top optin markup.
 *
 * @author Shannon MacMillan
 */
 function bsc_do_banner_image() {

	 // If we don't have ACF installed, get out of here to avoid a fatal error.
	 if ( !class_exists( 'acf' ) ) {
		 return false;
	 }

	 // Get the post so we can grab our custom fields.
	 global $post;

	 // Get all the page-specific and global custom fields.
	 $imageOrBannerGlobal = get_field( 'image_or_banner', 'option' );
	 $imageOrBannerPage = get_field( 'image_or_banner_page' );
	 $bannerContentGlobal = get_field( 'banner_content', 'option' );
	 $bannerContentPage = get_field( 'banner_content_page' );
	 $hideBanner = get_field( 'hide_banner_image' );
	 $bannerPage = get_field( 'banner_image_page' );
	 $bannerGlobal = get_field( 'banner_image', 'option' );

	 // Start our object buffer to hold the output.
	 ob_start();

	 // If the "hide banner" checkbox is not checked...
	 if ( empty( $hideBanner) ) :
		 // If the page we're on has banner content set, show that...
		 if ( $imageOrBannerPage == 'content' ) : ?>

			 <div id="banner-content" class="banner">
				 <?php echo $bannerContentPage; ?>
			 </div><!-- #banner-optin -->

		 <?php elseif ( $imageOrBannerGlobal == 'content' ) :
			 // Otherwise if the global banner has content set, show that... ?>
			 <div id="banner-content" class="banner">
				 <?php echo $bannerContentGlobal; ?>
			 </div><!-- #banner-optin -->

		 <?php elseif ( $imageOrBannerPage == 'image' ) :
			 // If we have a single page image set, show that... ?>
			 <div id="banner" class="banner">
				 <img src="<?php echo $bannerPage['url']; ?>" alt="<?php echo $bannerPage['alt']; ?>">
			 </div><!-- #banner -->

		 <?php elseif ( $imageOrBannerGlobal == 'image' ):
			 // Otherwise show the global banner if it exists. ?>
			 <div id="banner" class="banner">
				 <img src="<?php echo $bannerGlobal['url']; ?>" alt="<?php echo $bannerGlobal['alt']; ?>">
			 </div><!-- #banner -->
		 <?php endif; ?>
	 <?php endif; ?>
	 <?php

	 // Print our object buffer markup to the page.
	 echo ob_get_clean();
 }
 add_action( 'us_after_template:templates/l-header', 'bsc_do_banner_image', 12 );



 /**
 * Add thetop optin after the fixed header.
 * The optins should be handled by an ACF options page or hardcoded here.
 * @return string The banner and top optin markup.
 *
 * @author Shannon MacMillan
 */
 function bsc_do_banner_optin() {

	 // If we don't have ACF installed, get out of here to avoid a fatal error.
	 if ( !class_exists( 'acf' ) ) {
		 return false;
	 }

	 // Get the post so we can grab our custom fields.
	 global $post;

	 $hideOptinPage = get_field( 'show_hide_top_optin_page' );
	 $hideOptinGlobal = get_field( 'show_hide_top_optin', 'option' );
	 $optinLeft = get_field( 'top_optin_left', 'option' );
	 $optinRight = get_field( 'top_optin_right', 'option' );
	 $optinBgColorPage = get_field( 'top_optin_background_color_page' );
	 $optinBgColorGlobal = get_field( 'top_optin_background_color', 'option' );

	 if ( $optinBgColorPage ) {
		 $bgColor = $optinBgColorPage;
	 } else {
		 $bgColor = $optinBgColorGlobal;
	 }

	 // Start our object buffer to hold the output.
	 ob_start();
	 ?>
	 <?php
		 // If the global "hide optin" checkbox is not checked...
		 if ( empty( $hideOptinGlobal ) ):
			 // And the page-specific "hide optin" checkbox is not checked...
			 if ( empty( $hideOptinPage ) ):
				 // Show the global optin. ?>
				 <div id="top-optin" class="optin top-optin" style="background-color: <?php echo $bgColor; ?>;">
					 <div class="l-section-h g-cols">
						 <div class="vc_col-sm-6 optin-left">
							 <?php echo $optinLeft; ?>
						 </div>
						 <div class="vc_col-sm-6 optin-right">
							 <?php echo $optinRight; ?>
						 </div>
					 </div><!-- .container -->
				 </div><!-- #top-optin -->
			 <?php endif; ?>
		 <?php endif; ?>
	 <?php
	 // Print our object buffer markup to the page.
	 echo ob_get_clean();
 }
 add_action( 'us_after_template:templates/l-header', 'bsc_do_banner_optin', 15 );
