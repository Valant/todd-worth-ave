<?php
/**
 * Display notices in admin.
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Admin_Notices Class
 */
class YA_Admin_Notices {

	/**
	 * Array of notices - name => callback
	 * @var array
	 */
	private $notices = array(
		'install'             => 'install_notice',
		'update'              => 'update_notice',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'switch_theme', array( $this, 'reset_admin_notices' ) );
		add_action( 'yatco_installed', array( $this, 'reset_admin_notices' ) );
		add_action( 'wp_loaded', array( $this, 'hide_notices' ) );
		add_action( 'yatco_hide_install_notice', array( $this, 'hide_install_notice' ) );
		add_action( 'admin_print_styles', array( $this, 'add_notices' ) );
	}

	/**
	 * Reset notices for themes when switched or a new version of YA is installed
	 */
	public function reset_admin_notices() {
	}

	/**
	 * Show a notice
	 * @param  string $name
	 */
	public static function add_notice( $name ) {
		$notices = array_unique( array_merge( get_option( 'yatco_admin_notices', array() ), array( $name ) ) );
		update_option( 'yatco_admin_notices', $notices );
	}

	/**
	 * Remove a notice from being displayed
	 * @param  string $name
	 */
	public static function remove_notice( $name ) {
		$notices = array_diff( get_option( 'yatco_admin_notices', array() ), array( $name ) );
		update_option( 'yatco_admin_notices', $notices );
	}

	/**
	 * See if a notice is being shown
	 * @param  string  $name
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		return in_array( $name, get_option( 'yatco_admin_notices', array() ) );
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public function hide_notices() {
		if ( isset( $_GET['ya-hide-notice'] ) ) {
			$hide_notice = sanitize_text_field( $_GET['ya-hide-notice'] );
			self::remove_notice( $hide_notice );
			do_action( 'yatco_hide_' . $hide_notice . '_notice' );
		}
	}

	/**
	 * When install is hidden, trigger a redirect
	 */
	public function hide_install_notice() {
		// What's new redirect
		if ( ! self::has_notice( 'update' ) ) {
			delete_transient( '_ya_activation_redirect' );
			wp_redirect( admin_url( 'index.php?page=ya-load-vessels&ya-updated=true' ) );
			exit;
		}
	}

	/**
	 * Add notices + styles if needed.
	 */
	public function add_notices() {
		$notices = get_option( 'yatco_admin_notices', array() );

		foreach ( $notices as $notice ) {
			wp_enqueue_style( 'yatco-activation', plugins_url(  '/assets/css/activation.css', YA_PLUGIN_FILE ) );
			wp_enqueue_script( 'ya-admin-notices' );
			add_action( 'admin_notices', array( $this, $this->notices[ $notice ] ) );
		}
	}

	/**
	 * If we need to update, include a message with the update button
	 */
	public function update_notice() {
		include( 'views/html-notice-update.php' );
	}

	/**
	 * If we have just installed, show a message with the install pages button
	 */
	public function install_notice() {
		include( 'views/html-notice-install.php' );
	}

}

new YA_Admin_Notices();
