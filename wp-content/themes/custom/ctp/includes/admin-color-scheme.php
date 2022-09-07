<?php

/**
 * Mindgrub admin theme for WordPress
 */
function mg_add_mg_schemes() {
	$theme = get_stylesheet_directory_uri();

	wp_admin_css_color(
		'Mindgrub',
		'Mindgrub',
		$theme . '/dist/styles/admin-mg.min.css',
		array(
			'#2c215a',
			'#ff8200',
			'#aa0000',
			'#333333',
		)
	);
}
add_action( 'admin_init', 'mg_add_mg_schemes' );


/**
 * client admin theme for WordPress
 */
function mg_add_client_schemes() {
	$theme = get_stylesheet_directory_uri();

	wp_admin_css_color(
		'CTP',
		'CTP',
		$theme . '/dist/styles/admin-client.min.css',
		array(
			'#4a4a4a',
			'#ffd602',
			'#fffcf8',
			'#be1e2d',
		)
	);
}
add_action( 'admin_init', 'mg_add_client_schemes' );
