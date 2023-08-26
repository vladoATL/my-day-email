<?php

// don't call the file directly
if ( !defined( 'ABSPATH' ) )
	exit();

class EmailFunctions
{
	protected $type;
	protected $options_name;
	protected $options_array;
	
	public function __construct($type)
	{
		$this->type = $type;
		$this->options_name = $type . '_options';
		$this->options_array = get_option($this->options_name);
	}
	
	public function getBirthdaySentMetaKey()
	{
		return 'dob-coupon-sent';
	}	
	
	function mydayemail_set_sent($user, $istest = false)
	{		
		if (! $istest) {
			$id = $user->user_id;

			$check = get_user_meta( $id, $this->getBirthdaySentMetaKey(), true );
			if ( empty( $check )) {
				add_user_meta($id, $this->getBirthdaySentMetaKey(), date('Y'));
			} else {
				update_user_meta($id, $this->getBirthdaySentMetaKey(), date('Y'));
			}			
			
		}
	}
		
	function mydayemail_create($user, $istest = false)
	{
		$success = true;
		$options = $this->options_array;
		$subject_user = $options['subject'];
		$html_body = $options['email_body'];
		$from_name = $options['from_name'];
		$from_address = $options['from_address'];
		$header  = $options['header'];	
		
		if ($istest == true) {
			$headers_user   = $this->mydayemail_headers($from_name, $from_address,"", "", true);
			$email = $options['bcc_address'];
		} else {
			$headers_user   = $this->mydayemail_headers($from_name, $from_address,"", "", false);
			$email = $user->user_email;
		}		
		
		if (str_contains($html_body, '{coupon}') && (array)$user) {
			$coupon = $this->mydayemail_get_unique_coupon($user);
			
			if (empty($coupon)) {
				$this->mydayemail_add_log("No available coupons to create.");
				$success = false;
				return $success;
			}
			$html_body = str_replace('{coupon}',$coupon,$html_body);
		}
	
		$html_body = $this->mydayemail_replace_placeholders($html_body, $user, $options);
		$subject_user = $this->mydayemail_replace_placeholders($subject_user, $user, $options);

		
		if ((!str_contains(get_home_url(), 'test') && !str_contains(get_home_url(), 'stage') && $options['test'] != 1) || $istest == true) {
			if (is_email($email)) {

				if ($options['wc_template'] == 1) {
					$this->mydayemail_send_wc_email_html($subject_user, $email, $html_body, $header);
				} else {
					$sendmail_user = wp_mail( $email, $subject_user, $html_body, $headers_user );
				}

				if ($istest == true) {
					$this->mydayemail_add_log("Test email sent to" . ': ' . $email . ' coupon: ' . $coupon ) ;
					$success = false;
				} else {
					$this->mydayemail_add_log("Email sent to" . ': ' . $email . ' coupon: ' . $coupon  ) ;
				}
			} else {
				$this->mydayemail_add_log("Trying to send to incorrect or missing email address"  . ': ' . $email ) ;
				$success = false;
			}
		} else {
			if (isset($options['bcc_address'])) {
				$admin_email = $options['bcc_address'];
			} else {
				$admin_email = get_bloginfo('admin_email');
			}

			if ($options['wc_template'] == 1) {
				$this->mydayemail_send_wc_email_html($subject_user, $admin_email, $html_body, $header);
			} else {
				$sendmail_user = wp_mail( $admin_email, $subject_user, $html_body, $headers_user );
			}
			$this->mydayemail_add_log("Email has been sent as Test to"  . ' ' . $admin_email . " instead of to " . $email) ;
			$success = false;
		}
		return $success;
	}

	function mydayemail_replace_placeholders($content, $user, $options)
	{
		$days_before = is_numeric($options['days_before']) ? $options['days_before'] : 0;
		$inflection = new Inflection();
		$replaced_text = str_replace(
		array(
		'{site_name}',
		'{site_url}',
		'{site_name_url}',
		'{expires_in_days}',
		'{expires}',
		'{my_day_date}',
		'{percent}',
		'{fname}',
		'{fname5}',
		'{lname}',
		'{products_cnt}',
		'{email}',
		),
		array(
		get_option( 'blogname' ),
		home_url(),
		'<a href=' . home_url() . '>' . get_option( 'blogname' ) . '</a>',
		$options['expires'],
		date('d.m.Y', strtotime('+' . $options['expires'] . ' days')),
		date('d.m.Y', strtotime('+' . $days_before . ' days')),
		$options['coupon_amount'],
		ucfirst(strtolower($user->user_firstname)),
		$inflection->inflect(ucfirst(strtolower($user->user_firstname)))[5],
		ucfirst(strtolower($user->user_lastname)),
		$options['max_products'],
		strtolower($user-> user_email),
		),
		$content
		);
		return $replaced_text;
	}

	function mydayemail_add_log($entry)
	{
		$options = get_option('mydayemail_options');
		if ($options['enable_logs'] == "1") {

			if ( is_array( $entry ) ) {
				$entry = json_encode( $entry );
			}
			$entry =$this->type . ": " . current_time( 'mysql' ) . " " .  $entry  ;
			$options = get_option('mydayemail_logs');
			
			if (empty($options)) {
				add_option( 'mydayemail_logs', array('logs'	=>	$entry) );
			} else {
				$log = $options['logs'];
				update_option( 'mydayemail_logs',array('logs'	=>	$log . "\n" .  $entry) );
			}
		}
	}

	function mydayemail_headers($from_name, $from_address, $email_cc, $email_bcc, $istest = false)
	{
		$headers_user   = array();
		$headers_user[] = 'MIME-Version: 1.0' . "\r\n";
		$headers_user[] = 'Content-type:text/html;charset=UTF-8' . "\r\n";
		$headers_user[] = 'From: ' . $from_name . ' <' . $from_address . '>' . "\r\n";

		if ( ! empty( $email_cc ) ) {
			$headers_user[] = 'Cc: ' . $email_cc . "\r\n";
		}
		if ( ! empty( $email_bcc ) && $istest) {
			$headers_user[] = 'Bcc: ' . $email_bcc . "\r\n";
		}
		return $headers_user;
	}

	function mydayemail_get_unique_coupon($user)
	{
		global $wpdb;
		$options = $this->options_array;			
		$coupon_codes = $wpdb->get_col("SELECT post_name FROM $wpdb->posts WHERE post_type = 'shop_coupon'");		
		$characters = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
		$char_length = $options['characters'];

		$stp = 0;
		$max_stp = 10000;
		for ( $i = 0; $i < 1; $i++ ) {
			$generated_code  = substr( str_shuffle( $characters ), 0, $char_length );
			// Check if the generated code doesn't exist yet
			if ($stp > $max_stp)
				return;
			if ( in_array( $generated_code, $coupon_codes ) ) {
				$stp++;
				$i--; // continue the loop and generate a new code
			} else {
				break; // stop the loop: The generated coupon code doesn't exist already
			}
		}
		$amount = $options['coupon_amount']; // Amount
		$discount = $options['disc_type'];

		switch ($discount) {
			case 1:
				$discount_type = 'percent';
				break;
			case 2:
				$discount_type = 'fixed_cart';
				break;
			case 3:
				$discount_type = 'fixed_product';
				break;
			case 4:
				$discount_type = 'percent_product';
				break;
		}

		$expiration_date = $options['expires'] + 1;
		$expiry_date   = date('Y-m-d', strtotime('+' . $expiration_date . ' days'));
		$max_products = isset( $options['max_products']) ? $options['max_products'] : 0;
		$description = $options['description'];
		$description = $this->mydayemail_replace_placeholders($description, $user, $options);
		$free_shipping = isset( $options['free_shipping']) ? "yes" : 'no';
		$individual_use = isset( $options['individual_use']) ? "yes" : 'no';
		$exclude_discounted = isset( $options['exclude_discounted']) ? "yes" : 'no';
		$minimum_amount = $options['minimum_amount'];
		$maximum_amount = $options['maximum_amount'];

		$coupon = array(
		'post_title' => $generated_code,
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type'     => 'shop_coupon',
		'post_excerpt' => $description, // __( 'Name day', 'my-day-email' ) . ' ' . $user->user_firstname  . ' ' . $user->user_email,
		);
		//$this->mydayemail_add_log("wp_insert_post" . ': '  . $user->ID  . ' ' . implode($coupon) ) ;
		$new_coupon_id = wp_insert_post( $coupon );

		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
		if (isset($options['exclude_prods']))
			update_post_meta( $new_coupon_id, 'exclude_product_ids', implode(",", $options['exclude_prods'] )  );
		if (isset($options['only_products']))
			update_post_meta( $new_coupon_id, 'product_ids', implode(",", $options['only_products']) );
		if (isset($options['exclude_cats']))
			update_post_meta( $new_coupon_id, 'exclude_product_categories',  $options['exclude_cats'] );
		if (isset($options['only_cats']))
			update_post_meta( $new_coupon_id, 'product_categories', implode(",", $options['only_cats']) );
		update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_discounted );
		update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
		update_post_meta( $new_coupon_id, 'usage_limit', '1' );
		update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', $max_products );
		update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
		update_post_meta( $new_coupon_id, 'date_expires', $expiry_date );
		update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
		update_post_meta( $new_coupon_id, 'customer_email', array($user->user_email) );

		if (  is_plugin_active( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) ) {
			update_post_meta( $new_coupon_id, '_acfw_enable_date_range_schedules', 'yes' );
			update_post_meta( $new_coupon_id, '_acfw_allowed_customers', $user->id );
			update_post_meta( $new_coupon_id, '_acfw_schedule_end', $expiry_date );
		}

		if (isset($options['category']))
			$cat_id = $this->mydayemail_coupon_category($new_coupon_id, $options['category']);
		return $generated_code;
	}

	function mydayemail_coupon_category($new_coupon_id, $cat_slug)
	{
		global $wpdb;
		if ( ! is_plugin_active( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) )
			return 0;
			$term_id = $this->mydayemail_get_cat_slug_id($cat_slug) ;
		if (! empty($term_id)) {
			$sql = "INSERT INTO {$wpdb->prefix}term_relationships
								SET object_id = $new_coupon_id, term_taxonomy_id =
								(SELECT pt.term_id FROM {$wpdb->prefix}term_taxonomy AS pt
								INNER JOIN {$wpdb->prefix}terms AS t ON t.term_id = pt.term_id
								WHERE t.slug =  '$cat_slug')";

			$results = $wpdb->query($sql);
		} else {
			$results = 0;
		}
		return $results;
	}

	function mydayemail_get_cat_slug_id($cat_slug)
	{
		global $wpdb;
		$sql = "SELECT pt.term_id FROM {$wpdb->prefix}term_taxonomy AS pt
				INNER JOIN {$wpdb->prefix}terms AS t ON t.term_id = pt.term_id
				WHERE t.slug =  '$cat_slug'";
		$term_id = $wpdb->query($sql);
		return $term_id;
	}

	function mydayemail_send_wc_email_html($subject, $recipient, $body, $heading = false )
	{
		$template = 'emails/nameday.php';
		$mailer = WC()->mailer();
		$options = $this->options_array;
		$headers = $this->mydayemail_headers($options['from_name'], $options['from_address'], $options['cc_address'], $options['bcc_address'] );
		$content = wc_get_template_html( $template, array(
		'email_heading' => $heading,
		'sent_to_admin' => false,
		'plain_text'    => false,
		'email'         => $mailer,
		'content'  => $body,
		),'',  plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/' );
		$mailer->send( $recipient, $subject, $content, $headers );
	}

	function mydayemail_delete_expired()
	{
		global $wpdb;
		$options = get_option('mydayemail_options');
		$days_delete =  ((isset($options['days_delete']) && !empty($options['days_delete'])) ? $options['days_delete'] : 0);
		if ($days_delete == 0)
			return;
		$sql = "SELECT ID FROM $wpdb->posts AS p
				JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id AND pm.meta_key = 'date_expires'
				JOIN $wpdb->postmeta AS pmu ON p.ID = pmu.post_id AND pmu.meta_key = 'usage_count'
				WHERE post_type = 'shop_coupon'
				AND pm.meta_value > 0
				AND pmu.meta_value = 0
				AND pm.meta_value + (" . $days_delete . "*86400) < UNIX_TIMESTAMP()
				ORDER BY pm.meta_value desc";
		$coupon_ids = $wpdb->get_col($sql);
		$count = count($coupon_ids);

		if (sizeof($coupon_ids) == 0)
			return;

		$where_in = implode(",", $coupon_ids );

		$sql_pm = "DELETE FROM $wpdb->postmeta WHERE post_id IN (" . $where_in . ")";
		$sql_p = "DELETE FROM $wpdb->posts WHERE ID IN (" . $where_in . ")";
		$sql_tr = "DELETE FROM $wpdb->term_relationships WHERE object_id IN (" . $where_in . ")";

		$wpdb->get_results($sql_pm);
		$wpdb->get_results($sql_p);
		$wpdb->get_results($sql_tr);

		$this->mydayemail_add_log(sprintf(_n(  '%s expired unused coupons were deleted.', $count,  'my-day-email'), $count));
	}

	function namedayemail_get_next_names()
	{
		$m = intval(date('m'));
		$d = intval(date('d'));
		$options = $this->options_array;
		$prior_days =$options['days_before'];
		if (! isset($prior_days)) {
			$prior_days = 0;
		}
		$nd = new NameDays();
		$names = $nd->get_names_for_day($d + $prior_days, $m , false );
		if (empty($names))
			return;
		$names = implode(',',array_unique(explode(',', $names)));

		$d = $d + $prior_days ;

		if ($prior_days == 0) {
			return  sprintf(__(  'Today %s is Name Day celebrated by',  'my-day-email'), $d . '.' . $m . '.') . " : " . $names;
		} else {
			return  $d . "." . $m . ". - " . sprintf( _n( 'Tomorrow is Name Day celebrated by', 'In %s days is Name Day celebrated by', $prior_days, 'my-day-email' ), $prior_days )  . " " . $names;
		}
	}
}
?>