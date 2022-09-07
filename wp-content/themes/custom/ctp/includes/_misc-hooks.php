<?php

/**
 * Alters the permalink text appended to the end of an excerpt
 *
 * @param string $more Default excerpt permalink text
 * @return string Permalink to be appended to excerpt
 */
function mg_custom_excerpt_more( $more ) {
	global $post;
	return ' [...]  <a href="'. get_permalink($post->ID) . '">Read More</a>';
}
//add_filter('excerpt_more', 'mg_custom_excerpt_more');


/**
 * Alters the word length of an excerpt
 *
 * @param int $length Length of the excerpt in words
 * @return int Custom length of the excerpt in words
 */
function mg_custom_excerpt_length( $length ) {
	return 50;
}
//add_filter('excerpt_length', 'mg_custom_excerpt_length');


/**
 * Allows manipulation of the excerpt
 *
 * @param string The unmodified excerpt
 * @return string The modified excerpt
 */
function mg_custom_excerpt( $excerpt ) {
	global $post;
	return $excerpt . '...';
}
//add_filter('wp_trim_excerpt', 'mg_custom_excerpt');