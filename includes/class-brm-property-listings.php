<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ernestoarellano.dev
 * @since      1.0.0
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/includes
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
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/includes
 * @author     Ernesto Arellano <ernesto@bigrigmedia.net>
 */
class Brm_Property_Listings {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Brm_Property_Listings_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * The API key for Google Maps of the plugin 
	 * 
	 * @since		1.0.0
	 * @access	protected
	 * @var			string		$google_maps_api		The API key for Google Maps 
	 */
	protected $google_maps_api;

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
		if ( defined( 'BRM_PROPERTY_LISTINGS_VERSION' ) ) {
			$this->version = BRM_PROPERTY_LISTINGS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'brm-property-listings';
		$this->google_maps_api = 'AIzaSyCugE131kHRpc9su0FCtcHaXepJ11xn_Qo';
		$this->post_type = 'listings';

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
	 * - Brm_Property_Listings_Loader. Orchestrates the hooks of the plugin.
	 * - Brm_Property_Listings_i18n. Defines internationalization functionality.
	 * - Brm_Property_Listings_Admin. Defines all hooks for the admin area.
	 * - Brm_Property_Listings_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-brm-property-listings-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-brm-property-listings-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-brm-property-listings-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-brm-property-listings-public.php';

		$this->loader = new Brm_Property_Listings_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Brm_Property_Listings_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Brm_Property_Listings_i18n();

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

		$plugin_admin = new Brm_Property_Listings_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_post_type() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_post_taxonomies' );
		$this->loader->add_action( 'init', $plugin_admin, 'post_type_rewrites' );
		$this->loader->add_action( 'manage_listings_posts_custom_column', $plugin_admin, 'post_type_admin_columns_content', 10, 2 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_metabox' );

		$this->loader->add_filter( 'post_type_link', $plugin_admin, 'taxonomy_permalinks', 1, 2 );

		$this->loader->add_filter( 'manage_edit-listings_columns', $plugin_admin, 'post_type_admin_columns' );

		$this->loader->add_filter( 'rest_prepare_listings', $plugin_admin, 'listings_api_json', 10, 3 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Brm_Property_Listings_Public( $this->get_plugin_name(), $this->get_version(), $this->get_google_maps_api(), $this->get_post_type() );

		$this->loader->add_action( 'init', $plugin_public, 'image_sizes' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 200 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 200 );
		
		$this->loader->add_action( 'style_loader_tag', $plugin_public, 'style_preload', 10, 4 );
		$this->loader->add_action( 'script_loader_tag', $plugin_public, 'script_defer', 10, 3 );

		$this->loader->add_shortcode( 'map', $plugin_public, 'map_shortcode' );
		$this->loader->add_shortcode( 'listings-filter', $plugin_public, 'filter_shortcode' );
		$this->loader->add_shortcode( 'listings-filter-results', $plugin_public, 'filter_results_shortcode' );

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
	 * @return    Brm_Property_Listings_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Retreive the Google Maps API key of the plugin.
	 * 
	 * @since			1.0.0
	 * @return		string		The API key for Google Maps of the plugin.
	 */
	public function get_google_maps_api() {
		return $this->google_maps_api;
	}

	/**
	 * Retreive the post type of the plugin.
	 * 
	 * @since			1.0.0
	 * @return		string		The post type of the plugin.
	 */
	public function get_post_type() {
		return $this->post_type;
	}

}
