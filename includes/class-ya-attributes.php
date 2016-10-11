<?php
/**
 * Vessel Attributes
 *
 * Registers taxonomies for vessel attributes
 *
 * @class       YA_Attributes
 * @version     1.0.0
 * @package     Yatco/Classes/Vessels
 * @category    Class
 * @author      Valant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Attributes Class
 */
class YA_Attributes {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
	}

	/**
	 * Gett attributes
	 * @return array
	 */
	public static function get_attributes() {
		$attributes = array(
			array(
				'slug'              => 'vessel_top',
				'name'              => __( 'Vessel Top', 'yatco' ),
				'singular_name'     => __( 'Vessel Top', 'yatco' ),
				'menu_name'         => _x( 'Vessel Top', 'Admin menu name', 'yatco' )
			),
			array(
				'slug'              => 'naval_architect',
				'name'              => __( 'Naval Architect', 'yatco' ),
				'singular_name'     => __( 'Naval Architect', 'yatco' ),
				'menu_name'         => _x( 'Naval Architect', 'Admin menu name', 'yatco' )
			),
			array(
				'slug'              => 'hull_material',
				'name'              => __( 'Hull Materials', 'yatco' ),
				'singular_name'     => __( 'Hull Material', 'yatco' ),
				'menu_name'         => _x( 'Hull Material', 'Admin menu name', 'yatco' )
			)
		);
		return apply_filters('yatco_attributes_list', $attributes);
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {
		$attributes = self::get_attributes();
		//var_dump($attributes);
		if ( taxonomy_exists( 'vessel_company' ) ) {
			return;
		}

		do_action( 'yatco_register_attr_taxonomies' );

		/*register_taxonomy( 'vessel_cat',
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
		);*/
	}
	
}

YA_Attributes::init();