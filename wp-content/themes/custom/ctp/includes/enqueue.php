<?php //phpcs:ignore

/**
 * Front End CSS
 */
function mg_load_styles() {
	wp_enqueue_style( 'theme-styles', get_bloginfo( 'template_url' ) . '/dist/styles/app-styles.min.css', array(), false, 'screen' );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
}
add_action( 'wp_enqueue_scripts', 'mg_load_styles' );


/**
 * Front End JS
 */
function mg_load_scripts() {
	// Remove the default jquery that is bundled with WordPress and replace it with an up to date version from Google's API library.
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), null, false );

	// Theme Script.
	wp_enqueue_script( 'theme-scripts', get_bloginfo( 'template_url' ) . '/dist/scripts/app-scripts.min.js', array(), false, true );

	// WordPress Scripts.
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );

	// Dynamic URLs for use in scripts.
	wp_localize_script( 'theme-scripts', 'urls', array(
		'base'  => get_bloginfo( 'url' ),
		'theme' => get_bloginfo( 'template_url' ),
		'ajax'  => admin_url( 'admin-ajax.php ')
	) );

	// Initialize vars for JS.
	wp_localize_script( 'theme-scripts', 'info', array( /* IDs, etc. */ ) );
}
add_action( 'wp_enqueue_scripts', 'mg_load_scripts' );


/**
 * Admin CSS (includes wp-login)
 */
function mg_load_admin_styles() {
	wp_enqueue_style(
		'admin',
		get_bloginfo( 'template_url' ) . '/dist/styles/admin-styles.min.css'
	);
}
add_action( 'admin_enqueue_scripts', 'mg_load_admin_styles' );
add_action( 'login_enqueue_scripts', 'mg_load_admin_styles' );


/**
 * Admin JS
 */
function mg_load_admin_scripts() {
	wp_enqueue_script( 'admin', get_bloginfo('template_url') . '/dist/scripts/admin-scripts.min.js' );
}
//add_action('admin_enqueue_scripts', 'mg_load_admin_scripts');


/**
 * Appends a version number to each JS and CSS asset containing the time the file was last updated to automatically bust caching
 */
function mg_bust_asset_cache( $target_url ) {
	$url = parse_url( $target_url );

	if ( isset( $url['query'] ) && strpos( $url['query'], 'ver=' ) !== false && substr( $url['path'], 0, 19 ) === '/wp-content/themes/' ) {
		// Wrap in a try/catch block in case for stat failure.
		try {
			// Replace the "ver" variable with the modification time of the file.
			$target_url = add_query_arg( 'ver', filemtime( ABSPATH . $url['path'] ), $target_url );
		} catch ( \Exception $e ) {
			// Remove the "ver" variable in case of failure.
			$target_url = remove_query_arg( 'ver', $target_url );
		}
	}
	return $target_url;
}
add_filter( 'style_loader_src', 'mg_bust_asset_cache', 9999 );
add_filter( 'script_loader_src', 'mg_bust_asset_cache', 9999 );


/**
 * Add async attribute to selected scripts
 * http://matthewhorne.me/defer-async-wordpress-scripts/
 */
function mg_add_script_async_attribute( $tag, $handle ) {
	// add script handles to the array below.
	$scripts = array( 'theme-scripts' );

	foreach( $scripts as $script ) {
		if ( $script === $handle ) {
			$tag = str_replace( ' src', ' async="async" src', $tag );
		}
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'mg_add_script_async_attribute', 10, 2 );
