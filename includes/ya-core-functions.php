<?php
/**
 * Yatco Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		Valant
 * @category 	Core
 * @package 	Yatco/Functions
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( 'ya-cron-functions.php' );
include_once( 'ya-vessel-functions.php' );
include_once( 'ya-meta-box-functions.php' );

/**
 * Retrieve page ids - used for myaccount, edit_address, shop, cart, checkout, pay, view_order, terms. returns -1 if no page is found
 *
 * @param string $page
 * @return int
 */
function ya_get_page_id( $page ) {

  $page = apply_filters( 'yatco_get_' . $page . '_page_id', get_option('yatco_' . $page . '_page_id' ) );

  return $page ? absint( $page ) : -1;
}

if ( ! function_exists( 'is_ajax' ) ) {

  /**
   * is_ajax - Returns true when the page is loaded via ajax.
   *
   * @access public
   * @return bool
   */
  function is_ajax() {
    return defined( 'DOING_AJAX' );
  }
}

function ya_api_get_total_vessels_count()
{
  return 12547;
}

function ya_get_individual_meta(){
  $meta = array(
    'VesselID',
    'Builder',
    'AskingPrice',
    'AskingPrice',
    'LOAFeet',
    'LOAMeters',
    'NumBerths',
    'NumHeads',
    'NumSleeps',
    );
  return apply_filters('ya_individual_meta', $meta);
}
function ya_remove_api_filds(){
  $meta = array(
    'VesselID',
    'VesselSections',
    'DescriptionShortDescription',
    'Boatname',
    'MainCategory',
    'SubCategory',
    'CompanyName',
    'Gallery',
    'ProfileURL',
    'Videos',
    );
  return apply_filters('ya_remove_api_filds', $meta);
}

function ya_remove_attributes($text = '')
{
  return preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $text);
}
/**
 * Clean variables
 *
 * @param string $var
 * @return string
 */
function ya_clean( $var ) {
  return sanitize_text_field( $var );
}