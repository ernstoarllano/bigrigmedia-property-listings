<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ernestoarellano.dev
 * @since      1.0.0
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/includes
 * @author     Ernesto Arellano <ernesto@bigrigmedia.net>
 */
class Brm_Property_Listings_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'brm-property-listings',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
