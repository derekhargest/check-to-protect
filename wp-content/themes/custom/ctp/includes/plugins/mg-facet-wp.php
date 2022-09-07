<?php

/**
 * Instruct facetwp to look for a query var to trigger filtering on a non-archive or non-search template
 */
function mg_facetwp_is_main_query( $is_main_query, $query ) {
    if ( isset( $query->query_vars['facetwp'] ) ) {
        $is_main_query = true;
    }
    
    return $is_main_query;
}
add_filter( 'facetwp_is_main_query', 'mg_facetwp_is_main_query', 10, 2 );

/**
 * Remove default facetwp CSS
 */
function mg_facetwp_remove_assets( $assets ) {
    unset( $assets['front.css'] );
    return $assets;
}
add_filter( 'facetwp_assets', 'mg_facetwp_remove_assets' );