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
    call_user_func($func_name, $value);
  }
}


/*************Kilograms Conversion***********/

/**
 * Convert Kilograms to Pounds (kg to lbs)
 * @return float
 */
function kilos_to_pounds($kg){
    return $kg * 2.20462;
}
/**
 * Convert Kilograms to Short Tons (US)
 * @return float
 */
function kilos_to_shortton($kg){
  return $kg * 0.0011023;
}
/**
 * Convert Kilograms to Metric Tons (or Tonnes)
 * @return float
 */
function kilos_to_tonne($kg){
  return $kg / 1000;
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
  return $lb / 2.2046;
}

/**
 * Convert Pounds to Short Tons (US)
 * @return float
 */
function pounds_to_shortton($lb){
  return $lb * 0.00050000;
}


/**
 * Convert Pounds to Metric Tons (or Tonnes)
 * @return float
 */
function pounds_to_tonne($lb){
  return $lb / 2204.6;
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
  return $st / 0.0011023;
}

/**
 * Convert Short Tons (US) to Pounds
 * @return float
 */
function shortton_to_pounds($st){
  return $st * 2000.0;
}

/**
 * Convert Short Tons (US) to Tonnes
 * @return float
 */
function shortton_to_tonne($st){
  return $st / 1.1023;
} 

/***********Tonnes Conversion************/

/**
 * Convert Tonnes to Kilograms
 * @return float
 */
function tonne_to_kilos($t){
  return $t / 0.0010000;
}

/**
 * Convert Tonnes to Pounds
 * @return float
 */
function tonne_to_pounds($t){
  return $t * 2204.6;
}


/**
 * Convert Tonnes to Short Tons (US)
 * @return float
 */
function tonne_to_shortton($t){
  return $t * 1.1023;
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
  return $knots * 1852.000;
}

/**
 * Convert Meters per hour to Knots
 * @return float
 */
function mph_to_knots($mph){
  return $mph / 1852.000;
}

/**********Meters Conversion*********
* 1 m is equivalent to 1.0936 yards, or 39.370 inches.
************************************/
/**
 * Convert Meters to Feet
 * @return float
 */
function meters_to_feet($m){
  return $m * 3.2808;
}

/**
 * Convert Feet to Meters (ft to m)
 * @return float
 */
function feet_to_meters($ft){
  return $ft / 3.2808;
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
  return $gal / 0.26417;
}

/**
 * Convert Liters to US Gallons (Liquid)
 * @return float
 */
function ltr_to_gal($l){
  return $l * 0.26417;
}