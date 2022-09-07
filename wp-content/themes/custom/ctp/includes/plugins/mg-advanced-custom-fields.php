<?php

/**
 * Retrieves the url of an image uploaded via an ACF image field
 * TODO: Add support for return types other than array
 *
 * @param string $name the name of the ACF field - assume default return of image array is used
 * @param string $size the size of the image to be retrieved
 * @return bool|mixed the image url ( defaults to original size )
 */
function mg_get_acf_image_src( $name, $size = 'thumbnail' ) {
	// Return false if ACF is not active
	if( !function_exists( 'get_field' ) )
		return false;

	// Assume default of image array is used
	$image_array = ( get_row() ) ? get_sub_field( $name ) : get_field( $name );
	
	return mg_get_image_src_from_array( $image_array, $size );
}

/**
 * Echos the url of an image uploaded via an ACF image field
 * @param string $name the name of the ACF field - assume default return of image object is used
 * @param string $size The size of the image to be retrieved
 */
function mg_the_acf_image_src( $name, $size = 'thumbnail' ) {
	echo mg_get_acf_image_src( $name, $size );
}

/**
 * Add a general theme options page
 */
if( function_exists( 'acf_add_options_sub_page' ) ) {

	acf_add_options_sub_page(
		array(
			'page_title'	=> 'Global Settings',
			'menu_title'	=> 'Global Settings',
			'parent_slug'	=> 'themes.php'
		)
	);
}
