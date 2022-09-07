<?php
/**
 * Retrieves custom post items
 * @return array An array of post objects
 */
function mg_get_faceted_posts( $post_type ) {
	$args = array(
		'post_type'		    => $post_type,
		'posts_per_page'	=> get_option( 'posts_per_page' ),
		'facetwp'			=> true
	);

	$query = new WP_Query( $args );

	return $query->posts;
}


/**
 * Generate pagination links
 * @usage echo mg_pagination();
 */
function mg_pagination() {
	global $wp_query;

	$big = 999999999; // need an unlikely integer

	return paginate_links( array(
		'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' 		=> '?paged=%#%',
		'current' 		=> max( 1, get_query_var( 'paged' ) ),
		'total' 		=> $wp_query->max_num_pages,
		'mid_size' 		=> 2,
		'prev_text' 	=> '&nbsp;',
		'next_text' 	=> '&nbsp;',
	) );
}

/**
 * Format phone number input
 * @usage provide a number argument and this removes all non numberic chars and returns a formatted string.
 * You can also provide a separator for the formatting (ie: . or -)
 *
 * @param string|int $unformatted_number
 * @param string $separator
 * @return string A string of a formatted phone number
 */
function mg_format_phone_number($unformatted_number, $separator = '-') {
	$numbers_only = preg_replace('/[^0-9]/', '', $unformatted_number);
	$offset = 0;

	if (substr($numbers_only,0,1) === '1')
		$offset = 1;

	$area_code = substr($numbers_only,$offset,3);
	$offset+= 3;
	$prefix = substr($numbers_only,$offset,3);
	$offset+= 3;
	$line_number = substr($numbers_only,$offset,4);


	return $area_code . $separator . $prefix . $separator . $line_number;

}

/**
 * Returns the days of the week that a business is open in a pleasant format.
 * @param $days
 *
 * @return string|array
 */
function mg_format_business_days($days) {

	$business_days = [];

	if (in_array('monday',$days) &&
		in_array('tuesday',$days) &&
		in_array('wednesday',$days) &&
		in_array('thursday',$days) &&
		in_array('friday',$days)) {

		return 'M-F';
	}

	foreach ($days as $day) {

		array_push($business_days,ucfirst(substr($day,0,3)));

	}

	return implode(', ',$business_days);

}
