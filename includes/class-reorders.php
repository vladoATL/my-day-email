<?php
namespace MYDAYEMAIL;

class Reorders
{
	public function get_users_reorders($as_objects = false)
	{
		global $wpdb;

		$options = get_option('reorderemail_options');
		$days = isset( $options['days_after_order']) ? $options['days_after_order'] : 0;
		$sql = "SELECT  fpm.meta_value AS user_firstname, lpm.meta_value AS user_lastname,
			epm.meta_value AS user_email, upm.meta_value AS user_id,   max(DATE(p.post_date)) AS last_order_date
			FROM $wpdb->posts AS p
			JOIN $wpdb->users AS u ON u.ID = p.post_author
			JOIN $wpdb->postmeta AS fpm ON fpm.post_id = p.ID AND fpm.meta_key = '_billing_first_name'
			JOIN $wpdb->postmeta AS lpm ON lpm.post_id = p.ID AND lpm.meta_key = '_billing_last_name'
			JOIN $wpdb->postmeta AS epm ON epm.post_id = p.ID AND epm.meta_key = '_billing_email'
			JOIN $wpdb->postmeta AS upm ON upm.post_id = p.ID AND upm.meta_key = '_customer_user'
			WHERE p.post_type = 'shop_order' AND p.post_status = 'wc-completed'
			GROUP BY upm.meta_value
			HAVING  MAX( DATE(p.post_date)) = ADDDATE(CURRENT_DATE( ), -{$days})
			ORDER BY MAX( p.post_date) DESC  ";
			
			if ($as_objects) {
				$result = $wpdb->get_results($sql, OBJECT);
			} else {
				$result = $wpdb->get_results($sql, ARRAY_A);
			}
		

		return $result;
	}
	
	function reorderemail_event_setup()
	{
		$options = get_option('reorderemail_options');
		$success = false;
		if ( !empty($options['enabled']) && '1' == $options['enabled'] ) {
			$str_reorerday =  date('Y-m-d');
			$dateValue = strtotime($str_reorerday);
			$m = intval(date("m", $dateValue));
			$d = intval(date("d", $dateValue));
			$funcs = new EmailFunctions('reorderemail');
			$users = $this->get_users_reorders(true);
			
			foreach ($users as $user) {

				$success = $funcs->mydayemail_create($user);
			}
			$funcs->mydayemail_delete_expired();
		}
	}	
}
?>