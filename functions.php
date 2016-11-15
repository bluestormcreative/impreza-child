<?php
/* Custom functions code goes here. */


/**
* Enqueue our custom styles amd scripts.
*
* @author Shannon MacMillan
*/
function bsc_enqueue_scripts() {
    // Add a jQuery script to handle the fixed header responsively.
    wp_enqueue_script( 'bsc-scripts', get_stylesheet_directory_uri() .'/js/ngng-scripts.js', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'bsc_enqueue_scripts' );


/**
* Set WordPress SEO metabox priority to low so it is below other metaboxes on editor screens.
*
* @author Shannon MacMillan
*/
function bsc_position_wpseo_metabox() {
   return 'low';
}
add_filter( 'wpseo_metabox_prio', 'bsc_position_wpseo_metabox' );


/**
* Remove Yoast SEO notifications from the admin
* To stop clients from freaking out about unneeded notices.
*
* @author Shannon MacMillan
*/
function bsc_remove_wpseo_notifications() {

	if ( ! class_exists( 'Yoast_Notification_Center' ) ) {
		return;
	}

	remove_action( 'admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
	remove_action( 'all_admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
}
add_action( 'init', 'bsc_remove_wpseo_notifications' );


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
    ?>

    <?php
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
* Hide ACF menu in admin
* Comment this filter out to edit the custom fields.
*
* @author Shannon MacMillan
*/
//add_filter('acf/settings/show_admin', '__return_false');


/**
* Add visibilty field to Gravity Forms labels
*
* @author Shannon MacMillan
*/
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
