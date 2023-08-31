<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://perties.sk
 * @since      1.0.0
 *
 * @package    My_Day_Email
 * @subpackage My_Day_Email/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    My_Day_Email
 * @subpackage My_Day_Email/includes
 * @author     Vlado Laco <vlado@perties.sk>
 */
class My_Day_Email {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      My_Day_Email_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MY_DAY_EMAIL_VERSION' ) ) {
			$this->version = MY_DAY_EMAIL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'my-day-email';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - My_Day_Email_Loader. Orchestrates the hooks of the plugin.
	 * - My_Day_Email_i18n. Defines internationalization functionality.
	 * - My_Day_Email_Admin. Defines all hooks for the admin area.
	 * - My_Day_Email_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-day-email-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-day-email-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-name-day-email-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		 //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-name-day-email-public.php';

		$this->loader = new My_Day_Email_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the My_Day_Email_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new My_Day_Email_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new My_Day_Email_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );		
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'my_day_email_menu' );
		$this->loader->add_action('admin_init',  $plugin_admin, 'my_day_email_init'  );
		
		$this->loader->add_action( 'wp_ajax_namedayemail_restore_settings', $plugin_admin, 'namedayemail_restore_settings' );
		$this->loader->add_action( 'wp_ajax_onetimeemail_restore_settings', $plugin_admin, 'onetimeemail_restore_settings' );
		$this->loader->add_action( 'wp_ajax_reorderemail_restore_settings', $plugin_admin, 'reorderemail_restore_settings' );				
		$this->loader->add_action( 'wp_ajax_birthdayemail_restore_settings', $plugin_admin, 'birthdayemail_restore_settings' );
		$this->loader->add_action( 'wp_ajax_afterorderemail_restore_settings', $plugin_admin, 'afterorderemail_restore_settings' );
		
		$this->loader->add_action( 'wp_ajax_mydayemail_clear_log', $plugin_admin, 'mydayemail_clear_log' );
		$this->loader->add_action( 'wp_ajax_namedayemail_make_test', $plugin_admin, 'namedayemail_make_test' );	
		$this->loader->add_action( 'wp_ajax_birthdayemail_make_test', $plugin_admin, 'birthdayemail_make_test' );	
		$this->loader->add_action( 'wp_ajax_reorderemail_make_test', $plugin_admin, 'reorderemail_make_test' );	
		$this->loader->add_action( 'wp_ajax_onetimeemail_make_test', $plugin_admin, 'onetimeemail_make_test' );	
		$this->loader->add_action( 'wp_ajax_afterorderemail_make_test', $plugin_admin, 'afterorderemail_make_test' );			
		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

/*		$plugin_public = new My_Day_Email_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );*/	
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    My_Day_Email_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
