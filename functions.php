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


/**
 * Add the Headers post type so we can use VC for headers
 * Generated using Custom Post Types UI plugin.
 *
 * @author Shannon MacMillan
 */
function cptui_register_my_cpts_page_header() {

	/**
	 * Post Type: Headers.
	 */

	$labels = array(
		'name' => __( 'Headers', '' ),
		'singular_name' => __( 'Header', '' ),
	);

	$args = array(
		'label' => __( 'Headers', '' ),
		'labels' => $labels,
		'description' => '',
		'public' => true,
		'publicly_queryable' => false,
		'show_ui' => true,
		'show_in_rest' => false,
		'rest_base' => '',
		'has_archive' => false,
		'show_in_menu' => 'themes.php',
		'show_in_menu_string' => 'themes.php',
		'menu_position' => 10,
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'page_header', 'with_front' => true ),
		'query_var' => true,
		'supports' => array( 'title', 'editor' ),
	);

	register_post_type( 'page_header', $args );
}
add_action( 'init', 'cptui_register_my_cpts_page_header' );


/**
 * Create a single global header post on theme activation
 *
 * @author Shannon MacMillan
 */
function ngng_create_global_header_post() {

	// If the theme has been activated and we are in the admin.
	if ( isset( $_GET['activated'] ) && is_admin() ) {

		// Set up global post title and slug.
		$new_header_title = 'Global Header';
		$new_header_slug = 'global-header';

		// Check to make sure this post doesn't already exist.
		$header_check = get_page_by_title( $new_header_title, OBJECT, 'page_header' );

		// Create an array of our post parameters.
		$new_header = array(
				'post_type' => 'page_header',
				'post_title' => $new_header_title,
				'post_slug' => $new_header_slug,
				'post_status' => 'publish',
				'post_author' => 1,
		);

		// If our check is not set, create the new header post.
		if ( ! isset( $header_check ) ) {
			wp_insert_post( $new_header );
		}
	}
}
add_action( 'after_switch_theme', 'ngng_create_global_header_post' );
