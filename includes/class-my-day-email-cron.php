<?php

add_action( 'namedayemail_cron', 'namedayemail_event' );
add_action( 'birthdayemail_cron', 'birthdayemail_event' );
add_action( 'reorderemail_cron', 'reorderemail_event' );

function namedayemail_event(){
	$celebrating =  new \MYDAYEMAIL\Namedays();
	$celebrating->namedayemail_event_setup();	
}

function namedayemail_run_cron() {
	mydayemail_run_cron_setup("namedayemail");
}

function birthdayemail_event()
{
	$celebrating =  new \MYDAYEMAIL\Birthdays();
	$celebrating->birthdayemail_event_setup();
}

function birthdayemail_run_cron() {
	mydayemail_run_cron_setup("birthdayemail");
}

function reorderemail_event()
{
	$coupons =  new \MYDAYEMAIL\Reorders();
	$coupons->reorderemail_event_setup();
}

function reorderemail_run_cron()
{
	mydayemail_run_cron_setup("reorderemail");
}

function mydayemail_run_cron_setup($type)
{
	$options = get_option($type . '_options');
	$logs = new \MYDAYEMAIL\EmailFunctions($type);

	if (isset($options['enabled'])) {
		wp_clear_scheduled_hook($type . '_cron' );
		if (isset($options['send_time'])) {
			$tm = strtotime(time_utc($options['send_time']));
		} else {
			$tm = time();
		}		
		//$res = wp_schedule_event( $tm, 'daily', $type . '_cron' );
		$res = wp_reschedule_event( $tm, 'daily', $type . '_cron' );
		if ($res == 1 )
			$logs->mydayemail_add_log("Cron scheduled " . date("T H:i", $tm));
			else
				$logs->mydayemail_add_log("Cron scheduling error" );
			
	} else {
		wp_clear_scheduled_hook( $type . '_cron' );
		$logs->mydayemail_add_log("Cron removed" );
	}	
}

function time_utc($dateTime)
{
	$timezone_from = wp_timezone_string();
	$newDateTime = new \DateTime($dateTime, new \DateTimeZone($timezone_from));
	if (!$newDateTime instanceof DateTime)
		return "";
	$newDateTime->setTimezone(new DateTimeZone("UTC"));
	$dateTimeUTC = $newDateTime->format("H:i");
	return $dateTimeUTC;
}

?>