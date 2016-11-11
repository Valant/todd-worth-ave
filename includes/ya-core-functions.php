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
include_once( 'ya-conversion-functions.php' );

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
    'VesselSections',
    'DescriptionShortDescription',
    'Boatname',
    'MainCategory',
    'SubCategory',
    'CompanyName',
    'Builder',
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
          'meters'  => __('Meters', 'yatco'),
          'feet'    => __('Feet','yatco'),
        );
}

/**
 * Get all volume units.
 * @return array
 */
function ya_get_volume_units() {
  return array(
            'gal'    => __('Gallons','yatco'),
            'ltr'     => __('Liters', 'yatco')
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

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @param string|array $var
 * @return string|array
 */
function ya_clean( $var ) {
  if ( is_array( $var ) ) {
    return array_map( 'ya_clean', $var );
  } else {
    return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
  }
}

function ya_get_source($post_id){
  $source   = get_post_meta($post_id, '_source', true);
  $VesselID = get_post_meta($post_id, 'VesselID', true);
  $name   =  $source == 'yatco' || !empty($VesselID) ? __('Yatco', 'yatco') : __('Local', 'yatco');
  return $name;
}

function ya_get_flags()
{
    $list = get_option('vessel_flags_list', null);
    if ($list === null) {
        $list = array_merge(array('', 'United States'),array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"));
        global $wpdb;
        $localList = $wpdb->get_col("select meta_value from {$wpdb->postmeta} where meta_key='Flag' GROUP BY meta_value;");

        foreach ($localList as $flag) {
            if ($flag) {
                $list[$flag] = $flag;
            }

        }
        ya_save_flags($list);
    }
    return $list;
}

function ya_save_flags($list)
{
    update_option('vessel_flags_list', $list);
}

function ya_get_photo_categories()
{
    return array(
        'Exterior',
        'Interior',
        'Accomodations',
        'Toys',
        'Lifestyle',
        'Epicure',
        'Operations',
        'Design Details',
    );
}