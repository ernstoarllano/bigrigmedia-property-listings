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
	 * 
	 * @since		1.0.0
	 * @access	protected
	 * @var			string		 $post_type		The post type of the plugin.
	 */
	private $post_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    				The version of this plugin.
	 * @param      string    $google_maps_api   The API key for Google Maps.
	 */
	public function __construct( $plugin_name, $version, $google_maps_api, $post_type ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->google_maps_api = $google_maps_api;
		$this->post_type = $post_type;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/brm-property-listings-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brm-property-listings-public.js', array( 'jquery' ), $this->version, true );

		if ( is_singular('listings') ) {
			global $post;

			wp_localize_script( $this->plugin_name, 'listing', ['id' => $post->ID] );
		}

		wp_enqueue_script( 'brm-google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$this->google_maps_api.'', [], null, true );

	}

	/**
	 * Defer / Async scripts
	 * 
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

	/**
	 * Preload stylesheets
	 * 
	 * @since 1.0.0
	 */
	public function style_preload( $html, $handle, $href, $media ) {
		$styles = [
			'brm-property-listings'
		];

		if (in_array($handle, $styles)) {
				return '<link rel="preload" href="'.$href.'" as="style">
								<link rel="stylesheet" href="'.$href.'" media="'.$media.'">';
		} else {
				return $html;
		}
	}

	/**
	 * Shortcode to display map listings container
	 * 
	 * @since 1.0.0
	 */
	public function map_shortcode() {
		return '<div id="map" class="w-full h-full"></div>';
	}

	/**
	 * Shortcode to display taxonomies filters
	 * 
	 * @since 1.0.0
	 */
	public function filter_shortcode() {
		global $post;

		$taxonomies = get_object_taxonomies($this->post_type);

		$output = '<form class="filter" action="'.get_permalink($post->ID).'" method="get">';

		foreach ( $taxonomies as $key => $taxonomy ) {

			$output .= '<div class="filter__group">
										<select name="'.strtolower($taxonomy).'">
									</div>';

			$output .= '<option value="" selected>Select '.ucfirst($taxonomy).'</option>';

			$terms = get_terms([
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			]);

			foreach ( $terms as $term ) {
				if ($_GET) {
					$get_term = $_GET[$taxonomy];

					$selected = $get_term === $term->slug ? 'selected' : null;
				} else {
					$selected = null;
				}

				$output .= '<option value="'.$term->slug.'" '.$selected.'>'.$term->name.'</option>';
			}

			$output .= '</select></div>';
		}

		$output .= '<input class="btn btn--secondary filter__btn" type="submit" value="Find Destination">';
		$output .= '</form>';

		echo $output;
	}

	/**
	 * Shortcode to display taxonomies filter results
	 * 
	 * @since 1.0.0
	 */
	public function filter_results_shortcode() {
		if ( ($_GET) && ($_GET['amenities'] || $_GET['city'] || $_GET['neighborhood']) ) {
			$search_term = [];
			$search_terms = [];
			$relation = [
				'relation' => 'AND'
			];

			foreach ($_GET as $key => $value) {
				if (!empty($value)) {
					$search_term['taxonomy'] = $key;
					$search_term['terms'] = $value;
					$search_term['field'] = 'slug';
					$search_terms[] = $search_term;
				}
			}

			if ( $search_terms ) {
				$data = new \WP_Query([
					'post_type' => $this->post_type,
					'posts_per_page' => -1,
					'tax_query' => array_merge($relation, $search_terms),
					'orderby' => 'title',
					'order'   => 'ASC',
				]);

				var_dump($data->tax_query);

				if ( $data->have_posts() ) {
					var_dump($data->posts);
				}
			}
		}

		return;
	}

	/**
	 * Create custom image sizes
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	public function image_sizes() {
		$sizes = [
			'w132x132' => [132, 132, true]
		];

		if ( !empty( $sizes ) ) {
			foreach ($sizes as $key => $size) {
				add_image_size($key, $size[0], $size[1], $size[2]);
			}
		}
	}

}
