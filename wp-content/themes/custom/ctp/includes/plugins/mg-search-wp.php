<?php

/**
 * Allows the Search WP indexer to work when basic HTTP auth is enabled ( in Pantheon environments )
 * https://searchwp.com/docs/hooks/searchwp_basic_auth_creds/
 */
function mg_searchwp_basic_auth_creds() {
    $credentials = array( 
        'username' => 'Mindgrub', // the HTTP BASIC AUTH username
        'password' => 'MG'  // the HTTP BASIC AUTH password
    );
    
    return $credentials;
}
add_filter( 'searchwp_basic_auth_creds', 'mg_searchwp_basic_auth_creds' );