<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://starlogic.net/
 * @since             1.0.0
 * @package           My_Day_Email
 *
 * @wordpress-plugin
 * Plugin Name:       My Day Email
 * Plugin URI:        https://starlogic.net/
 * Description:       Send email with a coupon to users on birthday, name day and order reorder.
 * Version:           0.2.7
 * Author:            Vlado Laco
 * Author URI:        https://starlogic.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-day-email
 * Domain Path:       /languages
 * Date of Start:	  16.8.2023
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MY_DAY_EMAIL_VERSION', '0.2.7.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-my-day-email-activator.php
 */
function activate_my_day_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-day-email-activator.php';
	My_Day_Email_Activator::activate();	
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-my-day-email-deactivator.php
 */
function deactivate_my_day_email() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-my-day-email-deactivator.php';
	My_Day_Email_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_my_day_email' );
register_deactivation_hook( __FILE__, 'deactivate_my_day_email' );
register_deactivation_hook( __FILE__, 'namedayemail_plugin_deactivation' );
register_activation_hook( __FILE__, 'namedayemail_plugin_save_defaults' );
register_activation_hook( __FILE__, 'birthdayemail_plugin_save_defaults' );



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-my-day-email.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-my-day-email-functions.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-name-day-email-inflection.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-my-day-email-cron.php';

require_once plugin_dir_path( __FILE__ ) .  'includes/class-birthdays.php';
require_once plugin_dir_path( __FILE__ ) .  'includes/class-afterorder.php';
require_once plugin_dir_path( __FILE__ ) .  'includes/class-namedays.php';
require_once plugin_dir_path( __FILE__ ) .  'includes/class-birthdayfield.php';
require_once plugin_dir_path( __FILE__ ) .  'includes/class-reorders.php';
require_once plugin_dir_path( __FILE__ ) .  'includes/class-onetimes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-calendars.php';

\MYDAYEMAIL\BirthdayField::register();


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'mydayemail_settings_link' );

function namedayemail_plugin_save_defaults() {
    	namedayemail_save_defaults(true);	
}

function birthdayemail_plugin_save_defaults()
{
	birthdayemail_save_defaults(true);
}

function birthdayemail_save_defaults($add_new = false)
{
	$current_user = wp_get_current_user();

	$option_array = array(
	'subject'	=>	_x('{fname}, here is your birthday gift','Email Subject','my-day-email') ,
	'header'  =>	_x('Congratulations','Email Header','my-day-email') ,
	'days_before'	=>	1,
	'characters' =>	7,
	'wc_template' =>	1,
	'once_year' =>	1,
	'test' =>	1,
	'send_time'  =>	'05:30',
	'expires'	=>	14,
	'from_name'	=>	get_bloginfo('name'),
	'from_address'	=>	get_bloginfo('admin_email'),
	'bcc_address' => $current_user->user_email,
	'email_footer' => '{site_name_url}',
	'disc_type' => 1,
	'description' => _x('Birthday {fname} {lname}: {email}','Coupon description','my-day-email') ,
	'coupon_amount'	=>	25,
	'email_body'	=> _x("<p style='font-size: 20px;font-weight:600;'>Have a nice birthday, {fname}!</p>
<p style='font-size: 18px;'>Take advantage of this birthday discount code:</p>
<p style='font-size: 24px;font-weight:800;'>{coupon}</p>
<p style='font-size: 18px;'>During the next {expires_in_days} days you can use it in our online store {site_name_url} and get a special discount of <strong>{percent}%</strong> on {products_cnt} non-discounted products.</p>
<p style='font-size: 18px;font-weight:600;'>ENJOY !</p>
<p style='font-size: 18px;'>The Team of {site_name}</p>
<p style='font-size: 14px;'>The coupon can only be used after logging into your account and cannot be used with other discounts. Some products are exluded from the discount.</p>" ,'Email Body', 'my-day-email'	) ,
	'category' =>	_x('birth-day','Coupon category', 'my-day-email'	) ,
	);
	if ($add_new == true) {
		add_option( 'birthdayemail_options', $option_array );
	} else {
		update_option( 'birthdayemail_options', $option_array );
	}
}

function reorderemail_save_defaults($add_new = false)
{
	$current_user = wp_get_current_user();

	$option_array = array(
	'subject'	=>	_x("{fname}, it's time to order again","Email Subject","my-day-email") ,
	'header'  =>	_x('Your discount','Email Header','my-day-email') ,
	'characters' =>	7,
	'wc_template' =>	1,
	'test' =>	1,
	'days_after_order' =>	365,
	'send_time'  =>	'03:00',
	'expires'	=>	14,
	'from_name'	=>	get_bloginfo('name'),
	'from_address'	=>	get_bloginfo('admin_email'),
	'bcc_address' => $current_user->user_email,
	'email_footer' => '{site_name_url}',
	'disc_type' => 1,
	'description' => _x('Reorder {fname} {lname}: {email}','Coupon description','my-day-email') ,
	'coupon_amount'	=>	10,
	'email_body'	=> _x("<p style='font-size: 20px;font-weight:600;'>We have a special discount for you, {fname}!</p>
<p style='font-size: 18px;'>Take advantage of this  discount code and order again:</p>
<p style='font-size: 24px;font-weight:800;'>{coupon}</p>
<p style='font-size: 18px;'>During the next {expires_in_days} days you can use it in our online store {site_name_url} and get a special discount of <strong>{percent}%</strong> on {products_cnt} non-discounted products.</p>
<p style='font-size: 18px;font-weight:600;'>ENJOY !</p>
<p style='font-size: 18px;'>The Team of {site_name}</p>
<p style='font-size: 14px;'>The coupon can only be used after logging into your account and cannot be used with other discounts. Some products are exluded from the discount.</p>" ,'Email Body', 'my-day-email'	) ,
	'category' =>	_x('reorder','Coupon category', 'my-day-email'	) ,
	);
	if ($add_new == true) {
		add_option( 'reorderemail_options', $option_array );
	} else {
		update_option( 'reorderemail_options', $option_array );
	}
}

function onetimeemail_save_defaults($add_new = false)
{
	$current_user = wp_get_current_user();

	$option_array = array(
	'subject'	=>	_x('{fname}, here is your discount coupon','Email Subject','my-day-email') ,
	'header'  =>	_x('Your discount','Email Header','my-day-email') ,
	'characters' =>	7,
	'wc_template' =>	1,
	'roles' => array('customer'),
	'test' =>	1,
	'expires'	=>	14,
	'minimum_orders' => 1,
	'from_name'	=>	get_bloginfo('name'),
	'from_address'	=>	get_bloginfo('admin_email'),
	'bcc_address' => $current_user->user_email,
	'email_footer' => '{site_name_url}',
	'disc_type' => 1,
	'description' => _x('One time {fname} {lname}: {email}','Coupon description','my-day-email') ,
	'coupon_amount'	=>	15,
	'email_body'	=> _x("<p style='font-size: 20px;font-weight:600;'>We have a special discount for you, {fname}!</p>
<p style='font-size: 18px;'>Take advantage of this discount code:</p>
<p style='font-size: 24px;font-weight:800;'>{coupon}</p>
<p style='font-size: 18px;'>During the next {expires_in_days} days you can use it in our online store {site_name_url} and get a special discount of <strong>{percent}%</strong> on {products_cnt} non-discounted products.</p>
<p style='font-size: 18px;font-weight:600;'>ENJOY !</p>
<p style='font-size: 18px;'>The Team of {site_name}</p>
<p style='font-size: 14px;'>The coupon can only be used after logging into your account and cannot be used with other discounts. Some products are exluded from the discount.</p>" ,'Email Body', 'my-day-email'	) ,
	'category' =>	_x('one-time','Coupon category', 'my-day-email'	) ,
	);
	if ($add_new == true) {
		add_option( 'onetimeemail_options', $option_array );
	} else {
		update_option( 'onetimeemail_options', $option_array );
	}
}

function namedayemail_save_defaults($add_new = false){
			$current_user = wp_get_current_user();
			
			$option_array = array(
				'subject'	=>	_x('{fname}, here is your name day gift','Email Subject','my-day-email') ,
				'header'  =>	_x('Congratulations','Email Header','my-day-email') ,
				'days_before'	=>	1,
				'characters' =>	7,
				'wc_template' =>	1,
				'test' =>	1,
				'send_time'  =>	'05:00',
				'expires'	=>	31,
				'from_name'	=>	get_bloginfo('name'),
				'from_address'	=>	get_bloginfo('admin_email'),
				'bcc_address' => $current_user->user_email,
				'email_footer' => '{site_name_url}',
				'disc_type' => 1,
				'description' => _x('Name Day {fname}: {email}','Coupon description','my-day-email') ,
				'coupon_amount'	=>	10,
				'email_body'	=> _x("<p style='font-size: 20px;font-weight:600;'>Have a nice name day, {fname}!</p>
<p style='font-size: 18px;'>Take advantage of this name day discount code:</p>
<p style='font-size: 24px;font-weight:800;'>{coupon}</p>
<p style='font-size: 18px;'>During the next {expires_in_days} days you can use it in our online store {site_name_url} and get a special discount of <strong>{percent}%</strong> on {products_cnt} non-discounted products.</p>
<p style='font-size: 18px;font-weight:600;'>ALL THE BEST !</p>
<p style='font-size: 18px;'>The Team of {site_name}</p>
<p style='font-size: 14px;'>The coupon can only be used after logging into your account and cannot be used with other discounts. Some products are exluded from the discount.</p>" ,'Email Body', 'my-day-email'	) ,
				'category' =>	_x('name-day','Coupon category', 'my-day-email'	) ,
			);
			if ($add_new == true) {
				add_option( 'namedayemail_options', $option_array );	
			} else {
				update_option( 'namedayemail_options', $option_array );	
			}	
	}
	
function namedayemail_plugin_deactivation() {
    wp_clear_scheduled_hook( 'namedayemail_cron' );
}

function mydayemail_settings_link( array $links ) {
    $url = get_admin_url() . "admin.php?&page=mydayemail";
    $settings_link = '<a href="' . $url . '">' . __('Settings', 'woocommerce') . '</a>';
      $links[] = $settings_link;
    return $links;
  } 
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_day_email() {

	$plugin = new My_Day_Email();
	$plugin->run();

}
run_my_day_email();
