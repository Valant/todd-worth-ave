<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies
 *
 * @class       YA_Post_types
 * @version     1.0.0
 * @package     Yatco/Classes/Vessels
 * @category    Class
 * @author      Valant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Post_types Class
 */
class YA_Post_types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_statuses' ), 6 );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'support_jetpack_omnisearch' ) );

		add_filter('post_row_actions', array( __CLASS__, 'reload_vessel_action' ), 10, 2);
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {
		if ( taxonomy_exists( 'vessel_company' ) ) {
			return;
		}

		do_action( 'yatco_register_taxonomy' );

		$permalinks = get_option( 'yatco_permalinks' );

		register_taxonomy( 'vessel_cat',
			apply_filters( 'yatco_taxonomy_objects_vessel_cat', array( 'vessel' ) ),
			apply_filters( 'yatco_taxonomy_args_vessel_cat', array(				
				'label'                 => __( 'Vessel Categories', 'yatco' ),
				'labels' => array(
						'name'              => __( 'Vessel Categories', 'yatco' ),
						'singular_name'     => __( 'Vessel Category', 'yatco' ),
						'menu_name'         => _x( 'Categories', 'Admin menu name', 'yatco' ),
						'search_items'      => __( 'Search Vessel Categories', 'yatco' ),
						'all_items'         => __( 'All Vessel Categories', 'yatco' ),
						'parent_item'       => __( 'Parent Vessel Category', 'yatco' ),
						'parent_item_colon' => __( 'Parent Vessel Category:', 'yatco' ),
						'edit_item'         => __( 'Edit Vessel Category', 'yatco' ),
						'update_item'       => __( 'Update Vessel Category', 'yatco' ),
						'add_new_item'      => __( 'Add New Vessel Category', 'yatco' ),
						'new_item_name'     => __( 'New Vessel Category Name', 'yatco' )
					),
				'hierarchical'          => true,
				'show_admin_column'     => true,
				'show_ui'               => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_rest'          => true,
  				'rest_base'             => 'vessel_category',
  				'rest_controller_class' => 'WP_REST_Terms_Controller',
			) )
		);
		register_taxonomy( 'vessel_company',
			apply_filters( 'yatco_taxonomy_objects_vessel_company', 'vessel' ),
			apply_filters( 'yatco_taxonomy_args_vessel_company', array(
				'label'                 => __( 'Brokers', 'yatco' ),
				'labels' => array(
						'name'              => __( 'Broker', 'yatco' ),
						'singular_name'     => __( 'Broker', 'yatco' ),
						'menu_name'         => _x( 'Brokers', 'Admin menu name', 'yatco' ),
						'search_items'      => __( 'Search Brokers', 'yatco' ),
						'all_items'         => __( 'All Brokers', 'yatco' ),
						'parent_item'       => __( 'Parent Broker', 'yatco' ),
						'parent_item_colon' => __( 'Parent Broker:', 'yatco' ),
						'edit_item'         => __( 'Edit Broker', 'yatco' ),
						'update_item'       => __( 'Update Broker', 'yatco' ),
						'add_new_item'      => __( 'Add New Broker', 'yatco' ),
						'new_item_name'     => __( 'New Broker Name', 'yatco' )
					),
				'hierarchical'          => true,
				'public'                => false,
				'show_admin_column'     => true,
				'show_ui'               => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_rest'          => true,
  				'rest_base'             => 'broker',
  				'rest_controller_class' => 'WP_REST_Terms_Controller',
			) )
		);
		register_taxonomy( 'vessel_builder',
			apply_filters( 'yatco_taxonomy_objects_vessel_builder', 'vessel' ),
			apply_filters( 'yatco_taxonomy_args_vessel_builder', array(
				'label'                 => __( 'Builders', 'yatco' ),
				'labels' => array(
						'name'              => __( 'Builder', 'yatco' ),
						'singular_name'     => __( 'Builder', 'yatco' ),
						'menu_name'         => _x( 'Builders', 'Admin menu name', 'yatco' ),
						'search_items'      => __( 'Search Builders', 'yatco' ),
						'all_items'         => __( 'All Builders', 'yatco' ),
						'parent_item'       => __( 'Parent Builder', 'yatco' ),
						'parent_item_colon' => __( 'Parent Builder:', 'yatco' ),
						'edit_item'         => __( 'Edit Builder', 'yatco' ),
						'update_item'       => __( 'Update Builder', 'yatco' ),
						'add_new_item'      => __( 'Add New Builder', 'yatco' ),
						'new_item_name'     => __( 'New Builder Name', 'yatco' )
					),
				'hierarchical'          => false,
				'public'                => false,
				'show_admin_column'     => true,
				'show_ui'               => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_rest'          => true,
  				'rest_base'             => 'builder',
  				'rest_controller_class' => 'WP_REST_Terms_Controller',
			) )
		);

		register_taxonomy( 'vessel_amenities',
			apply_filters( 'yatco_taxonomy_objects_vessel_amenities', 'vessel' ),
			apply_filters( 'yatco_taxonomy_args_vessel_amenities', array(
				'label'                 => __( 'Vessel Amenities', 'yatco' ),
				'labels' => array(
						'name'              => __( 'Amenities', 'yatco' ),
						'singular_name'     => __( 'Amenity', 'yatco' ),
						'menu_name'         => _x( 'Amenities', 'Admin menu name', 'yatco' ),
						'search_items'      => __( 'Search Amenities', 'yatco' ),
						'all_items'         => __( 'All Amenities', 'yatco' ),
						'parent_item'       => __( 'Parent Amenity', 'yatco' ),
						'parent_item_colon' => __( 'Parent Amenity:', 'yatco' ),
						'edit_item'         => __( 'Edit Amenity', 'yatco' ),
						'update_item'       => __( 'Update Amenity', 'yatco' ),
						'add_new_item'      => __( 'Add New Amenity', 'yatco' ),
						'new_item_name'     => __( 'New Amenity Name', 'yatco' )
					),
				'hierarchical'          => true,
				'public'                => false,
				'show_admin_column'     => false,
				'show_ui'               => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_rest'          => true,
  				'rest_base'             => 'amenities',
  				'rest_controller_class' => 'WP_REST_Terms_Controller',
			) )
		);
		register_taxonomy( 'vessel_toys',
			apply_filters( 'yatco_taxonomy_objects_vessel_amenities', 'vessel' ),
			apply_filters( 'yatco_taxonomy_args_vessel_amenities', array(
				'label'                 => __( 'Vessel Toys', 'yatco' ),
				'labels' => array(
						'name'              => __( 'Toys', 'yatco' ),
						'singular_name'     => __( 'Toy', 'yatco' ),
						'menu_name'         => _x( 'Toys', 'Admin menu name', 'yatco' ),
						'search_items'      => __( 'Search Toys', 'yatco' ),
						'all_items'         => __( 'All Toys', 'yatco' ),
						'parent_item'       => __( 'Parent Toy', 'yatco' ),
						'parent_item_colon' => __( 'Parent Toy:', 'yatco' ),
						'edit_item'         => __( 'Edit Toy', 'yatco' ),
						'update_item'       => __( 'Update Toy', 'yatco' ),
						'add_new_item'      => __( 'Add New Toy', 'yatco' ),
						'new_item_name'     => __( 'New Toy Name', 'yatco' )
					),
				'hierarchical'          => true,
				'public'                => false,
				'show_admin_column'     => false,
				'show_ui'               => true,
				'query_var'             => true,
				'rewrite'               => false,
				'show_in_rest'          => true,
  				'rest_base'             => 'toys',
  				'rest_controller_class' => 'WP_REST_Terms_Controller',
			) )
		);

	

		do_action( 'yatco_after_register_taxonomy' );
	}
	
	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if ( post_type_exists('vessel') ) {
			return;
		}

		do_action( 'yatco_register_post_type' );

		$permalinks        = get_option( 'yatco_permalinks' );
		$vessel_permalink = empty( $permalinks['vessel_base'] ) ? _x( 'vessel', 'slug', 'yatco' ) : $permalinks['vessel_base'];

		register_post_type( 'vessel',
			apply_filters( 'yatco_register_post_type_vessel',
				array(
					'labels'              => array(
							'name'               => __( 'Vessels', 'yatco' ),
							'singular_name'      => __( 'Vessel', 'yatco' ),
							'menu_name'          => _x( 'Vessels', 'Admin menu name', 'yatco' ),
							'add_new'            => __( 'Add Vessel', 'yatco' ),
							'add_new_item'       => __( 'Add New Vessel', 'yatco' ),
							'edit'               => __( 'Edit', 'yatco' ),
							'edit_item'          => __( 'Edit Vessel', 'yatco' ),
							'new_item'           => __( 'New Vessel', 'yatco' ),
							'view'               => __( 'View Vessel', 'yatco' ),
							'view_item'          => __( 'View Vessel', 'yatco' ),
							'search_items'       => __( 'Search Vessels', 'yatco' ),
							'not_found'          => __( 'No Vessels found', 'yatco' ),
							'not_found_in_trash' => __( 'No Vessels found in trash', 'yatco' ),
							'parent'             => __( 'Parent Vessel', 'yatco' )
						),
					'menu_icon'           => 'dashicons-palmtree',
					'description'         => __( 'This is where you can add new vessels to your store.', 'yatco' ),
					'public'              => true,
					'show_ui'             => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite'             => $vessel_permalink ? array( 'slug' => untrailingslashit( $vessel_permalink ), 'with_front' => false, 'feeds' => true ) : false,
					'query_var'           => true,
					'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', 'publicize', 'wpcom-markdown' ),
					'has_archive'         => 'vessels',
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
  					'rest_base'           => 'vessels',
  					'rest_controller_class' => 'WP_REST_Posts_Controller'
				)
			)
		);

	}


	/**
	 * Register post statuses.
	 */
	public static function register_post_statuses() {
		register_post_status( 'inactive', array(
			'label'                     => _x( 'Inactive', 'vessel', 'yatco' ),
			'public'                    => is_admin(),
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Inactive <span class="count">(%s)</span>', 'Inactive <span class="count">(%s)</span>', 'yatco' ),
		) );
	}

	/**
	 * Add Vessel Support to Jetpack Omnisearch.
	 */
	public static function support_jetpack_omnisearch() {
		if ( class_exists( 'Jetpack_Omnisearch_Posts' ) ) {
			new Jetpack_Omnisearch_Posts( 'vessel' );
		}
	}

	public static function reload_vessel_action($actions, $post) {
		//check for your post type
	    if ($post->post_type =="vessel"){
	    	$VesselID = get_post_meta( $post->ID, 'VesselID', true);
	    	if( $VesselID ){
	    		$url = esc_url( add_query_arg( 'reload_vessel', $VesselID, get_edit_post_link($post->ID) ) );
	        	$actions['reload_vessel'] = sprintf('<a href="%s" target="_blank">%s</a>', $url, __('Reload vessel', 'yatco') );	    		
	    	}
	    }
	    return $actions;
	}
}

YA_Post_types::init();
