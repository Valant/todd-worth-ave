<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function get_vessel_specification_categories()
{
  return array(
    'general'      => __( 'General Information', 'yatco' ),
    'arrangement'  => __( 'Arrangement', 'yatco' ),
    'build_data'   => __( 'Build Data', 'yatco' ),
    'capacities'   => __( 'Capacities', 'yatco' ),
    'contact'      => __( 'Contact Information', 'yatco' ),
    'crew_areas'   => __( 'Crew Areas', 'yatco' ),
    'dimensions'   => __( 'Dimensions', 'yatco' ),
    'engine'       => __( 'Engine', 'yatco' ),
    'guest_area'   => __( 'Guest Areas', 'yatco' ),
    'location'     => __( 'Location Information', 'yatco' ),
    'measurements' => __( 'Measurements', 'yatco' ),
    'additional'   => __( 'Additional Information', 'yatco' )
    );
}
function get_vessel_specification_fields()
{
  $yesrs_list = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
  $yesrs_list = array_merge(array('' => ''), $yesrs_list);
  return array(
    'general'      => array(
          array(
                array(
                  'id'          => 'VesselID',
                  'label'       => __( 'Vessel ID', 'yatco' ),
                  'type'        => 'text',
                  'custom_attributes' => array(
                    'readonly' => 'true'
                    )
                ),
                array(
                  'id'          => 'VesselType',
                  'label'       => __( 'Vessel Type', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    'motor' => __( 'Motor', 'yatco' ),
                    'sail'  => __( 'Sail', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'AgreementType',
                  'label'       => __( 'Agreement Type', 'yatco' )
                ),
                array(
                  'id'          => 'ApproxPriceFormatted',
                  'label'       => __( 'Approx Price', 'yatco' )
                ),
                array(
                  'id'          => 'Condition',
                  'label'       => __( 'Condition', 'yatco' )
                ),
                array(
                  'id'          => 'CruiseSpeed',
                  'label'       => __( 'Cruise Speed', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                    'knots' => __('Knots','yatco'),
                    'mph' => __('MPH', 'yatco')
                    )
                ),
                array(
                  'id'          => 'CruiseSpeedRange',
                  'label'       => __( 'Cruise Speed Range', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'MaxSpeed',
                  'label'       => __( 'Max Speed', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                    'knots' => __('Knots','yatco'),
                    'mph' => __('MPH', 'yatco')
                    )
                ),
                array(
                  'id'          => 'MaxSpeedRange',
                  'label'       => __( 'Max Speed Range', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'Flag',
                  'label'       => __( 'Flag', 'yatco' ),
                  'type'        => 'select',
                  'class'       => 'ya-enhanced-select',
                  'style'       => 'width: 50%;',
                  'options'     => ya_get_countries()
                ),                
            ),
          array(
                array(
                  'id'          => 'HullFinish',
                  'label'       => __( 'Hull Finish', 'yatco' )
                ),
                array(
                  'id'          => 'HullHullColor',
                  'label'       => __( 'Hull Color', 'yatco' ),
                  'class'       => 'ya-enhanced-color',
                ),
                array(
                  'id'          => 'HullInteriorDesigner',
                  'label'       => __( 'Decorator', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1' => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
          ),
        ),
    'arrangement'  => array(
          array(
                array(
                  'id'          => 'Cockpit',
                  'label'       => __( 'Cockpit', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1' => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'FlyBridge',
                  'label'       => __( 'Fly Bridge', 'yatco' ),
                  'type'        => 'radio',
                  'options' => array(
                    'yes' => __( 'Yes', 'yatco' ),
                    'no'  => __( 'No', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'Helipad',
                  'label'       => __( 'Helip Pad', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1' => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
                 array(
                  'id'          => 'HullHullConfiguration',
                  'label'       => __( 'Configuration', 'yatco' )
                ),
                array(
                  'id'          => 'VesselSections',
                  'label'       => __( 'Sections', 'yatco' )
                ),
                 array(
                  'id'          => 'VesselTop',
                  'label'       => __( 'Top', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1'  => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  )
                ),
          ),
      ),
    'build_data'   => array(
        array(
                array(
                  'id'          => 'Model',
                  'label'       => __( 'Model', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1'  => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'ModelYear',
                  'label'       => __( 'Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list,
                ),
                array(
                  'id'          => 'YearBuilt',
                  'label'       => __( 'Year Built', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list
                ),
                array(
                  'id'          => 'Builder',
                  'label'       => __( 'Builder', 'yatco' ),
                  'type'        => 'taxonomy',
                  'taxonomy'    => 'vessel_builder',
                  'class'       => 'ya-enhanced-select',
                  'style'       => 'width: 50%;',
                ),  

        ),
        array(
                array(
                  'id'          => 'HullID',
                  'label'       => __( 'Hull ID', 'yatco' )
                ),
                array(
                  'id'          => 'HullDeckMaterial',
                  'label'       => __( 'Deck Material', 'yatco' )
                ),
                array(
                  'id'          => 'HullExteriorDesigner',
                  'label'       => __( 'Stylist', 'yatco' )
                ),
                array(
                  'id'          => 'HullHullDesigner',
                  'label'       => __( 'Naval Architect', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1'  => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'HullHullMaterial',
                  'label'       => __( 'Hull Material', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1'  => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
          )
      ),
    'capacities'   => array(
        array(
                array(
                  'id'          => 'BallastType',
                  'label'       => __( 'Ballast Type', 'yatco' )
                ),
                array(
                  'id'          => 'BallastWeight',
                  'label'       => __( 'Ballast', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'kilos'    => __('Kilos','yatco'),
                        'pounds'   => __('Pounds', 'yatco'),
                        'shortton' => __('ShortTon', 'yatco'),
                        'tonne'    => __('Tonne', 'yatco'),
                    )
                ),
                array(
                  'id'          => 'FuelCapacity',
                  'label'       => __( 'Fuel Capacity', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'gallons'    => __('Gallons','yatco'),
                        'liters'     => __('Liters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'FuelConsumption',
                  'label'       => __( 'Fuel Consumption', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'gallons'    => __('Gallons','yatco'),
                        'liters'     => __('Liters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'FuelConsumptionRPM',
                  'label'       => __( 'GPH/LPH', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'FuelType',
                  'label'       => __( 'Fuel Type', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'HoldingTank',
                  'label'       => __( 'Black Water', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'gallons'    => __('Gallons','yatco'),
                        'liters'     => __('Liters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'HullFuelTankMaterial',
                  'label'       => __( 'Tank Material', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'HullWaterTankMaterial',
                  'label'       => __( 'Fresh Water Tank', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'WaterCapacity',
                  'label'       => __( 'Fresh Water', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'gallons'    => __('Gallons','yatco'),
                        'liters'     => __('Liters', 'yatco')
                    )
                ),
          )
      ),
    'contact'      => array(
          array(
                array(
                  'id'          => 'CaptainOnBoard',
                  'label'       => __( 'Captain Aboard', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'CaptainsName',
                  'label'       => __( 'Captain', 'yatco' ),
                  'type'        => 'text',
                ),
          )
      ),
    'crew_areas'   => array(
          array(
                array(
                  'id'          => 'CaptainQuarters',
                  'label'       => __( 'Captain Quarters', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'NumBerths',
                  'label'       => __( 'Guest Berths', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'NumCrewBerths',
                  'label'       => __( 'Crew Berths', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'NumCrewHeads',
                  'label'       => __( 'Crew Heads', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'NumCrewQuarters',
                  'label'       => __( 'Crew Quarters', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'NumCrewSleeps',
                  'label'       => __( 'Crew', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
          )
      ),
    'dimensions'   => array(
          array(
                array(
                  'id'          => 'Beam',
                  'label'       => __( 'Beam', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'feet'    => __('Feet','yatco'),
                        'meters'  => __('Meters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'HeadRoom',
                  'label'       => __( 'Interior Clearance', 'yatco' ),
                ),
                array(
                  'id'          => 'LOD',
                  'label'       => __( 'Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'feet'    => __('Feet','yatco'),
                        'meters'  => __('Meters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'LWL',
                  'label'       => __( 'Lentgh Waterline', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'feet'    => __('Feet','yatco'),
                        'meters'  => __('Meters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'MaxDraft',
                  'label'       => __( 'Max Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'feet'    => __('Feet','yatco'),
                        'meters'  => __('Meters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'MinDraft',
                  'label'       => __( 'Min Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => array(
                        'feet'    => __('Feet','yatco'),
                        'meters'  => __('Meters', 'yatco')
                    )
                ),
                array(
                  'id'          => 'LOAFeet',
                  'label'       => __( 'Length Overall', 'yatco' ),
                ),
            )
      ),
    'engine'       => array(
          array(
                array(
                  'id'          => 'EngineEngineCount',
                  'label'       => __( 'Engine Count', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineEngineModel',
                  'label'       => __( 'Engine Model', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'EngineManufacturer',
                  'label'       => __( 'Engine Manufacturer', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'EngineType',
                  'label'       => __( 'Engine Type', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'PropulsionType',
                  'label'       => __( 'Propulsion Type', 'yatco' ),
                  'type'        => 'select',
                  'options'     => array(
                        '1'    => __('1','yatco'),
                        '2'     => __('2', 'yatco')
                    )
                ),
                array(
                  'id'          => 'RPMCruiseSpeed',
                  'label'       => __( 'RPM Cruise Speed', 'yatco' ),
                ),
                array(
                  'id'          => 'RPMMaxSpeed',
                  'label'       => __( 'RPM Max Speedt', 'yatco' ),
                ),
          ),
          array(
                array(
                  'id'          => 'EngineDateHoursRegistered1',
                  'label'       => __( 'Engine Hours Date 1', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered2',
                  'label'       => __( 'Engine Hours Date 2', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered3',
                  'label'       => __( 'Engine Hours Date 3', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered4',
                  'label'       => __( 'Engine Hours Date 4', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineDateOverhaul1',
                  'label'       => __( 'Engine 1 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul2',
                  'label'       => __( 'Engine 2 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul3',
                  'label'       => __( 'Engine 3 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul4',
                  'label'       => __( 'Engine 4 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
          ),
          array(
                array(
                  'id'          => 'EngineHorsePower1',
                  'label'       => __( 'Engine 1 HP', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower2',
                  'label'       => __( 'Engine 2 HP', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower3',
                  'label'       => __( 'Engine 3 HP', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower4',
                  'label'       => __( 'Engine 4 HP', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineHours1',
                  'label'       => __( 'Engine 1 Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours2',
                  'label'       => __( 'Engine 2 Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours3',
                  'label'       => __( 'Engine 3 Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours4',
                  'label'       => __( 'Engine 4 Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineOverhaulHours1',
                  'label'       => __( 'Engine 1 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours2',
                  'label'       => __( 'Engine 2 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours3',
                  'label'       => __( 'Engine 3 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours4',
                  'label'       => __( 'Engine 4 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineSerialNumber1',
                  'label'       => __( 'Engine 1 Serial Number', 'yatco' ),
                ),
                array(
                  'id'          => 'EngineSerialNumber2',
                  'label'       => __( 'Engine 2 Serial Number', 'yatco' ),
                ),
                array(
                  'id'          => 'EngineSerialNumber3',
                  'label'       => __( 'Engine 3 Serial Number', 'yatco' ),
                ),
                array(
                  'id'          => 'EngineSerialNumber4',
                  'label'       => __( 'Engine 4 Serial Number', 'yatco' ),
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineYear1',
                  'label'       => __( 'Engine 1 Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list
                ),
                array(
                  'id'          => 'EngineYear2',
                  'label'       => __( 'Engine 2 Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list
                ),
                array(
                  'id'          => 'EngineYear3',
                  'label'       => __( 'Engine 3 Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list
                ),
                array(
                  'id'          => 'EngineYear4',
                  'label'       => __( 'Engine 4 Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $yesrs_list
                ),
          )

      ),
    'guest_area'   => array(
          array(
                array(
                  'id'          => 'NumHeads',
                  'label'       => __( 'Guest Heads', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'NumSleeps',
                  'label'       => __( 'Guests', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'StateRooms',
                  'label'       => __( 'State Rooms', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
            )
      ),
    'location'     => array(),
    'measurements' => array(),
    'additional'   => array(
        array(
                array(
                  'id'          => 'Classifications',
                  'label'       => __( 'Classifications', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    '1'  => __( '1', 'yatco' ),
                    '2'  => __( '2', 'yatco' ),
                  ) 
                ),
          ),

      )
    );
}