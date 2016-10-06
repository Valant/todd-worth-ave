<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$years_list = array_combine(range(date("Y"), 1910), range(date("Y"), 1910));
$years_list = array('' => '') + $years_list;

$currency_code_options = ya_get_currencies();

foreach ( $currency_code_options as $code => $name ) {
  $currency_code_options[ $code ] = $name . ' (' . ya_get_currency_symbol( $code ) . ')';
}

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
                  'options'     => ya_get_speed_units()
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
                  'options'     => ya_get_speed_units()
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
                  'type'        => 'text'
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
                  //'class'       => 'ya-enhanced-color',
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
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'FlyBridge',
                  'label'       => __( 'Fly Bridge', 'yatco' ),
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
                  ) 
                ),
                array(
                  'id'          => 'Helipad',
                  'label'       => __( 'Helip Pad', 'yatco' ),
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
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
                  'type'        => 'text'
                ),
                array(
                  'id'          => 'ModelYear',
                  'label'       => __( 'Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list,
                ),
                array(
                  'id'          => 'YearBuilt',
                  'label'       => __( 'Year Built', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list
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
                  'options'     => ya_get_weight_units()
                ),
                array(
                  'id'          => 'FuelCapacity',
                  'label'       => __( 'Fuel Capacity', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_volume_units()
                ),
                array(
                  'id'          => 'FuelConsumption',
                  'label'       => __( 'Fuel Consumption', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_volume_units()
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
                  'options'     => ya_get_volume_units()
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
                  'options'     => ya_get_volume_units()
                ),
          )
      ),
    'contact'      => array(
          array(
                array(
                  'id'          => 'CaptainOnBoard',
                  'label'       => __( 'Captain Aboard', 'yatco' ),
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
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
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
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
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'HeadRoom',
                  'label'       => __( 'Interior Clearance', 'yatco' ),
                ),
                array(
                  'id'          => 'LOD',
                  'label'       => __( 'Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'LWL',
                  'label'       => __( 'Lentgh Waterline', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'MaxDraft',
                  'label'       => __( 'Max Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'MinDraft',
                  'label'       => __( 'Min Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
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
                  'id'            => 'EngineDateHoursRegistered1',
                  'label'         => __( 'Engine Hours Date 1', 'yatco' ),
                  'type'          => 'number',
                  'wrapper_class' => 'col-2',
                  'style'         => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered2',
                  'label'       => __( 'Engine Hours Date 2', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered3',
                  'label'       => __( 'Engine Hours Date 3', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateHoursRegistered4',
                  'label'       => __( 'Engine Hours Date 4', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineDateOverhaul1',
                  'label'       => __( 'Engine 1 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul2',
                  'label'       => __( 'Engine 2 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul3',
                  'label'       => __( 'Engine 3 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineDateOverhaul4',
                  'label'       => __( 'Engine 4 Overhaul Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
          ),
          array(
                array(
                  'id'          => 'EngineHorsePower1',
                  'label'       => __( 'Engine 1 HP', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower2',
                  'label'       => __( 'Engine 2 HP', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower3',
                  'label'       => __( 'Engine 3 HP', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHorsePower4',
                  'label'       => __( 'Engine 4 HP', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineHours1',
                  'label'       => __( 'Engine 1 Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours2',
                  'label'       => __( 'Engine 2 Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours3',
                  'label'       => __( 'Engine 3 Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineHours4',
                  'label'       => __( 'Engine 4 Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineOverhaulHours1',
                  'label'       => __( 'Engine 1 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours2',
                  'label'       => __( 'Engine 2 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours3',
                  'label'       => __( 'Engine 3 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'EngineOverhaulHours4',
                  'label'       => __( 'Engine 4 Overhaul Hours', 'yatco' ),
                  'type'        => 'number',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 150px;',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineSerialNumber1',
                  'label'       => __( 'Engine 1 Serial Number', 'yatco' ),
                  'wrapper_class' => 'col-2',
                ),
                array(
                  'id'          => 'EngineSerialNumber2',
                  'label'       => __( 'Engine 2 Serial Number', 'yatco' ),
                  'wrapper_class' => 'col-2',
                ),
                array(
                  'id'          => 'EngineSerialNumber3',
                  'label'       => __( 'Engine 3 Serial Number', 'yatco' ),
                  'wrapper_class' => 'col-2',
                ),
                array(
                  'id'          => 'EngineSerialNumber4',
                  'label'       => __( 'Engine 4 Serial Number', 'yatco' ),
                  'wrapper_class' => 'col-2',
                ),                
          ),
          array(
                array(
                  'id'          => 'EngineYear1',
                  'label'       => __( 'Engine 1 Year', 'yatco' ),
                  'type'        => 'select',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list
                ),
                array(
                  'id'          => 'EngineYear2',
                  'label'       => __( 'Engine 2 Year', 'yatco' ),
                  'type'        => 'select',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list
                ),
                array(
                  'id'          => 'EngineYear3',
                  'label'       => __( 'Engine 3 Year', 'yatco' ),
                  'type'        => 'select',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list
                ),
                array(
                  'id'          => 'EngineYear4',
                  'label'       => __( 'Engine 4 Year', 'yatco' ),
                  'type'        => 'select',
                  'wrapper_class' => 'col-2',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list
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
    'historical'   => array(
          array(
                array(
                  'id'          => 'FormerNames',
                  'label'       => __( 'Former Names', 'yatco' ),
                ),
                array(
                  'id'          => 'FormerNameDateChange',
                  'label'       => __( 'Date', 'yatco' ),                  
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'LastKnownFlag',
                  'label'       => __( 'Last Known Flag', 'yatco' ),
                ),
                array(
                  'id'          => 'HullProjectManager',
                  'label'       => __( 'Build Manager', 'yatco' ),
                ),
                array(
                  'id'          => 'RefitType',
                  'label'       => __( 'Refit Type', 'yatco' ),
                ),
                array(
                  'id'          => 'RefitYear',
                  'label'       => __( 'Refit Year', 'yatco' ),
                  'type'        => 'select',
                  'style'       => 'width: 200px;',
                  'options'     => $years_list,
                ),
                array(
                  'id'          => 'LastKnownCoordinatesAIS',
                  'label'       => __( 'Last Known Position', 'yatco' ),
                ),
            )
      ),
    'location'     => array(
          array(
                array(
                  'id'                => 'LocationCountry',
                  'label'             => __( 'Country', 'yatco' ),
                  'type'              => 'select',
                  'style'             => 'width: 50%;',
                  'class'             => 'ya-enhanced-select',
                  'options'           => array('' => '') + ya_get_countries(),
                  'custom_attributes' => array('data-allow_clear' => true)
                ),
                array(
                  'id'          => 'LocationState',
                  'label'       => __( 'State', 'yatco' ),
                ),
                array(
                  'id'          => 'LocationCity',
                  'label'       => __( 'City', 'yatco' ),
                ),
                array(
                  'id'          => 'LocationRegionName',
                  'label'       => __( 'Region Name', 'yatco' ),
                ),
          )
      ),
    'measurements' => array(
          array(
                array(
                  'id'          => 'GrossTonnage',
                  'label'       => __( 'Gross Tonnage', 'yatco' ),
                  'type'        => 'number',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'BridgeClearance',
                  'label'       => __( 'Air Draft', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'BuilderLength',
                  'label'       => __( 'Build Length', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_length_units()
                ),
                array(
                  'id'          => 'Weight',
                  'label'       => __( 'Weight', 'yatco' ),
                  'type'        => 'units',
                  'options'     => ya_get_weight_units()
                ),
          )
      ),
    'media' => array(
          array(
                array(
                  'id'          => 'ExternalLink',
                  'label'       => __( 'More Info', 'yatco' ),
                  'type'        => 'url'
                ),
                array(
                  'id'          => 'PDFUrl',
                  'label'       => __( 'PDF URL', 'yatco' ),
                  'type'        => 'url'
                ),
                array(
                  'id'          => 'VirutalTourURL',
                  'label'       => __( 'Virtual Tour', 'yatco' ),
                  'type'        => 'url'
                ),
          )
    ),
    'official_num' => array(
          array(
                array(
                  'id'          => 'CoastGuardNumber',
                  'label'       => __( 'USCG No', 'yatco' )
                ),
                array(
                  'id'          => 'RegistrationNumber',
                  'label'       => __( 'Registration Number', 'yatco' )
                ),
                array(
                  'id'          => 'IMONumber',
                  'label'       => __( 'IMO Number', 'yatco' )
                ),
                array(
                  'id'          => 'MMSINumber',
                  'label'       => __( 'MMSI Number', 'yatco' )
                ),
          )
    ),
    'sale' => array(
          array(
                array(
                  'id'          => 'AskingPrice',
                  'label'       => __( 'Asking Price', 'yatco' )
                ),
                array(
                  'id'          => 'Currency',
                  'label'       => __( 'Currency', 'yatco' ),
                  'type'        => 'select',
                  'options'     => $currency_code_options
                ),                
                array(
                  'id'          => 'DescriptionShowingInstructions',
                  'label'       => __( 'Showing Description', 'yatco' ),
                  'type'        => 'textarea'
                ),
                array(
                  'id'          => 'DockMasterName',
                  'label'       => __( 'Dockmaster', 'yatco' ),
                  'type'        => 'textarea'
                ),
                array(
                  'id'          => 'ListingDate',
                  'label'       => __( 'Listing Date', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'ExpirationDate',
                  'label'       => __( 'Listing Expiration', 'yatco' ),
                  'type'        => 'text',
                  'class'       => 'ya-datepicker',
                  'style'       => 'width: 150px;',
                ),
                array(
                  'id'          => 'Cruising',
                  'label'       => __( 'Available in US', 'yatco' ),
                  'type'        => 'checkbox',
                  'description' => __( 'Enable if the vessel is available in US.', 'yatco' )
                ),
                array(
                  'id'          => 'TaxPaid',
                  'label'       => __( 'Tax Paid', 'yatco' ),
                  'type'        => 'radio',
                  'options' => array(
                    'Yes' => __( 'Yes', 'yatco' ),
                    'No'  => __( 'No', 'yatco' ),
                  ) 
                ),
          ),
          array(
                array(
                  'id'          => 'DescriptionBrokerTeaser',
                  'label'       => __( 'Sales Description', 'yatco' ),
                  'type'        => 'textarea'
                ),
                array(
                  'id'          => 'SalesPerson',
                  'label'       => __( 'Sales Agent', 'yatco' ),
                ),
                array(
                  'id'          => 'SalesPersonCellPhone',
                  'label'       => __( 'Sales Agent Cell', 'yatco' ),
                ),
                array(
                  'id'          => 'SalesPersonEmail',
                  'label'       => __( 'Sales Agent Email', 'yatco' ),
                ),
                array(
                  'id'          => 'SalesPersonPhone',
                  'label'       => __( 'Sales Agent Direct Phone', 'yatco' ),
                ),
                
          )
    ),
    'additional'   => array(
        array(
                array(
                  'id'          => 'Cruising',
                  'label'       => __( 'Cruising?', 'yatco' ),
                  'type'        => 'checkbox',
                  'description' => __( 'Enable if the vessel is a cruise.', 'yatco' )
                ),
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