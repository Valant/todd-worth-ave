<?php
/**
 * Yatco API
 *
 * @author   VaLant
 * @category API
 * @package  Yatco/API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class YA_API {

	/**
	 * Setup class.
	 */
	public function __construct() {		
		// WP REST API.
		$this->rest_api_init();
	}


	/**
	 * Init WP REST API.
	 */
	private function rest_api_init() {
		global $wp_version;

		// REST API was included starting WordPress 4.4.
		if ( version_compare( $wp_version, 4.4, '<' ) ) {
			return;
		}

		$this->rest_api_includes();

		// Init REST API routes.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Include REST API classes.
	 */
	private function rest_api_includes() {
		// Authentication.
		include_once( 'api/class-ya-rest-authentication.php' );

		include_once( 'api/class-ya-rest-vessel-controller.php' );
		include_once( 'api/class-ya-rest-vessel-taxonomies-controller.php' );
	}

	/**
	 * Register REST API routes.
	 */
	public function register_rest_routes() {
		$controllers = array(
			'YA_REST_Vessel_Controller',
			'YA_REST_Vessel_Taxonomies_Controller',
		);

		foreach ( $controllers as $controller ) {
			$this->$controller = new $controller();
			$this->$controller->register_fields();
		}
	}
}
