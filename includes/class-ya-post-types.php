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
		add_action( 'init', array( __CLASS__, 'support_jetpack_omnisearch' ) );

		add_action( 'manage_vessel_posts_columns' , array( __CLASS__, 'manage_vessel_columns' ), 10, 1 );
		add_action( 'manage_vessel_posts_custom_column' , array( __CLASS__, 'display_vessel_column' ), 10, 2 );

		add_filter('post_row_actions', array( __CLASS__, 'reload_vessel_action' ), 10, 2);
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
					'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'publicize', 'wpcom-markdown' ),
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

	public static function manage_vessel_columns($columns)
	{
		return array_merge( $columns, array( 'source' => __( 'Source', 'yatco' ) ) );
	}

	public static function display_vessel_column($column, $post_id)
	{
		if ($column == 'source'){
	        echo ya_get_source($post_id);
	    }
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
