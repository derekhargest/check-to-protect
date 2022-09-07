<?php

/**
 * Remove unnecessary dashboard meta boxes
 */
function mg_remove_dashboard_widgets() {
    // remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');    // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');     // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');            // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');          // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');        // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');              // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');            // Other WordPress News
}
add_action('wp_dashboard_setup', 'mg_remove_dashboard_widgets' );

/**
 * Unregisters unnecessary default widgets
 */
function mg_unregister_default_wp_widgets() {
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Categories');
	// unregister_widget('WP_Widget_Text');
	unregister_widget('WP_Nav_Menu_Widget');
	unregister_widget('GFWidget');
}
add_action('widgets_init', 'mg_unregister_default_wp_widgets');

/**
 * Hide admin pages that are not used
 */
function mg_remove_menu_pages() {
	remove_menu_page( 'edit-comments.php' );    // Comments
	//remove_menu_page( 'edit.php' );             // Posts
}
add_action('admin_menu', 'mg_remove_menu_pages');

/**
 * Change admin menu order
 */
function mg_change_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;

	return array(
		 'index.php', // Dashboard
		 'separator1', // First separator
		 'edit.php?post_type=page', // Pages
		 'edit.php',                // Posts
		 'edit.php?post_type=make', // Makes
		 'upload.php',              // Media
		// 'gf_edit_forms', // Gravity Forms
		// 'edit-comments.php', // Comments
		 'separator2', // Second separator
		 'themes.php', // Appearance
		 'plugins.php', // Plugins
		 'users.php', // Users
		 'tools.php', // Tools
		 'options-general.php', // Settings
		 'separator-last', // Last separator
	);
}
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', 'mg_change_menu_order');

/**
 * Removes admin bar items
 */
function mg_remove_admin_bar_items() {
    global $wp_admin_bar;

    $wp_admin_bar->remove_menu( 'comments' );
	//$wp_admin_bar->remove_node( 'new-post' );
}
add_action('wp_before_admin_bar_render', 'mg_remove_admin_bar_items');

/**
 * Remove post_tags and categories from admin
 */
function mg_unregister_default_taxonomies() {
	register_taxonomy('category', array() );
	register_taxonomy('post_tag', array() );
}
//add_action('init', 'mg_unregister_default_taxonomies');

/*
 * Change the instances of "Post(s)" to something else
 */
function mg_change_post_label() {
	global $menu;
	global $submenu;

//	mg_debug($submenu);

	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0]  = 'Articles';
	$submenu['edit.php'][10][0] = 'Add Article';
	$submenu['edit.php'][15][0] = 'Article Categories';
	$submenu['edit.php'][16][0] = 'Article Tags';
}
function mg_change_post_object() {
	global $wp_post_types;

	$labels = &$wp_post_types['post']->labels;
	$labels->name               = 'News';
	$labels->singular_name      = 'News';
	$labels->add_new            = 'Create an Article';
	$labels->add_new_item       = 'Create an Article';
	$labels->edit_item          = 'Edit Article';
	$labels->new_item           = 'Articles';
	$labels->view_item          = 'View Article';
	$labels->search_items       = 'Search for Article(s)';
	$labels->not_found          = 'No News Articles found';
	$labels->not_found_in_trash = 'No News Articles found in Trash';
	$labels->all_items          = 'All News Articles';
	$labels->menu_name          = 'News';
	$labels->name_admin_bar     = 'News Article';
}
add_action( 'admin_menu', 'mg_change_post_label' );
add_action( 'init', 'mg_change_post_object' );
