<?php

/**
 * Post Type Declaration
 */
$labels = array(
	'name' => 'Slides',
	'singular_name' => 'Slide',
	'add_new' => 'Add New',
	'add_new_item' => 'Add New Slide',
	'edit_item' => 'Edit Slide',
	'new_item' => 'New Slide',
	'view_item' => 'View Slide',
	'search_items' => 'Search Slides',
	'not_found' => 'No Slides Found',
	'not_found_in_trash' => 'No Slides Found in Trash',
	'menu_name' => 'Slides'
);

$args = array(
	'labels' => $labels,
	'description' => '',
	'public' => true,
	'exclude_from_search' => false,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_nav_menus' => false,
	'show_in_menu' => true,
	'show_in_admin_bar' => true,
	'menu_position' => 10,
	'menu_icon' => 'dashicons-images-alt2', // https://developer.wordpress.org/resource/dashicons/
	'capability_type' => 'post',
	'hierarchical' => true,
	'supports' => array( 'title', 'editor', 'thumbnail' ),
	'taxonomies' => array(),
	'has_archive' => true
);

register_post_type('slide', $args );

/**
 * Custom Title Placeholder
 */
function slide_change_title_placeholder( $title ){
     $screen = get_current_screen();
     if ( $screen->post_type == 'slide' ) {
          $title = 'Enter slide title here';
     }
     return $title;
}
add_filter('enter_title_here', 'slide_change_title_placeholder');