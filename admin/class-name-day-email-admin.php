<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://perties.sk
 * @since      1.0.0
 *
 * @package    My_Day_Email
 * @subpackage My_Day_Email/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    My_Day_Email
 * @subpackage My_Day_Email/admin
 * @author     Vlado Laco <vlado@perties.sk>
 */
class My_Day_Email_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-day-email-admin.css', array(), $this->version, 'all' );
	}

	/**Function to add menu. */
	public function my_day_email_menu() {
		$menu_list = _x('My Day Emails','menu', 'my-day-email');
		add_submenu_page( 'woocommerce-marketing', $menu_list, $menu_list, 'manage_options', 'mydayemail', array( $this, 'mydayemail_menu_init' ) );	
	}
	public function mydayemail_menu_init()
	{
		require plugin_dir_path( __FILE__ ) . 'partials/my-day-admin-display.php';
		//require plugin_dir_path( __FILE__ ) . 'partials/my-day-email-admin-display.php';
	}
	public function my_day_email_init(){
		register_setting( 'namedayemail_plugin_options', 'namedayemail_options', 
		    array('sanitize_callback' => array( $this, 'namedayemail_validate_options' ),)
		 );
		register_setting( 'mydayemail_plugin_log_options', 'mydayemail_logs', 
			array('sanitize_callback' => array( $this, 'namedayemail_validate_log_options' ),) 
		 );	
		 register_setting( 'mydayemail_plugin_options', 'mydayemail_options',
		 array('sanitize_callback' => array( $this, 'mydayemail_validate_options' ),)
		 );		
		 register_setting( 'birtdayemail_plugin_options', 'birthdayemail_options',
		 array('sanitize_callback' => array( $this, 'birthdayemail_validate_options' ),)
		 );		   
	}
	function namedayemail_validate_options($input) 	{
		return $input;
	}
	function birthdayemail_validate_options($input)
	{
		return $input;
	}	
	function namedayemail_validate_log_options($input) {
		return $input;
	}
	function mydayemail_validate_options($input)
	{
		return $input;
	}
 	public function mydayemail_clear_log() {
 		if ( isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_mydayemail_nonce_log' ) ) 
		{
	 		delete_option( 'mydayemail_logs' );
	 		die();
		}
	}
	
 	public function namedayemail_make_test() {		 
 		if ( isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_namedayemail_nonce_test' ) ) 
		{
			$user = wp_get_current_user();
			$funcs = new EmailFunctions("namedayemail");
			$funcs->	mydayemail_create($user, true);
	 		die();
		}
	}
		
	public function birthdayemail_make_test()
	{
		if ( isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_birthdayemail_nonce_test' ) ) {
			$user = wp_get_current_user();
			$funcs = new EmailFunctions("birthdayemail");
			$funcs->	mydayemail_create($user, true);
			die();
		}
	}		
		
 	public function namedayemail_restore_settings($add_new = false) {
		if ( isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_namedayemail_nonce' ) || $add_new == true) 
		{
			namedayemail_save_defaults($add_new);			
			die();
		}
	}		
	
	public function birthdayemail_restore_settings($add_new = false)
	{
		if ( isset( $_POST['nonce'] ) && '' !== $_POST['nonce'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), '_birthdayemail_nonce' ) || $add_new == true) {
			birthdayemail_save_defaults($add_new);
			die();
		}
	}	
			
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in My_Day_Email_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The My_Day_Email_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/name-day-email-admin.js', array( 'jquery' ), $this->version, false );

	}
	
}
