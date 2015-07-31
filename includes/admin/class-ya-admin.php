<?php
/**
 * Yatco Admin.
 *
 * @class       YA_Admin
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * YA_Admin class.
 */
class YA_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		// Functions
		#include_once( 'ya-admin-functions.php' );
		#include_once( 'ya-meta-box-functions.php' );

		// Classes
		#include_once( 'class-ya-admin-post-types.php' );
		#include_once( 'class-ya-admin-taxonomies.php' );

		// Classes we only need during non-ajax requests
		if ( ! is_ajax() ) {
			include_once( 'class-ya-admin-menus.php' );
			include_once( 'class-ya-admin-welcome.php' );
			include_once( 'class-ya-admin-notices.php' );
			#include_once( 'class-ya-admin-assets.php' );
		}

	}

	/**
	 * Include admin files conditionally
	 */
	public function conditonal_includes() {

		$screen = get_current_screen();

		switch ( $screen->id ) {
			case 'options-permalink' :
				#include( 'class-ya-admin-permalink-settings.php' );
			break;
		}
	}

}

return new YA_Admin();
