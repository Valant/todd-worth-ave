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

		$currency_code_options = ya_get_currencies();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name . ' (' . ya_get_currency_symbol( $code ) . ')';
		}

		$desc = __('If you want reload all vessels', 'yatco') . ' <a href="'.admin_url('index.php?page=ya-load-vessels').'">' .__('click here', 'yatco'). '</a>';
		$settings = array(
			array( 'title' => __( 'General Options', 'yatco' ), 'type' => 'title', 'desc' => $desc, 'id' => 'general_options' ),			

			array(
				'title'   => __( 'Weight unit', 'yatco' ),
				'desc'    => __( 'Default unit of weight.', 'yatco' ),
				'id'      => 'vessel_weight_unit',
				'type'    => 'select',
				'default' => 'tonne',
				'options' => ya_get_weight_units()
			),
			array(
				'title'   => __( 'Speed unit', 'yatco' ),
				'desc'    => __( 'Default unit of speed.', 'yatco' ),
				'id'      => 'vessel_speed_unit',
				'type'    => 'select',
				'default' => 'knots',
				'options' => ya_get_speed_units()
			),
			array(
				'title'   => __( 'Length unit', 'yatco' ),
				'desc'    => __( 'Default unit of length.', 'yatco' ),
				'id'      => 'vessel_length_unit',
				'type'    => 'select',
				'default' => 'meters',
				'options' => ya_get_length_units()
			),
			array(
				'title'   => __( 'Volume unit', 'yatco' ),
				'desc'    => __( 'Default unit of volume.', 'yatco' ),
				'id'      => 'vessel_volume_unit',
				'type'    => 'select',
				'default' => 'gallons',
				'options' => ya_get_volume_units()
			),
			array(
				'title'    => __( 'Currency', 'yatco' ),
				'desc'     => __( 'Default currency code.', 'yatco' ),
				'id'       => 'vessel_currency',
				'css'      => 'min-width:350px;',
				'default'  => 'GBP',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'desc_tip' =>  true,
				'options'  => $currency_code_options
			),

			array( 'type' => 'sectionend', 'id' => 'general_options'),


		);

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
