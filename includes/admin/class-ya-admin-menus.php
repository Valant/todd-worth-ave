<?php
/**
 * Setup menus in WP admin.
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YA_Admin_Menus' ) ) :

/**
 * YA_Admin_Menus Class
 */
class YA_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus
		add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );
		add_action( 'parent_file', array( $this, 'menu_highlight' ) );
	}

	/**
	 * Add menu item
	 */
	public function settings_menu() {
		$settings_page = add_submenu_page( 'edit.php?post_type=vessel', __( 'Yatco Settings', 'yatco' ),  __( 'Settings', 'yatco' ) , 'manage_options', 'ya-settings', array( $this, 'settings_page' ) );
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 */
	public function menu_highlight($some_slug) {
		global $submenu_file;
		if (isset($_GET['page']) && $_GET['page'] == 'ya-settings') 
			$submenu_file = 'ya-settings';
		
		return $some_slug;
	}

	/**
	 * Init the settings page
	 */
	public function settings_page() {
		YA_Admin_Settings::output();
	}

	
}

endif;

return new YA_Admin_Menus();
