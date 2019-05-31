<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://agileware.com.au
 * @since      1.0.0
 *
 * @package    Agileware_Civicrm_Utilities
 * @subpackage Agileware_Civicrm_Utilities/includes
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
 * @package    Agileware_Civicrm_Utilities
 * @subpackage Agileware_Civicrm_Utilities/includes
 * @author     Agileware <support@agileware.com.au>
 */
class Agileware_Civicrm_Utilities {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      Agileware_Civicrm_Utilities_Loader $loader Maintains and registers all hooks for the plugin.
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $loader;

	/**
	 * The helper class that provide helper function to all other class
	 *
	 * @var      Agileware_Civicrm_Utilities_Helper $helper The helper class that provide helper function to all other class
	 * @since    1.0.0
	 * @access   public
	 */
	public $helper;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var      string $agileware_civicrm_utilities The string used to uniquely identify this plugin.
	 * @since    1.0.0
	 * @access   protected
	 */
	protected $agileware_civicrm_utilities;

	/**
	 * The current version of the plugin.
	 *
	 * @var      string $version The current version of the plugin.
	 * @since    1.0.0
	 * @access   protected
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
		if ( defined( 'AGILEWARE_CIVICRM_UTILITIESVERSION' ) ) {
			$this->version = AGILEWARE_CIVICRM_UTILITIESVERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->agileware_civicrm_utilities = 'agileware-civicrm-utilities';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Agileware_Civicrm_Utilities_Loader. Orchestrates the hooks of the plugin.
	 * - Agileware_Civicrm_Utilities_i18n. Defines internationalization functionality.
	 * - Agileware_Civicrm_Utilities_Admin. Defines all hooks for the admin area.
	 * - Agileware_Civicrm_Utilities_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-agileware-civicrm-utilities-loader.php';

		/**
		 * The helper class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-agileware-civicrm-utilities-helper.php';

		/**
		 * The shortcode manager.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-agileware-civicrm-utilities-shortcode-manager.php';

		/**
		 * The shortcode interface.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/interface/interface-agileware-civicrm-utilities-shortcode.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-agileware-civicrm-utilities-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-agileware-civicrm-utilities-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-agileware-civicrm-utilities-public.php';

		$this->loader = new Agileware_Civicrm_Utilities_Loader();
		$this->helper = new Agileware_Civicrm_Utilities_Helper($this);

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Agileware_Civicrm_Utilities_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Agileware_Civicrm_Utilities_i18n();

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

		$plugin_admin = new Agileware_Civicrm_Utilities_Admin( $this->get_agileware_civicrm_utilities(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Agileware_Civicrm_Utilities_Public( $this->get_agileware_civicrm_utilities(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register shortcodes.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		$shortcode_manager = new Agileware_Civicrm_Utilities_Shortcode_Manager( $this );

		$this->loader->add_action( 'init', $shortcode_manager, 'register_shortcodes' );
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
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_agileware_civicrm_utilities() {
		return $this->agileware_civicrm_utilities;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Agileware_Civicrm_Utilities_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}