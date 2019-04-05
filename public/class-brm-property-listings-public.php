<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ernestoarellano.dev
 * @since      1.0.0
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/public
 * @author     Ernesto Arellano <ernesto@bigrigmedia.net>
 */
class Brm_Property_Listings_Public {

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
	 * 
	 * @since		1.0.0
	 * @access	protected
	 * @var			string		 $google_maps_api		The API key for Google Maps.
	 */
	private $google_maps_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    				The version of this plugin.
	 * @param      string    $google_maps_api   The API key for Google Maps.
	 */
	public function __construct( $plugin_name, $version, $google_maps_api ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->google_maps_api = $google_maps_api;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Brm_Property_Listings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Brm_Property_Listings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/brm-property-listings-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Brm_Property_Listings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Brm_Property_Listings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brm-property-listings-public.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( 'brm-google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$this->google_maps_api.'', [], null, true );

	}

	/**
	 * @since 1.0.0
	 */
	public function script_defer( $tag, $handle, $src ) {
		$scripts = [
			'brm-property-listings',
			'brm-google-maps'
		];

		if (in_array($handle, $scripts)) {
				switch ($handle) {
					case 'brm-google-maps':
						return '<script src="'.$src.'" async defer></script>';
					break;
					default:
						return '<script src="'.$src.'" defer></script>';
					break;
				}
		}

		return $tag;
	}

}
