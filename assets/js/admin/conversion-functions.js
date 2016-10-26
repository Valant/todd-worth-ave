function ya_convert_measurement(value, from, to) {
  if( typeof value == 'undefined' || typeof from == 'undefined' || typeof to == 'undefined'  ){
  	return false;
  }

  var conversion_name = from.toLowerCase() + '_to_' + to.toLowerCase();
  var v = 0;
	switch(conversion_name) {
		case 'kilos_to_pounds':
			v =  value * 2.20462, 4;
			break;
		case 'kilos_to_shortton':
			v =  value * 0.0011023, 4;
			break;
		case 'kilos_to_tonne':
			v =  value / 1000, 4;
			break;
		case 'pounds_to_kilos':
			v =  value / 2.2046, 4;
			break;
		case 'pounds_to_shortton':
			v =  value * 0.00050000, 4;
			break;
		case 'pounds_to_tonne':
			v =  value / 2204.6, 4;
			break;
		case 'shortton_to_kilos':
			v =  value / 0.0011023, 4;
			break;
		case 'shortton_to_pounds':
			v =  value * 2000.0, 4;
			break;
		case 'shortton_to_tonne':
			v =  value / 1.1023, 4;
			break;
		case 'tonne_to_kilos':
			v =  value / 0.0010000, 4;
			break;
		case 'tonne_to_pounds':
			v =  value * 2204.6, 4;
			break;
		case 'tonne_to_shortton':
			v =  value * 1.1023, 4;
			break;
		case 'knots_to_mph':
			v =  value * 1.151, 4;
			break;
		case 'mph_to_knots':
			v =  value / 1.151, 4;
			break;
		case 'meters_to_feet':
			v =  value * 3.2808, 4;
			break;
		case 'feet_to_meters':
			v =  value / 3.2808, 4;
			break;
		case 'gal_to_ltr':
			v =  value / 0.26417, 4;
			break;
		case 'ltr_to_gal':
			v =  value * 0.26417, 4;
			break;
		default:
			return false;
			break;
	}

	return parseFloat( v.toFixed(4) );
    
}