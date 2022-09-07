<?php

/**
 * Plugin customizations & helper functions
 * Loads individual plugin files when a class associated with that plugin exists
 */
function mg_customize_plugins() {
    $plugins = array(
        'acf' => 'advanced-custom-fields',
        'FacetWP' => 'facet-wp',
        'SearchWP' => 'search-wp',
        'GFForms' => 'gravity-forms',
        'WPSEO_Options' => 'yoast-seo'
    );

    foreach( $plugins as $class => $filename ) {
        if( class_exists( $class ) ) {
            require_once dirname(__FILE__) . '/plugins/mg-' . $filename . '.php';
        }
    }
}
add_action('init', 'mg_customize_plugins');

/**
 * Register the required/recommended plugins for this theme using the TGM_Plugin_Activation class
 * http://tgmpluginactivation.com/
 */
require_once dirname(__FILE__) . '/vendor/class-tgm-plugin-activation.php';

function mg_require_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'          => 'Advanced Custom Fields PRO',
            'slug'		    => 'advanced-custom-fields-pro',
            'source'        => 'http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=b3JkZXJfaWQ9NDQ2OTZ8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE0LTExLTE5IDIyOjU2OjUz',
            'required'      => true
        ),
        array(
            'name'          => 'Admin Columns',
            'slug'          => 'codepress-admin-columns',
            'required'      => false
        ),
        array(
            'name'          => 'Gravity Forms',
            'slug'          => 'gravity-forms',
            'source'        => 'http://s3.amazonaws.com/gravityforms/releases/gravityforms_2.2.5.21.zip?AWSAccessKeyId=1603BBK66770VCSCJSG2&Expires=1516937467&Signature=Eh1mnvyl%2B%2FybGGpXCXTT5bHNg1A%3D',
            'external_url'  => 'https://www.gravityforms.com',
            'required'      => false
        ),
        array(
            'name'          => 'Gravity Forms - CSS Selector',
            'slug'          => 'gravitywp-css-selector',
            'required'      => false
        ),
        array(
            'name'          => 'Nice Search',
            'slug'          => 'nice-search',
            'required'      => false
        ),
        array(
            'name'          => 'Regenerate Thumbnails',
            'slug'          => 'regenerate-thumbnails',
            'required'      => false
        ),
        array(
            'name'          => 'Safe Redirect Manager',
            'slug'          => 'safe-redirect-manager',
            'required'      => false
        ),
        array(
            'name'          => 'Simple Page Ordering',
            'slug'          => 'simple-page-ordering',
            'required'      => false
        ),
        array(
            'name'          => 'TRY SEO',
            'slug'          => 'try-seo',
            'source'        => 'https://github.com/taeo920/try-seo/archive/master.zip',
            'external_url'  => 'https://github.com/taeo920/try-seo',
            'required'      => false
        ),
        array(
            'name'          => 'WP Redis',
            'slug'          => 'wp-redis',
            'required'      => false
        ),
        array(
            'name'          => 'WP Term Order',
            'slug'          => 'wp-term-order',
            'required'      => false
        ),
        array(
            'name'          => 'WordPress Native PHP Sessions',
            'slug'          => 'wp-native-php-sessions',
            'required'      => false
        )
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     */
    $config = array(
        'id'           => 'mg-tgmpa',              // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'plugins.php',           // Parent menu slug.
        'capability'   => 'activate_plugins',      // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                    // Automatically activate plugins after installation or not.
        'message'      => ''         			   // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}
add_action('tgmpa_register', 'mg_require_plugins');