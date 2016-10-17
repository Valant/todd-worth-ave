<?php
/**
 * REST API Vessel Taxonomies controller
 *
 *
 * @author   Valant
 * @category API
 * @package  Yatco/API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API Vessel Taxonomies controller class.
 *
 * @package Yatco/API
 */
class YA_REST_Vessel_Taxonomies_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wp/v2';


	public function __construct() {
	}

	/**
	 * Register the fields for terms.
	 */
	public function register_fields() {
		register_rest_field( 'vessel_amenities',
	        'image_url',
	        array(
	            'get_callback'    => array($this, 'get_image_url'),
	            'update_callback' => null,
	            'schema'          => null,
	        )
	    );
	    register_rest_field( 'vessel_toys',
	        'image_url',
	        array(
	            'get_callback'    => array($this, 'get_image_url'),
	            'update_callback' => null,
	            'schema'          => null,
	        )
	    );
	}

	public function get_image_url($object, $field_name, $request)
	{
		return get_term_meta( $object[ 'id' ], $field_name, true );;
	}
}
