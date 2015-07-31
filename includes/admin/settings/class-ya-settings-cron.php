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

if ( ! class_exists( 'YA_Settings_Cron' ) ) :

/**
 * YA_Admin_Settings_General
 */
class YA_Settings_Cron extends YA_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'cron';
		$this->label = __( 'Cron', 'yatco' );

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

		$desc = __( 'WP-cron is a virtual cron that only works when the page is loaded. WP-cron is first loaded by WordPress when a page is requested on the front or backend of a site at which point WP-cron displays the necessary page to the site visitor.', 'yatco' ) . '<br>'.
			__('The best way to optimize the efficiency of your WordPress cron jobs is to disable WP-cron and set up a normal cron job through cPanel which will run every hour.', 'yatco')
			 . ' <br><a href="https://support.hostgator.com/articles/specialized-help/technical/wordpress/how-to-replace-wordpress-cron-with-a-real-cron-job">'.__( 'This article', 'yatco' ).'</a>' . __(' will help walk you through your first time replacing a WP-cron with a Server cron job.', 'yatco');
		
		$settings = apply_filters( 'yatco_general_settings', array(
			array( 'title' => __( 'Schedule', 'yatco' ), 'type' => 'title', 'desc' => $desc, 'id' => 'schedule_options' ),

			array(
				'title'   => __( 'Cron recurrence', 'yatco' ),
				'desc'    => '',
				'id'      => 'yatco_cron_schedule',
				'css'     => 'min-width: 350px;',
				'default' => 'two_hourly',
				'type'    => 'select',
				'options' => array(
					'hourly'     => __('Hourly', 'yatco'),
					'two_hourly' => __('Once in two hours', 'yatco'),
					'twicedaily' => __('Twice daily', 'yatco'),
					'daily'      => __('Daily', 'yatco'),
					)
			),

			array( 'type' => 'sectionend', 'id' => 'schedule_options'),

		) );

		return apply_filters( 'yatco_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();

		YA_Admin_Settings::save_fields( $settings );
		YA_Install::create_cron_jobs();
	}

}

endif;

return new YA_Settings_Cron();
