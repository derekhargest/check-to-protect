<?php

/**
 * Enable more WYSIWYG editor buttons
 */
function try_add_mce_buttons( $buttons ) {
	// Remove 'toolbar toggle'
	unset( $buttons[ array_search('wp_adv', $buttons) ] );

	// Add custom buttons
	$buttons[] = 'btn';

	// Add 'toolbar toggle' back in at end of array
	$buttons[] = 'wp_adv';

	return $buttons;
}
add_filter('mce_buttons', 'try_add_mce_buttons', 99 );

/**
 * Add buttons to the second row of WYSIWYG editor buttons
 */
function mg_add_mce_buttons_2( $buttons ) {
	$buttons[] = 'subscript';
	$buttons[] = 'superscript';

	return $buttons;
}
add_filter('mce_buttons_2', 'mg_add_mce_buttons_2');

/**
 * Declare WYSIWYG editor plugins
 */
function mg_declare_mce_plugins($plugins) {
	$plugins['btn'] = get_bloginfo('template_url') . '/webpack/scripts/mce/mce-button.js';

	return $plugins;
}
add_filter( 'mce_external_plugins', 'mg_declare_mce_plugins' );
