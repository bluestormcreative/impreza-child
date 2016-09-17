<?php
/* Custom functions code goes here. */


/**
* Enqueue our custom styles amd scripts.
*
* @author Shannon MacMillan
*/
function bsc_enqueue_scripts() {
    wp_enqueue_script( 'bsc-scripts', get_stylesheet_directory_uri() .'/js/ngng-scripts.js', array( 'jquery' ), false, true );
}
add_action( 'wp_enqueue_scripts', 'bsc_enqueue_scripts' );

// Set WordPress SEO metabox priority to low so it is below other metaboxes on editor screens.
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
* Add the banner image and top optin after the fixed header.
* The banner image and optins should be handled by an ACF options page or hardcoded here.
* @return string The banner and top optin markup.
*
* @author Shannon MacMillan
*/
function bsc_do_banner_image() {

    ob_start();
    ?>
    <div id="banner" class="banner">Banner image here.</div><!-- #banner -->
    <div id="top-optin" class="optin top-optin">
        <div class="l-section-h g-cols">
            <div class="vc_col-sm-6 optin-left">
                <h3>This is the optin text</h3>
            </div>
            <div class="vc_col-sm-6 optin-right">
                <form>
                  <label class="screenreader" for="name">Name</label>
                  <input type="text" name="FNAME" value="Name" id="mce-FNAME" onblur=" if (this.value == '' ) this.value = 'Name'; " onfocus="if(this.value == 'Name') this.value = '';" >
                  <label class="screenreader">Email</label>
                  <input type="email" name="EMAIL" value="Email" id="mce-EMAIL" onblur=" if (this.value == '' ) this.value = 'Email'; " onfocus="if(this.value == 'Email') this.value = '';" >
                  <input type="submit" value="Sign Up"></input>
                </form>
            </div>
        </div><!-- .container -->
    </div><!-- #top-optin -->
    <?php
    echo ob_get_clean();
}
add_action( 'us_after_template:templates/l-header', 'bsc_do_banner_image' );
