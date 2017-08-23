<?php
/* Custom functions code goes here. */


/**
* Enqueue our custom styles amd scripts.
*
* @author Shannon MacMillan
*/
function ngng_enqueue_scripts() {
	// Add a jQuery script to handle the fixed header responsively.
	wp_enqueue_script( 'bsc-scripts', get_stylesheet_directory_uri() .'/js/ngng-scripts.js', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'ngng_enqueue_scripts' );


/**
* Set WordPress SEO metabox priority to low so it is below other metaboxes on editor screens.
*
* @author Shannon MacMillan
*/
function ngng_position_wpseo_metabox() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'ngng_position_wpseo_metabox' );


/**
* Remove Yoast SEO notifications from the admin.
* To stop clients from freaking out about unneeded notices.
*
* @author Shannon MacMillan
*/
function ngng_remove_wpseo_notifications() {

	if ( ! class_exists( 'Yoast_Notification_Center' ) ) {
		return;
	}

	remove_action( 'admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
	remove_action( 'all_admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
}
add_action( 'init', 'ngng_remove_wpseo_notifications' );


/**
* Hide ACF Custom Fields menu item in admin.
* This hides the custom fields admin area. If you'd like to use this area to edit custom fields, comment out line 52 below.
*
* @author Shannon MacMillan
*/
//add_filter( 'acf/settings/show_admin', '__return_false' );


/**
* Add visibilty field to Gravity Forms labels
* This lets you set a label on the back end that is invisible on the front end. Go to the field's "appearance" tab and set the visibiltiy dropdown to "admin only".
*
* @author Shannon MacMillan
*/
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );


/**
* Add NGNG specific shortcodes file.
* This includes the [copyright] and [ngnglink] shortcodes as of 4/29/2017.
*
* @author Shannon MacMillan
*/
include_once( 'shortcodes.php' );


/**
* Add post name to body classes for use in styling.
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
 * @author WDS / Shannon MacMillan
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
 * Create a single global header post on theme activation.
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


/**
 * Remove Yoast SEO box from header cpts.
 *
 * @author Shannon MacMillan
 */
function ngng_remove_yoast_metabox_page_headers() {
	remove_meta_box( 'wpseo_meta', 'page_header', 'normal' );
}
add_action( 'add_meta_boxes', 'ngng_remove_yoast_metabox_page_headers', 11 );


/**
 * Remove Revolution slider box from header cpts.
 *
 * @author Shannon MacMillan
 */
function ngng_remove_revslider_metabox_page_headers() {
	remove_meta_box( 'mymetabox_revslider_0', 'page_header', 'normal' );
}
add_action( 'add_meta_boxes', 'ngng_remove_revslider_metabox_page_headers', 11 );
