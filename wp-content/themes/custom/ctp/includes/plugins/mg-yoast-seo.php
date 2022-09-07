<?php

/**
 * Forces the Yoast SEO panel to bottom of page
 */
function mg_move_yoast_to_bottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'mg_move_yoast_to_bottom');