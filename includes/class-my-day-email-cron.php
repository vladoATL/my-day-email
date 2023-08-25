<?php
add_action( 'namedayemail_cron', 'namedayemail_event' );
add_action( 'birthdayemail_cron', 'birthdayemail_event' );

function namedayemail_event(){
	namedayemail_event_setup("namedayemail");
}

function namedayemail_event_setup($type)
{
	$options = get_option($type .'_options');
	if ( !empty($options['enabled']) && '1' == $options['enabled'] ) {
		$str_nameday =  date('Y-m-d',strtotime('+' . $options['days_before'] . ' day'));
		$dateValue = strtotime($str_nameday);
		$m = intval(date("m", $dateValue));
		$d = intval(date("d", $dateValue));
		$funcs = new EmailFunctions($type);
		$celebrating =  new NameDays();
		$users = $celebrating->get_celebrating_users($d,$m);
		foreach ($users as $user) {
			$funcs->mydayemail_create($user);
		}
		$funcs->mydayemail_delete_expired();
	}
}
function namedayemail_run_cron() {
	mydayemail_run_cron_setup("namedayemail");
}

function birthdayemail_event()
{
	birthdayemail_event_setup("birthdayemail");
}
function birthdayemail_event_setup($type)
{
	$options = get_option($type .'_options');
	if ( !empty($options['enabled']) && '1' == $options['enabled'] ) {
		$str_nameday =  date('Y-m-d',strtotime('+' . $options['days_before'] . ' day'));
		$dateValue = strtotime($str_nameday);
		$m = intval(date("m", $dateValue));
		$d = intval(date("d", $dateValue));
		$funcs = new EmailFunctions($type);
		$celebrating =  new Birthdays();
		$users = $celebrating->get_celebrating_users($d,$m);
		foreach ($users as $user) {
			$funcs->mydayemail_create($user);
		}
		$funcs->mydayemail_delete_expired();
	}
}
function birthdayemail_run_cron() {
	mydayemail_run_cron_setup("birthdayemail");
}

function mydayemail_run_cron_setup($type)
{
	$options = get_option($type . '_options');
	$logs = new EmailFunctions($type);

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
	}	
}

function time_utc($dateTime)
{
	$timezone_from = wp_timezone_string();
	$newDateTime = new DateTime($dateTime, new DateTimeZone($timezone_from));
	if (!$newDateTime instanceof DateTime)
		return "";
	$newDateTime->setTimezone(new DateTimeZone("UTC"));
	$dateTimeUTC = $newDateTime->format("H:i");
	return $dateTimeUTC;
}

?>