<?php
/**
 * Installation related functions and actions.
 *
 * @author 		Valant
 * @category 	Admin
 * @package 	Yatco/Classes
 * @version   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Install Class
 */
class YA_Install {

	/**
	 * Hook in tabs.
	 */
	public static function init() {		
		add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'install_actions' ) );
		add_filter( 'plugin_action_links_' . YA_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * Install Yatco
	 */
	public static function install() {
		if ( ! defined( 'YA_INSTALLING' ) ) {
			define( 'YA_INSTALLING', true );
		}

		// Ensure needed classes are loaded
		include_once( 'admin/class-ya-admin-notices.php' );
		

		// Register post types
		YA_Post_types::register_post_types();
		YA_Post_types::register_taxonomies();

		// Update version
		delete_option( 'yatco_version' );
		add_option( 'yatco_version', YA()->version );

		self::create_cron_jobs();

		// Check if pages are needed
		if ( ya_get_page_id( 'vessels' ) < 1 ) {
			YA_Admin_Notices::add_notice( 'install' );
		}

		// Redirect to welcome screen
		if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
			set_transient( '_ya_activation_redirect', 1, 30 );
		}

		// Trigger action
		do_action( 'yatco_installed' );
	}

	/**
	 * Create cron jobs (clear them first)
	 */
	public static function create_cron_jobs() {
		wp_clear_scheduled_hook( 'yatco_cron_update_vassel' );

		$ve         = get_option( 'gmt_offset' ) > 0 ? '+' : '-';
		$recurrence = get_option( 'yatco_cron_schedule' );
		if(!$recurrence)
			$recurrence = 'two_hourly';

		wp_schedule_event( strtotime( '00:00 yesterday ' . $ve . get_option( 'gmt_offset' ) . ' HOURS' ), $recurrence, 'yatco_cron_update_vassel' );
	}

	/**
	 * check_version function.
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && ( get_option( 'yatco_version' ) != YA()->version || get_option( 'yatco_db_version' ) != YA()->version ) ) {
			self::install();
			do_action( 'yatco_updated' );
		}
	}
	

	/**
	 * Install actions such as installing pages when a button is clicked.
	 */
	public static function install_actions() {
		// Update button
		if ( ! empty( $_GET['do_update_yatco'] ) ) {

			self::update();

			// Update complete
			YA_Admin_Notices::remove_notice( 'update' );

			// What's new redirect
			if ( ! YA_Admin_Notices::has_notice( 'install' ) ) {
				delete_transient( '_ya_activation_redirect' );
				wp_redirect( admin_url( 'index.php?page=ya-load-vessels&ya-updated=true' ) );
				exit;
			}
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=ya-settings' ) . '" title="' . esc_attr( __( 'View Yatco Settings', 'yatco' ) ) . '">' . __( 'Settings', 'yatco' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}


}

YA_Install::init();
