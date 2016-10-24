<?php
/**
 * Measurement Conversions
 *
 * http://www.metric-conversions.org/
 *
 * @author 		Valant
 * @category 	Conversion
 * @package 	Yatco/Functions
 * @version   1.0.0
 */


/**
 * Calculations for measurement conversions
 * @param  int $value
 * @param  string $from
 * @param  string $to
 * @return float
 */
function ya_convert_measurement($value = 0, $from = '', $to = '') {
  if( empty($from) || empty($to) ) return false;

  $func_name = strtolower($from) . '_to_' . strtolower($to);
  if ( function_exists($func_name)) {
    $result = call_user_func($func_name, $value);
    return $result;
  }else{
    return false;
  }
}


/*************Kilograms Conversion***********/

/**
 * Convert Kilograms to Pounds (kg to lbs)
 * @return float
 */
function kilos_to_pounds($kg){
    return round($kg * 2.20462, 4);
}

/**
 * Convert Kilograms to Short Tons (US)
 * @return float
 */
function kilos_to_shortton($kg){
  return round($kg * 0.0011023, 4);
}
/**
 * Convert Kilograms to Metric Tons (or Tonnes)
 * @return float
 */
function kilos_to_tonne($kg){
  return round($kg / 1000, 4);
}


/*************Pounds Conversion***********
* The imperial (avoirdupois, or international) 
* pound is officially defined as 453.59237 grams.
*****************************************/

/**
 * Convert Pounds to Kilograms (lbs to kg)
 * @return float
 */
function pounds_to_kilos($lb){
  return round($lb / 2.2046, 4);
}

/**
 * Convert Pounds to Short Tons (US)
 * @return float
 */
function pounds_to_shortton($lb){
  return round($lb * 0.00050000, 4);
}


/**
 * Convert Pounds to Metric Tons (or Tonnes)
 * @return float
 */
function pounds_to_tonne($lb){
  return round($lb / 2204.6, 4);
}

/********Short Tons (US) Conversion*******
* United States measurement also known as
* a short ton that equals 2,000 pounds
*****************************************/

/**
 * Convert Short Tons (US) to Kilograms
 * @return float
 */
function shortton_to_kilos($st){
  return round($st / 0.0011023, 4);
}

/**
 * Convert Short Tons (US) to Pounds
 * @return float
 */
function shortton_to_pounds($st){
  return round($st * 2000.0, 4);
}

/**
 * Convert Short Tons (US) to Tonnes
 * @return float
 */
function shortton_to_tonne($st){
  return round($st / 1.1023, 4);
} 

/***********Tonnes Conversion************/

/**
 * Convert Tonnes to Kilograms
 * @return float
 */
function tonne_to_kilos($t){
  return round($t / 0.0010000, 4);
}

/**
 * Convert Tonnes to Pounds
 * @return float
 */
function tonne_to_pounds($t){
  return round($t * 2204.6, 4);
}


/**
 * Convert Tonnes to Short Tons (US)
 * @return float
 */
function tonne_to_shortton($t){
  return round($t * 1.1023, 4);
}

/**********Knots conversion*********
* Knots are a speed measurement 
* that is nautical miles per hour. 
************************************/
/**
 * Convert Knots to Meters per hour
 * @return float
 */
function knots_to_mph($knots){
  return round($knots * 1.151, 4);
}

/**
 * Convert Meters per hour to Knots
 * @return float
 */
function mph_to_knots($mph){
  return round($mph / 1.151, 4);
}

/**********Meters Conversion*********
* 1 m is equivalent to 1.0936 yards, or 39.370 inches.
************************************/
/**
 * Convert Meters to Feet
 * @return float
 */
function meters_to_feet($m){
  return round($m * 3.2808, 4);
}

/**
 * Convert Feet to Meters (ft to m)
 * @return float
 */
function feet_to_meters($ft){
  return round($ft / 3.2808, 4);
}


/***************US Gallons (Liquid) Conversion**************************
* A US capacity measure (for liquid) equal to 4 quarts or 3.785 liters. 
* Note also there are different measures of US dry gallons and UK gallons.
************************************************************************/
/**
 * Convert US Gallons (Liquid) to Liters
 * @return float
 */
function gal_to_ltr($gal){
  return round($gal / 0.26417, 4);
}

/**
 * Convert Liters to US Gallons (Liquid)
 * @return float
 */
function ltr_to_gal($l){
  return round($l * 0.26417, 4);
}