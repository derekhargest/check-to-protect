<?php

/**
 * Customizes the editor role
 * Adds theme option and gravity form capabilities
 */
function mg_customize_editor_role() {
	$role = get_role('editor');

	// Provides editor access to widgets and menus
	$role->add_cap('edit_theme_options');

	$role->add_cap('gravityforms_edit_forms');
	$role->add_cap('gravityforms_delete_forms');
	$role->add_cap('gravityforms_create_form');
	$role->add_cap('gravityforms_view_entries');
	$role->add_cap('gravityforms_edit_entries');
	$role->add_cap('gravityforms_delete_entries');
	$role->add_cap('gravityforms_export_entries');
	$role->add_cap('gravityforms_view_enmg_notes');
	$role->add_cap('gravityforms_edit_enmg_notes');
}
//add_filter('after_switch_theme', 'mg_customize_editor_role');