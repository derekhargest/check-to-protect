<?php
// $vin = $_POST['vin'];

// Add to calendar functionality is utilizing AddEvent's 'Add to Calendar button'
// https://www.addevent.com/add-to-calendar-button
$add_to_calendar = get_field( 'tab2_add_to_calendar' );

$days                    = $add_to_calendar['days'];
$title                   = $add_to_calendar['event_title'];
$description             = $add_to_calendar['event_description'];
$button_text             = $add_to_calendar['call_to_action'];
$check_back_date         = date( 'Y/m/d', strtotime('+' . $add_to_calendar['days'] . ' days') );

/**
 * Date formatting friendly to both Google/Outlook calendar systems
 *
 * @see https://stackoverflow.com/questions/37335415/link-to-add-all-day-event-to-google-calendar
 *
 * > If you don't put a time in the link it seems to mark the event as all day, for example: 20160627/20160627
 * > For a 2 day event it would look like (notice you have to add one extra day):
 */
$url_date_format        = date( 'Ymd', strtotime('+' . $add_to_calendar['days'] . ' days') );
/* Required to keep Google Calendar event on one day */
$next_day = date('Ymd', strtotime("+1 day", strtotime($url_date_format)));


// Google Calendar
$google_query_string     = http_build_query([
	'action' => 'TEMPLATE',
	'dates' => $url_date_format . '/' . $next_day,
	'text' => $title,
	'details' => $description,
]);

// Outlook.com Calender
$outlook_query_string    = http_build_query([
	'path' => '/calendar/action/compose',
	'startdt' => $url_date_format,
	'enddt' => $url_date_format,
	'allday' => 'true',
	'subject' => $title,
	'body' => $description,
]);

?>

<div class="recalls__no-results">
	<p><?= get_field( 'tab2_message' ); ?></p>

	<?php if ( false === mg_empty_array_element_exists($add_to_calendar) ) : // If none of the add to calendar fields are empty then display the add to calendar button. ?>
		<div class="add-to-calendar">
			<a class="button yellow" data-toggle-active=".add-to-calendar__buttons"><?= $button_text; ?> <i class="fa fa-calendar" aria-hidden="true"></i></a>
			<ul class="add-to-calendar__buttons">
				<li class="add-to-calendar__google">
					<a href="https://www.google.com/calendar/event?<?= $google_query_string ?>" target="_blank">Google Calendar</a>
				</li>
				<li class="add-to-calendar__outlook">
					<a href="https://outlook.live.com/owa/?<?= $outlook_query_string ?>" target="_blank">Outlook.com</a>
				</li>
				<li class="add-to-calendar__ics">
					<form method="post" action="<?= get_template_directory_uri(); ?>/includes/_download-ics.php">
						<?php # Previous hardcoded solution for all-day events wasn't working. Instead the date_end value has been removed and the format of date_start forced to date format rather than DateTime in 'vendor/class-ics.php' ?>
						<input type="hidden" name="date_start" value="<?= $check_back_date; ?>">
						<input type="hidden" name="description" value="<?= $description; ?>. ">
						<input type="hidden" name="summary" value="<?= $title; ?>">
						<input type="hidden" name="url" value="<?= home_url(); ?>">
						<input type="submit" value="<?= $button_text; ?> (.ics)">
					</form>
				</li>
			</ul>
		</div>
	<?php endif; ?>
</div>
