<?php
class Birthdays
{
	public function get_celebrating_users($d, $m)
	{
		global $wpdb;

		$date_str = sprintf("%02d", $m) . "-" . sprintf("%02d", $d);
		$sql = "	
		SELECT m.user_id AS id, m.meta_value as dob, u.user_email AS user_email, fmu.meta_value AS user_firstname, lmu.meta_value AS user_lastname,
		TIMESTAMPDIFF(YEAR, m.meta_value, CURDATE()) AS age
		FROM {$wpdb->prefix}users  AS u
		JOIN {$wpdb->prefix}usermeta AS m ON u.ID = m.user_id AND m.meta_key = 'billing_birth_date'
		JOIN {$wpdb->prefix}usermeta AS fmu ON u.ID = fmu.user_id AND fmu.meta_key = 'billing_first_name'
		JOIN {$wpdb->prefix}usermeta AS lmu ON u.ID = lmu.user_id AND lmu.meta_key = 'billing_last_name'
		WHERE m.meta_value LIKE '%-{$date_str}'";		
					
		$result = $wpdb->get_results($sql, OBJECT);

		return $result;
	}
}
?>