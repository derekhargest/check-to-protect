<?php
/**
 * Allows the creation of a custom template with custom re-write rules
 * For example you can add a rule to rewrite /flickr-photoset/{id} to /index.php?photoset={id}
 * When the photoset id is present we can then load a custom template
 */

/**
 * Adds custom query variable
 */
function mg_custom_template_query_vars() {
	global $wp;
	$wp->add_query_var('custom');
}
add_action('init', 'mg_custom_template_query_vars');

/**
 * Adds custom rules to wp_rewrite for pretty URLs
 */
function mg_custom_template_rewrite_rules( $rules ) {
	$custom['custom/(.+)$'] = 'index.php?custom=$matches[1]';
	return $custom + $rules;
}
add_filter('rewrite_rules_array', 'mg_custom_template_rewrite_rules');

/**
 * Load custom template when custom query variable is present
 */
function mg_custom_template( $template ) {
	if( get_query_var('custom') ) {
		add_filter('body_class', 'mg_custom_template_body_class');
		return TEMPLATEPATH . "/single-custom.php";
	} else {
		return $template;
	}
}
add_filter('template_include', 'mg_custom_template');

/**
 * Adds custom body class when custom template is loaded
 */
function mg_custom_template_body_class( $classes ) {
	$classes[] = 'custom_template';
	return $classes;
}