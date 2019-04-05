<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ernestoarellano.dev
 * @since      1.0.0
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Brm_Property_Listings
 * @subpackage Brm_Property_Listings/admin
 * @author     Ernesto Arellano <ernesto@bigrigmedia.net>
 */
class Brm_Property_Listings_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/brm-property-listings-admin.css', array(), $this->version, 'all' );

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
		 * defined in Brm_Property_Listings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Brm_Property_Listings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brm-property-listings-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register custom post types 
	 * 
	 * @since 1.0.0
	 * @link 	https://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_post_types() {
		register_post_type( 'listings', [
      'label'               	=> 'Listings',
      'public'              	=> true,
      'publicly_queryable'  	=> true,
      'show_ui'             	=> true,
      'show_in_menu'        	=> true,
      'query_var'           	=> true,
      'rewrite'             	=> [ 'slug' => 'destinations/%city%', 'with_front' => false ],
      'capability_type'     	=> 'post',
      'has_archive'         	=> false,
      'hierarchical'        	=> false,
      'menu_position'       	=> null,
      'menu_icon'           	=> 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#a0a5aa" d="M560 160c-2 0-4 .4-6 1.2L384 224l-10.3-3.6C397 185.5 416 149.2 416 123 416 55 358.7 0 288 0S160 55.1 160 123c0 11.8 4 25.8 10.4 40.6L20.1 216C8 220.8 0 232.6 0 245.7V496c0 9.2 7.5 16 16 16 2 0 4-.4 6-1.2L192 448l172 60.7c13 4.3 27 4.4 40 .2L555.9 456c12.2-4.9 20.1-16.6 20.1-29.7V176c0-9.2-7.5-16-16-16zM176 419.8L31.9 473l-1.3-226.9L176 195.6zM288 32c52.9 0 96 40.8 96 91 0 27-38.1 88.9-96 156.8-57.9-67.9-96-129.8-96-156.8 0-50.2 43.1-91 96-91zm80 444.2l-160-56.5V228.8c24.4 35.3 52.1 68 67.7 85.7 3.2 3.7 7.8 5.5 12.3 5.5s9-1.8 12.3-5.5c12.8-14.5 33.7-39.1 54.3-66.9l13.4 4.7zm32 .2V252.2L544.1 199l1.3 226.9zM312 128c0-13.3-10.8-24-24-24s-24 10.7-24 24c0 13.2 10.8 24 24 24s24-10.7 24-24z"/></svg>' ),
			'supports'            	=> [ 'editor','title','thumbnail' ],
			'register_meta_box_cb' 	=> [ $this, 'add_metaboxes' ],
			'show_in_rest'					=> true
    ] );
	}

	/**
	 * Register custom post type taxonomies
	 * 
	 * @since 1.0.0
	 * @link	https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function register_post_taxonomies() {
		$taxonomies = [
      'Amenities' => [
        'public'        => false,
        'label'         => 'Amenities',
        'url'           => 'amenities',
        'hierarchical'  => true,
        'parent'        => 'listings'
      ],
      'City' => [
        'public'        => true,
        'label'         => 'City',
        'url'           => 'destinations',
        'hierarchical'  => true,
        'parent'        => 'listings'
      ],
      'Neighborhood' => [
        'public'        => false,
        'label'         => 'Neighborhood',
        'url'           => 'neighborhood',
        'hierarchical'  => true,
        'parent'        => 'listings'
      ]
    ];

    if ( !empty( $taxonomies ) ) {
      foreach ( $taxonomies as $key => $taxonomy ) {
        // Taxonomy variables
        $taxonomy_string    = str_replace( ' ', '_', strtolower( $key ) );
        $label              = ucwords( $taxonomy['label'] );
        $rewrite_url        = $taxonomy['url'];
        $public             = $taxonomy['public'];
        $hierarchical       = $taxonomy['hierarchical'];
        $parent             = $taxonomy['parent'];

        register_taxonomy(
          $taxonomy_string,
          $parent,
          [
            'label'         => $label,
            'public'        => $public,
            'show_ui'       => true,
            'rewrite'       => [ 'slug' => $rewrite_url, 'with_front' => false ],
            'hierarchical'  => $hierarchical
          ]
        );
      }
    }
	}

	/**
	 * Add some url rewrites for our custom post type
	 * 
	 * @since 1.0.0
	 * @link	https://codex.wordpress.org/Rewrite_API/add_rewrite_rule
	 */
	public function post_type_rewrites() {
		add_rewrite_rule( 'destinations/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?page_id=$matches[1]', 'top' );
	}

	/**
	 * Replace a query string in our custom post type url
	 * 
	 * @since 1.0.0
	 * @link 	https://codex.wordpress.org/Plugin_API/Filter_Reference/post_type_link
	 */
	public function taxonomy_permalinks( $url, $post ) {
		if ( is_object( $post ) && $post->post_type == 'listings' ) {
      $terms = wp_get_object_terms( $post->ID, 'city' );

      if ($terms) {
        return str_replace( '%city%' , $terms[0]->slug , $url );
      }
    }

    return $url;
	}

	/**
	 * Add some custom columns to our custom post type
	 * 
	 * @since 1.0.0
	 * @link	https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
	 */
	public function post_type_admin_columns( $columns ) {
		$columns = [
      'cb'    				=> '<input type="checkbox" />',
      'title' 				=> __('Title'),
			'city'					=> __('City'),
			'neighborhood' 	=> __('Neighborhood'),
			'sister_park'		=> __('Sister Park'),
      'date'  				=> __('Date')
    ];

    return $columns;
	}

	/**
	 * Insert data into our custom post type columns
	 * 
	 * @since 1.0.0
	 * @link	https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
	 */
	public function post_type_admin_columns_content( $column_name, $id ) {
		$output = [];

    switch ( $column_name ) {
      // City
      case 'city':
        $terms = get_the_terms( $id, 'city' );

        if ( !empty( $terms ) )  {
          foreach ( $terms as $term ) {
            $output[] = $term->name;
          }
        }
			break;
			// Neighborhood
      case 'neighborhood':
        $terms = get_the_terms( $id, 'neighborhood' );

        if ( !empty( $terms ) )  {
          foreach ( $terms as $term ) {
            $output[] = $term->name;
          }
        }
			break;
			// Sister Park
			case 'sister_park':
				$data = (int) get_post_meta( $id, '_listing_sister', true );

				$value = $data === 1 ? 'Yes' : 'No';

				$output[] = $value;
			break;
    }

    // Return output
    echo join( ', ', $output );
	}

	/**
	 * Create some custom metaboxes for our custom post type
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function add_metaboxes() {
		$metaboxes = [
			[
				'id' 				=> 'listing_address',
				'title' 		=> 'Address',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'input'
				],
			],
			[
				'id' 				=> 'listing_phone',
				'title' 		=> 'Phone',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'input'
				],
			],
			[
				'id'				=> 'listing_sister',
				'title'			=> 'Sister Park',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'data'  => [
						'no' 	=> 0,
						'yes' => 1
					], 
					'field'	=> 'select'
				],
			],
			[
				'id' 				=> 'listing_facebook',
				'title' 		=> 'Facebook',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'url'
				],
			],
			[
				'id' 				=> 'listing_twitter',
				'title' 		=> 'Twitter',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'url'
				],
			],
			[
				'id' 				=> 'listing_google',
				'title' 		=> 'Google',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'url'
				],
			],
			[
				'id' 				=> 'listing_yelp',
				'title' 		=> 'Yelp',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'url'
				],
			],
			[
				'id' 				=> 'listing_gallery',
				'title' 		=> 'Gallery',
				'callback' 	=> 'metabox_type',
				'screen' 		=> 'listings',
				'context' 	=> 'normal',
				'priority' 	=> 'default',
				'args'			=> [
					'field'	=> 'file'
				],
			]
		];

		if ( !empty( $metaboxes ) ) {
			foreach ( $metaboxes as $metabox ) {
				add_meta_box(
					$metabox['id'], 
					__( $metabox['title'], $this->plugin_name ), 
					[$this, $metabox['callback']], 
					$metabox['screen'], 
					$metabox['context'], 
					$metabox['priority'],
					$metabox['args']
				);
			}
		}
	}

	/**
	 * Determine which metabox type to output
	 * 
	 * @since 1.0.0
	 */
	public function metabox_type( $post, $metabox ) {
		switch ( $metabox['args']['field'] ) {
			case 'input':
				$this->input_text_metabox( $post->ID, $metabox );
			break;
			case 'select':
				$this->input_select_metabox( $post->ID, $metabox );
			break;
			case 'url':
				$this->input_url_metabox( $post->ID, $metabox );
			break;
			case 'file':
				$this->input_file_metabox( $post->ID, $metabox );
			break;
		}
	}

	/**
	 * Output an file upload metabox 
	 * 
	 * @since 1.0.0
	 */
	public function input_file_metabox( $post_id, $metabox ) {
		wp_nonce_field( basename( __FILE__ ), 'listings_metabox_nonce' );

		$images = get_post_meta( $post_id, '_' . $metabox['id'], true );
		$image_thumbs = [];
		$image_inputs = [];

		if ( $images ) {
			foreach ($images as $image) {
				$image_attachment = wp_get_attachment_image_src((int) $image, 'w132x132');
				$image_html = '<img src="'.$image_attachment[0].'" width="'.$image_attachment[1].'" height="'.$image_attachment[2].'">';
				$image_thumbs[] = $image_html;

				$input_html = '<input type="hidden" name="listing_gallery[]" value="'.(int) $image.'">';
				$image_inputs[] = $input_html;
			}
		}

		$output = '<div class="gallery-thumbs">';

		if ( $image_thumbs ) {
			foreach ( $image_thumbs as $image_thumb ) {
				$output .= $image_thumb;
			}
		}

		$output .= '</div>';
		$output .= '<input class="button js-upload" type="button" value="Add Images">';

		if ( $image_inputs ) {
			foreach ( $image_inputs as $image_input ) {
				$output .= $image_input;
			}
		}

		echo $output;
	}

	/**
	 * Output an input text field metabox
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/#getting-values
	 */
	public function input_text_metabox( $post_id, $metabox ) {
		wp_nonce_field( basename( __FILE__ ), 'listings_metabox_nonce' );

		$data = get_post_meta( $post_id, '_' . $metabox['id'], true );

		echo '<input class="input" type="text" name="'.$metabox['id'].'" value="'.esc_textarea($data).'">';
	}

	/**
	 * Output an input url field metabox
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/#getting-values
	 */
	public function input_url_metabox( $post_id, $metabox ) {
		wp_nonce_field( basename( __FILE__ ), 'listings_metabox_nonce' );

		$data = get_post_meta( $post_id, '_' . $metabox['id'], true );

		echo '<input class="input js-url" type="url" name="'.$metabox['id'].'" value="'.esc_textarea($data).'">';
	}

	/**
	 * Output an input select field metabox
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/#getting-values
	 */
	public function input_select_metabox( $post_id, $metabox ) {
		wp_nonce_field( basename( __FILE__ ), 'listings_metabox_nonce' );

		$data = get_post_meta( $post_id, '_' . $metabox['id'], true );

		$output = '<select class="select" name="'.$metabox['id'].'">';

		foreach ( $metabox['args']['data'] as $key => $option ) {
			$selected = (int) $data === (int) $option ? 'selected' : null;

			$output .= '<option value="'.$option.'" '.$selected.'>'.ucfirst($key).'</option>';
		}

		$output .= '</select>';

		echo $output;
	}

	/**
	 * Save custom metabox data
	 * 
	 * @since 1.0.0
	 * @link 	https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/#saving-values
	 */
	public function save_metabox( $post_id ) {
		// Check if correct post type
		if ( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'listings' ) {
			// Check for nonce and if it's valid
			if ( !isset( $_POST['listings_metabox_nonce'] ) && !wp_verify_nonce( $_POST['listings_metabox_nonce'], 'listings_metabox_nonce' ) ) {
				return;
			}

			// Check if autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check user permissions
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

			// Define our custom metaboxes
			// Need to make this part easier
			$metaboxes = [
				'_listing_address' 	=> 'listing_address',
				'_listing_phone' 		=> 'listing_phone',
				'_listing_sister'		=> 'listing_sister',
				'_listing_facebook'	=> 'listing_facebook',
				'_listing_twitter'	=> 'listing_twitter',
				'_listing_google'		=> 'listing_google',
				'_listing_yelp'			=> 'listing_yelp',
				'_listing_gallery'	=> 'listing_gallery'
			];

			// Check that custom metabox data is set and save
			if ( !empty( $metaboxes ) ) {
				foreach ( $metaboxes as $key => $metabox ) {
					if ( !isset( $_POST[$metabox] ) ) {
						return;
					}

					switch ($metabox) {
						case 'listing_sister':
							update_post_meta( $post_id, $key, sanitize_text_field( (int) $_POST[$metabox] ) );
						break;
						case 'listing_gallery':
							update_post_meta( $post_id, $key, $_POST[$metabox] );
						break;
						default:
							update_post_meta( $post_id, $key, sanitize_text_field( $_POST[$metabox] ) );
						break;
					}
				}
			}

			return;
		}
	}

	/**
	 * @since 1.0.0
	 * @link	https://developer.wordpress.org/reference/hooks/rest_prepare_this-post_type/
	 */
	public function listings_api_json( $data, $post, $context ) {
		$address = get_post_meta( $post->ID, '_listing_address', true );

		if ( $address ) {
			$data->data['address'] = $address;
		}

		return $data;
	}

}
