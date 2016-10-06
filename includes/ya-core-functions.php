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
    'Builder',
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

/**
 * Get all countries.
 * @return array
 */
function ya_get_countries() {
    $countries = apply_filters( 'yatco_countries', include( YA()->plugin_path() . '/i18n/countries.php' ) );
    if ( apply_filters( 'yatco_sort_countries', true ) ) {
      asort( $countries );
    }
  return $countries;
}

/**
 * Get all weight units.
 * @return array
 */
function ya_get_weight_units() {
  return array(
          'kilos'    => __('Kilos', 'yatco'),
          'pounds'   => __('Pounds', 'yatco'),
          'shortton' => __('Short Ton', 'yatco'),
          'tonne'    => __('Tonne', 'yatco'),
          );
}

/**
 * Get all speed units.
 * @return array
 */
function ya_get_speed_units() {
  return array(
          'knots'    => __('Knots', 'yatco'),
          'mph'      => __('MPH', 'yatco'),
          );
}

/**
 * Get all length units.
 * @return array
 */
function ya_get_length_units() {
  return array(
          'meters'  => __('Meters', 'yatco')
          'feet'    => __('Feet','yatco'),
        );
}

/**
 * Get all volume units.
 * @return array
 */
function ya_get_volume_units() {
  return array(
            'gallons'    => __('Gallons','yatco'),
            'liters'     => __('Liters', 'yatco')
        );
}

/**
 * Get Base Currency Code.
 *
 * @return string
 */
function ya_get_currency() {
  return apply_filters( 'ya_currency', get_option('vessel_currency') );
}

/**
 * Get full list of currency codes.
 *
 * @return array
 */
function ya_get_currencies() {
  return array(
            'EUR' => __( 'Euro', 'yatco' ),
            'USD' => __( 'United States dollar', 'yatco' ),
        );
}

/**
 * Get Currency symbol.
 *
 * @param string $currency (default: '')
 * @return string
 */
function ya_get_currency_symbol( $currency = '' ) {

  if ( ! $currency ) {
    $currency = ya_get_currency();
  }
  
  $symbols = array(
            'EUR' => '&euro;',
            'USD' => '&#36;',
        );
  
  $currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
  return $currency_symbol;
}