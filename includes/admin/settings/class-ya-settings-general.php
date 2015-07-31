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

if ( ! class_exists( 'YA_Settings_General' ) ) :

/**
 * YA_Admin_Settings_General
 */
class YA_Settings_General extends YA_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'yatco' );

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
		$desc = __('If you want reload all vessels', 'yatco') . ' <a href="'.admin_url('index.php?page=ya-load-vessels').'">' .__('click here', 'yatco'). '</a>';
		$settings = apply_filters( 'yatco_general_settings', array(
			array( 'title' => __( 'General Options', 'yatco' ), 'type' => 'title', 'desc' => $desc, 'id' => 'general_options' ),			

			/*array(
				'title'   => __( 'Lock editing', 'yatco' ),
				'desc'    => __( 'Enable this if you want lock editing the loaded vessels.', 'yatco' ),
				'id'      => 'yatco_api_enabled',
				'type'    => 'checkbox',
				'default' => 'no',
			),*/

			array( 'type' => 'sectionend', 'id' => 'general_options'),


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

return new YA_Settings_General();
