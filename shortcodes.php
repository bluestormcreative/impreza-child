<?php
/** SHORTCODES
* This file includes NGNG shortcodes for the child theme.
*
*/


/** Copyright shortcode
* Add an incremental copyright with the site title, for use in wp editor.
*
* @author Shannon MacMillan
*/
function ngng_do_copyright() {

	// The site's title and the current year.
	$title = get_bloginfo( 'title' );
	$date = date( 'Y' );

	echo '&copy; ' . $date . ' ' . $title . ' ';
}
add_shortcode( 'copyright', 'ngng_do_copyright' );


/** NGNG link shortcode
* Add the "web development" NGNG link, for use in editor.
*
* @author Shannon MacMillan
*/
function ngng_do_link() {

	// Add a link to the NGNG site.
	echo '<a href="' . esc_url( 'http://insightfuldevelopment.com' ) . '" target="blank">' . esc_html( 'Website Development', 'impreza-child' ) . '</a>';
}
add_shortcode( 'ngnglink', 'ngng_do_link' );
