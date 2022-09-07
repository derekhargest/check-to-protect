<?php

/**
 * Remove default link option for images
 */
function mg_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ( $image_set !== 'none' ) {
		update_option( 'image_default_link_type', 'none' );
	}
}
add_action('admin_init', 'mg_imagelink_setup', 10 );

/**
 * Add ability to upload SVGs
 */
function mg_upload_types( $existing_mimes = array() ) {
    $existing_mimes['svg'] = 'image/svg+xml';
    return $existing_mimes;
}
add_filter('upload_mimes', 'mg_upload_types');
