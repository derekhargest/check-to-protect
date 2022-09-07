<?php

// Override the default document title separator
function mg_document_title_separator( $sep ) {
    return '|';
}
add_filter('document_title_separator', 'mg_document_title_separator');


/**
 * Change the login page logo URL
 * @source https://nazmulahsan.me/customize-wordpress-login-page/
 */
function mg_alter_login_header_url() {
	return get_home_url();
}
add_action( 'login_headerurl', 'mg_alter_login_header_url' );


/**
 * Change the login page logo alt text
 * @source https://nazmulahsan.me/customize-wordpress-login-page/
 */
function mg_alter_login_header_title() {
	return 'Powered by Mindgrub Technologies';
}
add_action( 'login_headertitle', 'mg_alter_login_header_title' );


/**
 * Remove the additional CSS section, introduced in 4.7, from the Customizer.
 * @param $wp_customize WP_Customize_Manager
 */
function prefix_remove_css_section( $wp_customize ) {
	$wp_customize->remove_section( 'custom_css' );
}
add_action( 'customize_register', 'prefix_remove_css_section', 15 );


/**
 * Remove the featured thumbnail from certain templates
 * @param $post_type
 */
function mg_remove_thumbnail_box( $post_type ) {
	// page templates
	remove_meta_box( 'postimagediv', 'post.php', 'side' );
	remove_meta_box( 'postimagediv', 'page.php', 'side' );
	remove_meta_box( 'postimagediv', 'page-template-bulk-lookup.php', 'side' );
	remove_meta_box( 'postimagediv', 'page-template-home.php', 'side' );
	remove_meta_box( 'postimagediv', 'page-template-news-archive.php', 'side' );
	remove_meta_box( 'postimagediv', 'page-template-results.php', 'side' );
}
add_action( 'do_meta_boxes', 'mg_remove_thumbnail_box' );


/**
 * Remove default tags support for posts (News)
 */
function mg_remove_posts_tags() {
	unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'mg_remove_posts_tags');


/**
 * Hide permalinks from posts (News)
 * @see https://wordpress.stackexchange.com/questions/91037/disable-permalink-on-custom-post-type#answer-91048
 * @param $return
 * @param $post
 * @return string
 */
function mg_remove_posts_permalinks($return, $post)
{
	if( $post->post_type == 'post' ) {
		return '';
	}
	return $return;
}
//add_filter( 'get_sample_permalink_html', 'mg_remove_posts_permalinks', 10, 5 );
