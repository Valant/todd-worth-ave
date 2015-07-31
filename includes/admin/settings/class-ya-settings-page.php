<?php
/**
 * Yatco Settings Page/Tab
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YA_Settings_Page' ) ) :

/**
 * YA_Settings_Page
 */
abstract class YA_Settings_Page {

	protected $id    = '';
	protected $label = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'yatco_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'yatco_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'yatco_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'yatco_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Add this page to settings
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters( 'yatco_get_settings_' . $this->id, array() );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		return apply_filters( 'yatco_get_sections_' . $this->id, array() );
	}

	/**
	 * Output sections
	 */
	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=ya-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
	}

	/**
	 * Output the settings
	 */
	public function output() {
		$settings = $this->get_settings();

		YA_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings();
		YA_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'yatco_update_options_' . $this->id . '_' . $current_section );
		}
	}
}

endif;
