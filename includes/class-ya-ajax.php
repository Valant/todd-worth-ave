<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Yatco YA_AJAX
 *
 * AJAX Event Handler
 *
 * @class 		YA_AJAX
 * @version		1.0.0
 * @package		Yatco/Classes
 * @category	Class
 * @author 		Valant
 */
class YA_AJAX {

	/**
	 * Hook in methods
	 */
	public static function init() {

		// yatco_EVENT => nopriv
		$ajax_events = array(
			'load_vessels'                          => true,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_yatco_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_yatco_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	public static function load_vessels()
	{
		$answer      = array();
		if( isset($_POST['page_id']) && !empty($_POST['page_id'])){
			$page_id     = (int)$_POST['page_id'];
			$api         = new YA_API();
			$vessel_list = $api->load_page($page_id);
			if($vessel_list){
				foreach ($vessel_list as $vessel) {
					$VesselID = $vessel->VesselID;
					$vessel_detail = $api->load_vessel_detail($VesselID);
					if($vessel_detail !== false){
						$answer[] = $api->save_vessel();
					}else{
						$a['status'] = 'failed';
						$a['VesselID'] = $vessel->VesselID;
						$a['Boatname'] = $vessel->Boatname;
						$answer[] = $a;
					}
				}
			}
		}
		wp_send_json( $answer );
		die;
	}

	
}

YA_AJAX::init();
