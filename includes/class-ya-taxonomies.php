<?php
/**
 * Vessel Taxonomies
 *
 * Registers taxonomies for vessel fields
 *
 * @class       YA_Taxonomies
 * @version     1.0.0
 * @package     Yatco/Classes/Vessels
 * @category    Class
 * @author      Valant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Taxonomies Class
 */
class YA_Taxonomies {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_hidden_taxonomies' ), 5 );
	}

	/**
	 * Gett taxonomies names
	 * @return array
	 */
	public static function get_taxonomies_names() {
		$taxonomies = array(
			'VesselTop' => array(
				'slug'              => 'vessel_top',
				'name'              => __( 'Vessel Top', 'yatco' ),
				'singular_name'     => __( 'Vessel Top', 'yatco' ),
				'menu_name'         => _x( 'Vessel Top', 'Admin menu name', 'yatco' )
			),
			'HullHullDesigner' => array(
				'slug'              => 'naval_architect',
				'name'              => __( 'Naval Architect', 'yatco' ),
				'singular_name'     => __( 'Naval Architect', 'yatco' ),
				'menu_name'         => _x( 'Naval Architect', 'Admin menu name', 'yatco' )
			),
			'HullHullMaterial' => array(
				'slug'              => 'hull_material',
				'name'              => __( 'Hull Materials', 'yatco' ),
				'singular_name'     => __( 'Hull Material', 'yatco' ),
				'menu_name'         => _x( 'Hull Material', 'Admin menu name', 'yatco' )
			),
			'FuelType' => array(
				'slug'              => 'fuel_type',
				'name'              => __( 'Fuel Types', 'yatco' ),
				'singular_name'     => __( 'Fuel Type', 'yatco' ),
				'menu_name'         => _x( 'Fuel Type', 'Admin menu name', 'yatco' )
			),
			'EngineEngineModel' => array(
				'slug'              => 'engine_model',
				'name'              => __( 'Engine Models', 'yatco' ),
				'singular_name'     => __( 'Engine Model', 'yatco' ),
				'menu_name'         => _x( 'Engine Model', 'Admin menu name', 'yatco' )
			),
			'EngineManufacturer' => array(
				'slug'              => 'engine_manufacturer',
				'name'              => __( 'Engine Manufacturer', 'yatco' ),
				'singular_name'     => __( 'Engine Manufacturer', 'yatco' ),
				'menu_name'         => _x( 'Engine Manufacturer', 'Admin menu name', 'yatco' )
			),
			'HullHullColor' => array(
				'slug'              => 'hull_color',
				'name'              => __( 'Hull Colors', 'yatco' ),
				'singular_name'     => __( 'Hull Color', 'yatco' ),
				'menu_name'         => _x( 'Hull Color', 'Admin menu name', 'yatco' )
			),
			'HullInteriorDesigner' => array(
				'slug'              => 'decorator',
				'name'              => __( 'Decorators', 'yatco' ),
				'singular_name'     => __( 'Decorator', 'yatco' ),
				'menu_name'         => _x( 'Decorator', 'Admin menu name', 'yatco' )
			),
		);
		return apply_filters('yatco_taxonomies_list', $taxonomies);
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
	 * Register hidden taxonomies.
	 */
	public static function register_hidden_taxonomies() {
		$taxonomies = self::get_taxonomies_names();
		
		if ( taxonomy_exists( 'vessel_top' ) ) {
			return;
		}

		do_action( 'yatco_register_attr_taxonomies' );

		foreach ($taxonomies as $yatco_key => $taxonomy) {
			register_taxonomy( $taxonomy['slug'],
				apply_filters( 'yatco_taxonomy_objects_' . $taxonomy['slug'], array( 'vessel' ) ),
				apply_filters( 'yatco_taxonomy_args_' . $taxonomy['slug'], array(				
					'label'                 => __( 'Vessel Categories', 'yatco' ),
					'labels' => array(
							'name'              => $taxonomy['name'],
							'singular_name'     => $taxonomy['singular_name'],
							'menu_name'         => $taxonomy['menu_name'],
							'search_items'      => sprintf(__( 'Search %s', 'yatco' ), $taxonomy['name']),
							'all_items'         => sprintf(__( 'All %s', 'yatco' ), $taxonomy['name']),
							'parent_item'       => sprintf(__( 'Parent %s', 'yatco' ), $taxonomy['singular_name']),
							'parent_item_colon' => sprintf(__( 'Parent %s:', 'yatco' ), $taxonomy['singular_name']),
							'edit_item'         => sprintf(__( 'Edit %s', 'yatco' ), $taxonomy['singular_name']),
							'update_item'       => sprintf(__( 'Update %s', 'yatco' ), $taxonomy['singular_name']),
							'add_new_item'      => sprintf(__( 'Add New %s', 'yatco' ), $taxonomy['singular_name']),
							'new_item_name'     => sprintf(__( 'New %s Name', 'yatco' ), $taxonomy['singular_name']),
						),
					'hierarchical'          => false,
					'public'                => false,
					'show_admin_column'     => false,
					'show_ui'               => true,
					'query_var'             => true,
					'rewrite'               => false,
					'show_in_rest'          => true,
	  				'rest_base'             => $taxonomy['slug'],
	  				'rest_controller_class' => 'WP_REST_Terms_Controller',
				) )
			);
		}

	}
	
}

YA_Taxonomies::init();