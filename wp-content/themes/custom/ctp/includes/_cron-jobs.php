<?php

/**
 * Add cron job
 */
if( !wp_get_schedule( "cron_job_name" ) ) {
	// wp_schedule_event( time() + 604800, "weekly", "cron_job_name" );
}

/**
 * Adds weekly cron scheduling option
 */
function mg_add_weekly_schedule( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 604800, // seconds in a week
		'display' => __('Once Weekly')
	);
	return $schedules;
}
add_filter('cron_schedules', 'mg_add_weekly_schedule');