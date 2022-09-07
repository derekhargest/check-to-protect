<?php
/**
 * Repair Location CPT Declaration
 *
 * @package mindgrub-starter-theme
 */

$singular         = 'Repair Location';
$plural           = 'Repair Locations';
$custom_post_type = 'repair-location';
$lower_plural     = 'repair locations';

$labels = array(
	'name'               => $plural,
	'singular_name'      => $singular,
	'add_new'            => 'Add ' . $singular,
	'add_new_item'       => 'Add New ' . $singular,
	'edit_item'          => 'Edit ' . $singular,
	'new_item'           => 'New ' . $singular,
	'view_item'          => 'View ' . $singular,
	'search_items'       => 'Search for ' . $plural,
	'not_found'          => 'No ' . $plural . ' Found',
	'not_found_in_trash' => 'No ' . $plural . ' Found in Trash',
	'menu_name'          => $plural,
	'back_to_items'      => 'Back to ' . $lower_plural,
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
	'menu_icon' => 'dashicons-admin-tools', // https://developer.wordpress.org/resource/dashicons/
	'capability_type' => 'post',
	'hierarchical' => true,
	'supports' => array( 'title' ),
	'taxonomies' => array(),
	'has_archive' => true
);;

register_post_type( $custom_post_type, $args );
