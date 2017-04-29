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
* Add visibilty field to Gravity Forms labels
*
* @author Shannon MacMillan
*/
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );


/**
* Optionally add ACF components.
*
* Includes global banner/content area, global optin bar,
* page-specific banner/content area, and NGNG options page.
* To use, un-comment this line and import the acf/.json file into the Pro plugin.
*
* @author Shannon MacMillan
*/
include_once( 'acf/acf-components.php' );


/**
* Add NGNG specific shortcodes file
*
* @author Shannon MacMillan
*/
include_once( 'shortcodes.php' );


/**
* Add post name to body classes for use in styling
*
* @author Shannon MacMillan
*/
function ngng_class_names( $classes ) {
	global $post;

	// Add 'post_name' to the $classes array.
	$classes[] = $post->post_name;

	// Return the $classes array.
	return $classes;
}
add_filter( 'body_class', 'ngng_class_names' );
