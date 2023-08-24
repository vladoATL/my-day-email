<?php
add_action( 'namedayemail_cron', 'namedayemail_event' );

function namedayemail_event(){
	$options = get_option('namedayemail_options'); 
	if ( !empty($options['enabled']) && '1' == $options['enabled'] ) {
		$str_nameday =  date('Y-m-d',strtotime('+' . $options['days_before'] . ' day'));
		$dateValue = strtotime($str_nameday);
		$m = intval(date("m", $dateValue));
		$d = intval(date("d", $dateValue));
	
		$celebrating =  new NameDays();
		$users = $celebrating->get_celebrating_users($d,$m);
		foreach ($users as $user){
			namedayemail_create($user);
		}	
		namedayemail_delete_expired();	
	}
}


function namedayemail_run_cron()
{
	wp_clear_scheduled_hook( 'namedayemail_cron' );

	$options = get_option('namedayemail_options');
	if (isset($options['enabled'])) {
		
		if ( ! wp_next_scheduled( 'namedayemail_cron' ) ) {
			add_action( 'namedayemail_cron', 'namedayemail_event' );
			if (isset($options['send_time'])) {
				$tm = strtotime(time_utc($options['send_time']));
			} else {
				$tm = time();
			}
			wp_schedule_event( $tm, 'daily', 'namedayemail_cron' );
		}
	}
}

?>