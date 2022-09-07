<?php //phpcs:ignore
/**
 * Loads template part file
 * Duplicates functionality of get_template_part() with the added benefit of optional output buffering and the ability to pass parameters
 * NOTE: Like the original function this uses extract() to move vars into scope - user data should be carefully escaped
 *
 * @param string $slug The slug name for the generic template or sub-directory.
 * @param string $name The name of the specialised template.
 * @param bool   $echo Echo output immediately or buffered and returned.
 * @param array  $params Array of additional params to include in scope.
 */
function mg_get_template_part( $slug, $name, $echo = true, $params = array() ) {
	global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

	do_action( "get_template_part_{$slug}", $slug, $name );

	$templates = array();
	if ( isset( $name ) ) {
		$templates[] = "{$slug}/{$name}.php";
		$templates[] = "{$slug}-{$name}.php";
	}
	$templates[] = "{$slug}.php";

	$template_file = locate_template( $templates, false, false );

	// Add query vars and params to scope.
	if ( is_array( $wp_query->query_vars ) ) {
		extract( $wp_query->query_vars, EXTR_SKIP );
	}

	// Add passed parameters to scope.
	if ( is_array( $params ) ) {
		extract( $params, EXTR_SKIP );
	}

	// Buffer output and return if echo is false.
	if ( ! $echo ) {
		ob_start();
	}
	include $template_file;
	if ( ! $echo ) {
		return ob_get_clean();
	}
}

/**
 * Gets the URL of a single post with the given template name
 *
 * @param $template|string $template  The template file name ( including file extension ).
 * @return string|bool      The post object, or FALSE if no post was found
 */
function mg_get_post_by_template( $template ) {
	// Query for a single post that has the given template..
	$args = array(
		'post_type'      => 'any',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_query'     => array( //phpcs:ignore
			array(
				'key'   => '_wp_page_template',
				'value' => $template,
			),
		),
	);

	$posts = get_posts( $args );

	// If there is a result, return post object - else return FALSE.
	if ( is_array( $posts ) ) {
		return array_shift( $posts );
	} else {
		return false;
	}
}

/**
 * Debug tool - displays contents of any variable wrapped in pre tags
 *
 * @param object  $variable Variable you want to debug.
 * @param boolean $log Log error instead of output.
 */
function mg_debug( $variable, $log = false ) {
	echo $log ? '' : "<pre class='debug'>";
	if ( is_array( $variable ) || is_object( $variable ) ) {
		if ( $log ) {
			error_log( print_r( $variable, true ) ); //phpcs:ignore
		} else {
			print_r( $variable ); //phpcs:ignore
		}
	} else {
		if ( $log ) {
			error_log( $variable ); //phpcs:ignore
		} else {
			var_dump( $variable ); //phpcs:ignore
		}
	}
	echo $log ? '' : '</pre>';
}

/**
 * Get Repair Locations Ajax
 */
function get_repair_locations_ajax() {

	if ( $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : false ) { //phpcs:ignore

		$city_key         = strtolower( str_replace( array( ' ', ',' ), array( '', '-' ), $city ) );
		$repair_locations = get_repair_locations( 'detailView' );
		$location_html    = '';

		foreach ( $repair_locations[ $city_key ] as $location ) {

			$phone_formatted = mg_format_phone_number( $location['phone'] );
			$business_days   = mg_format_business_days( $location['open_days'] );

			$location_html .= '<div class="location">
						<p><strong>' . $location['business_name'] . '</strong><br>
							Email: ' . $location['contact_email'] . '<br>
							Phone: ' . $phone_formatted . '<br>
							Hours: ' . $location['open_time'] . ' – ' . $location['close_time'] . ', ' . $business_days . '
						</p>
						<p style="font-size: 1.6rem; line-height: 1.5; margin-top: 0;"><i></i>' . $location['services'] . '</p>
					</div>';
		}

		$result = wp_json_encode( $location_html );
		echo $result; //phpcs:ignore
		die();

	}

}
add_action( 'wp_ajax_get_repair_locations_ajax', 'get_repair_locations_ajax' );
add_action( 'wp_ajax_nopriv_get_repair_locations_ajax', 'get_repair_locations_ajax' );

/**
 * Build repair location array
 *
 * @param object $use 
 * @return array Returns an array for use in the Select or in the detailed display
 */
function get_repair_locations( $use ) {

	$location_array = array();

	// Get repair location cities and states.
	$args  = array(
		'post_type'      => 'repair-location',
		'posts_per_page' => -1,
	);
	$query = new WP_Query( $args );

	$repair_locations_post_id = 0;

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$repair_locations_post_id = get_the_ID();
		}
	}

	wp_reset_postdata();

	$rows = get_field( 'repair_locations', $repair_locations_post_id );

	foreach ( $rows as $row ) {
		$business_name = $row['name'];
		$contact_email = $row['email'];
		$url           = $row['url'];
		$phone         = $row['phone'];
		$city_state    = $row['citystate'];
		$open_time     = $row['open_time'];
		$close_time    = $row['close_time'];
		$open_days     = $row['open_days'];
		$services      = $row['services_blurb'];

		// create a key so that casing and spacing is not an issue..
		$city_state_key = strtolower( str_replace( array( ' ', ',' ), array( '', '-' ), $city_state ) );

		// if the city is not in the array, put it in the array. The select box should not have duplicate cities.
		if ( 'selectList' == $use && ! in_array( $city_state, $location_array ) ) {
			array_push( $location_array, $city_state );

			// If the use is for the detailed results view.
		} elseif ( 'detailView' == $use ) {

			$location = array(
				'business_name' => $business_name,
				'contact_email' => $contact_email,
				'url'           => $url,
				'phone'         => $phone,
				'open_time'     => $open_time,
				'close_time'    => $close_time,
				'open_days'     => $open_days,
				'services'      => $services,
			);

			if ( array_key_exists( $city_state_key, $location_array ) ) {
				array_push( $location_array[ $city_state_key ], $location );
			} else {
				$location_array[ $city_state_key ] = array( $location );
			}
		}
	}

	return $location_array;

}

function mg_empty_array_element_exists( $arr ) { //phpcs:ignore
	return array_search( '', $arr ) !== false;
}

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );

/**
 * Deregisters unused stylesheets.
 */
function wps_deregister_styles() {
	wp_dequeue_style( 'wp-block-library' );
}
