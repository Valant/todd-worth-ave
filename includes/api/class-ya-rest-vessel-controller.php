<?php
/**
 * REST API Vessels controller
 *
 * Handles requests to the /vessels endpoint.
 *
 * @author   Valant
 * @category API
 * @package  Yatco/API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API Vessels controller class.
 *
 * @package Yatco/API
 */
class YA_REST_Vessels_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wp/v2';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'vessels';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'vessel';

	/**
	 * Initialize vessels actions.
	 */
	public function __construct() {
		#add_filter( "woocommerce_rest_{$this->post_type}_query", array( $this, 'query_args' ), 10, 2 );
	}

	/**
	 * Register the routes for vessels.
	 */
	public function register_routes() {
	}


	/**
	 * Query args.
	 *
	 * @param array $args
	 * @param WP_REST_Request $request
	 * @return array
	 */
	public function query_args( $args, $request ) {
		global $wpdb;

		return $args;
	}
	
}
