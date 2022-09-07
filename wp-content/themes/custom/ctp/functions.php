<?php //phpcs:ignore
/**
 * Form Vin Search
 *
 * @package  WordPress
 */
function mg_theme_setup() {
	// Enable support for post thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Add custom image sizes.
	add_image_size( 'vin_how_to_image', 455, 535, false );

	// Enable support for automated page titles.
	add_theme_support( 'title-tag' );

	// Enable support for HTML5 markup..
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', 'gallery', 'caption' ) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Enable admin to set custom background images in 'appearance > background'.
	// add_theme_support('custom-background');.

	// Add WYSIWYG editor stylesheet.
	add_editor_style( '/dist/styles/editor-styles.min.css' );

	// Register commonly used menus.
	register_nav_menus( 
		array(
			'header' => 'Header Navigation',
			'footer' => 'Footer Navigation',
		)
	);

	// Cleanup Head.
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	// Include custom post types, custom taxonomies, and general includes.
	$includes = array_merge(
		glob( get_theme_root() . '/' . get_template() . '/taxonomies/*.php' ), // All taxonomies.
		glob( get_theme_root() . '/' . get_template() . '/types/*.php' ), // All custom post types.
		glob( get_theme_root() . '/' . get_template() . '/includes/*.php' ) // All includes.
	);

	// Ignore files starting with an underscore.
	if ( $includes ) {
		foreach ( $includes as $include ) {
			$exploded_path = explode( '/', $include );

			$filename = end( $exploded_path );
			if ( strpos( $filename, '_' ) !== 0 ) {
				include_once $include;
			}
		}
	}
}
add_action( 'after_setup_theme', 'mg_theme_setup' );

/**
 * Advanced Custom Fields Options function
 * Always fetch an Options field value from the default language
 */
function cl_acf_set_language() {
	return acf_get_setting( 'default_language' );
}

function get_global_option( $name ) { //phpcs:ignore
	add_filter( 'acf/settings/current_language', 'cl_acf_set_language', 100 );
	$option = get_field( $name, 'option' );
	remove_filter( 'acf/settings/current_language', 'cl_acf_set_language', 100 );
	return $option;
}

add_action( 'wp', 'post_pw_sess_expire' );
function post_pw_sess_expire() { //phpcs:ignore
	if ( isset( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] ) ) {
		// Setting a time of 0 in setcookie() forces the cookie to expire with the session.
		// setcookie('wp-postpass_' . COOKIEHASH, '', 0, COOKIEPATH);.
		setcookie( 'wp-postpass_' . COOKIEHASH, $_COOKIE[ 'wp-postpass_' . COOKIEHASH ], time() + 7200, COOKIEPATH ); //phpcs:ignore
	}
}
