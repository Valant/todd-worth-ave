<?php
/**
 * Yatco General Settings
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'YA_Settings_Api' ) ) :

/**
 * YA_Admin_Settings_General
 */
class YA_Settings_Api extends YA_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'api';
		$this->label = __( 'API', 'yatco' );

		add_filter( 'yatco_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'yatco_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'yatco_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters( 'yatco_general_settings', array(
			array( 'title' => __( 'Yatco API', 'yatco' ), 'type' => 'title', 'desc' => '', 'id' => 'api_options' ),

			array(
				'title'   => __( 'API key', 'yatco' ),
				'desc'    => '',
				'id'      => 'yatco_api_key',
				'css'     => 'min-width: 350px;',
				'default' => '',
				'type'    => 'text'
			),

			array( 'type' => 'sectionend', 'id' => 'api_options'),

		) );

		return apply_filters( 'yatco_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();

		YA_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new YA_Settings_Api();
