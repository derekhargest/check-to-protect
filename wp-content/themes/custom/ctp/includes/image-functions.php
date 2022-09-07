<?php

/**
 * Retrieves the correctly sized image source from an array produced by wp_prepare_attachment_for_js()
 *
 * @param (array) $image_array Image array produced by wp_prepare_attachment_for_js() function
 * @param (string) $size The size of the image to be retrieved
 * @return (string) The image url ( defaults to original size )
 */
function mg_get_image_src_from_array( $image_array, $size = 'thumbnail' ) {
	// Return false if field is empty or type other than array is being used
	if( !$image_array || !is_array( $image_array ) )
		return false;

	// Get the correct size url if found - otherwise get the original image url
	if( array_key_exists( $size, $image_array['sizes'] ) ) {
		$image_url = $image_array['sizes'][$size];
	} else {
		$image_url = $image_array['url'];
	}

	return $image_url;
}

/**
 * Echos the correctly sized image source from an array produced by wp_prepare_attachment_for_js()
 *
 * @param (array) $image_array Image array produced by wp_prepare_attachment_for_js() function
 * @param (string) $size The size of the image to be retrieved
 * @return (string) The image url ( defaults to original size )
 */
function mg_the_image_src_from_array( $image_array, $size = 'thumbnail' ) {
	echo mg_get_image_src_from_array( $image_array, $size );
}

/**
 * Retrieves the url of the post thumbnail
 *
 * @param (string) $size The size of the thumbnail to be retrieved
 * @return (string) The post thumbnail url
 */
function mg_get_post_thumbnail_src( $size = 'thumbnail' ) {
	global $post;
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
	return $image[0];
}

/**
 * Echos the url of the post thumbnail
 *
 * @param (string) $size The size of the thumbnail to be retrieved
 * @return (string) The post thumbnail url
 */
function mg_the_post_thumbnail_src( $size = 'thumbnail' ) {
	echo mg_get_post_thumbnail_src( $size );
}

/**
 * Get all of the images attached to the current post
 * Excludes the post thumbnail
 *
 * @param string $size Desired image size
 * @return array
 */
function mg_get_post_images_src( $size = 'thumbnail' ) {
	global $post;
	
	$images = get_children( array('exclude' => get_post_thumbnail_id( $post->ID ), 'post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order') );
		
	$results = array();

	if ( $images ) {
		foreach ( $images as $image ) {
			// get the correct image html for the selected size
			$results[] = wp_get_attachment_image_src( $image->ID, $size );
		}
	}
	
	return $results;
}