<?php
/**
 * clean up the actions and filters that fill up the <head>
 * @see http://cubiq.org/clean-up-and-optimize-wordpress-for-your-next-theme
 */
function wp_requests_cleanup() {
	remove_action( 'wp_head', 'wp_generator' );                                    // removes the “generator” meta tag
	remove_action( 'wp_head', 'wlwmanifest_link' );                                // (outdated) removes the “wlwmanifest” link
	remove_action( 'wp_head', 'rsd_link' );                                        // (outdated) remove RSD
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );                            // remove the generation of the shortlink
	remove_action('wp_head', 'index_rel_link');
	remove_action( 'wp_print_styles', 'print_emoji_styles' );                      // removes /wp-includes/js/wp-emoji.release.min.js
	remove_action( 'wp_head','feed_links', 2 );                             // remove rss feed links
	remove_action( 'wp_head','feed_links_extra', 3 );                       // disable automatic feeds
	remove_action('wp_head', 'start_post_rel_link', 10);
	remove_action('wp_head', 'parent_post_rel_link', 10);
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );      // removes a link to the next and previous post
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );          // removes emoji styles and JS
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );              // avoid setting useless cookies
	remove_action( 'wp_print_styles', 'print_emoji_styles' );                      // removes emoji styles and JS
	add_filter( 'the_generator', '__return_false' );                                                    // removes the generator name from the RSS feeds
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'show_admin_bar','__return_false' );                                                    // removes the administrator’s bar while logged in
}
//add_action( 'after_setup_theme', 'wp_requests_cleanup' );


/**
 * remove JSON API links in header html
 * @see https://wordpress.stackexchange.com/questions/211467/remove-json-api-links-in-header-html
 */
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );


/**
 * use simple classes for supported page templates
 * @param $classes
 * @return array
 */
function mg_add_custom_body_class( $classes ) {
	global $post;

	// if a custom template, output a custom class to the body (whitelisted in another function)
	if ( isset( $post ) ) {
		$template_name   = get_page_template_slug( $post->ID );
		$template_single = get_single_template();

		// home
		if ( $template_name == 'page-template-home.php' ) {
			$classes[] = 'home';
		}

		// vin lookup results
		if ( $template_name == 'page-template-results.php' ) {
			$classes[] = 'vin-results';
		}

		// protected vin lookup results
		if ( $template_name == 'page-template-results-protected.php' ) {
			$classes[] = 'vin-results';
		}

		// bulk vin lookup
		if ( $template_name == 'page-template-bulk-lookup.php' ) {
			$classes[] = 'vin-bulk-lookup';
		}

		// bulk vin lookup
		if ( $template_name == 'single.php' && !is_404() ) {
			$classes[] = 'blog';
		}

		// if using the default "page" template, inject 'default' class to body
		$template = get_post_meta( $post->ID, '_wp_page_template', true );

		if ( $template == 'default' ) {
			$classes[] = 'default';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'mg_add_custom_body_class', 9, 2 );


/**
 * clean up the body_class(); output
 * @see http://wordpress.stackexchange.com/questions/15850/remove-classes-from-body-class
 */
function wp_cleanup_body_class( $wp_classes, $extra_classes ) {
	// list of the only WP generated classes allowed
	$whitelist = array(
		'home',
		'blog',
		'search',
		'default',
		'vin-bulk-lookup',
		'news',
		'vin-results',
		'archive',
		'error404',
		'logged-in',
		'admin-bar'
	);
	// whitelist result: (comment if you want to blacklist classes)
	$wp_classes = array_intersect( $wp_classes, $whitelist );

	// list of the only WP generated classes that are not allowed
	//$blacklist = array( 'home', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' );

	// blacklist result: (uncomment if you want to blacklist classes)
	//$wp_classes = array_diff( $wp_classes, $blacklist );

	// add the extra classes back untouched
	return array_merge( $wp_classes, (array) $extra_classes );
}
add_filter( 'body_class', 'wp_cleanup_body_class', 10, 2 );


/**
 * remove the meta="generator" from being produced
 * @see https://stackoverflow.com/questions/16335347/wordpress-how-do-i-remove-meta-generator-tags
 */
function remove_meta_generators( $html ) {
	$pattern = '/<meta name(.*)=(.*)"generator"(.*)>/i';
	$html = preg_replace( $pattern, '', $html );

	return $html;
}
function clean_meta_generators( $html ) {
	ob_start( 'remove_meta_generators' );
}
add_action( 'get_header', 'clean_meta_generators', 100 );
add_action( 'wp_footer', function () { ob_end_flush(); }, 100);


/**
 * remove the jquery migrate introduced in the WP Core 4.5 update
 * @see http://wordpress.stackexchange.com/questions/224661/annoying-jqmigrate-migrate-is-in-console-after-update-to-wp-4-5
 */
add_action( 'wp_default_scripts', function( $scripts ) {
	if ( ! empty( $scripts->registered[ 'jquery' ] ) ) {
		$jquery_dependencies = $scripts->registered[ 'jquery' ]->deps;

		$scripts->registered[ 'jquery' ]->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
	}
} );


/**
 * remove the calls for the asset: /wp-includes/js/wp.embed.min.js
 * @see http://wordpress.stackexchange.com/questions/15850/remove-classes-from-body-class
 */
function my_deregister_scripts() {
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );


/**
 * remove the generation of the /wp-json/ file
 * @see https://thomas.vanhoutte.be/miniblog/remove-api-w-org-rest-api-from-wordpress-header/
 */
function remove_wpjson () {
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
//add_action( 'after_setup_theme', 'remove_wpjson' );


/**
 * remove the unwanted attachment pages created for media files
 */
function attachment_redirect() {
	global $wp_query, $post;

	if ( is_attachment() ) {
		$post_parent = $post->post_parent;
		if ( $post_parent ) {
			wp_redirect( get_permalink($post->post_parent), 301 );
			exit;
		}
		$wp_query->set_404();
		return;
	}

	if ( is_author() || is_date() ) {
		$wp_query->set_404();
	}
}
add_action( 'template_redirect', 'attachment_redirect' );


/**
 * Remove the shortlink from being generated within the <head>
 */
//remove_action('wp_head', 'wp_shortlink_wp_head', 10);


/**
 * Prevents the issue of having to refresh after logging in due to cookie error
 *
 * @see https://wordpress.org/support/topic/cookie-error-when-logging-in/
 * @see https://www.steckinsights.com/how-to-fix-a-cookies-blocked-error-on-wordpress-admin-dashboard/
 */
setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH ) {
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
}
