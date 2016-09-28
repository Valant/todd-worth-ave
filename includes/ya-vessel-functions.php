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

  return array(
    'general'      => array(
          array(
                array(
                  'id'       => 'vessel_model',
                  'label'    => __( 'Model', 'yatco' ),
                  'type'     => 'text',
                ),
                array(
                  'id'          => 'AgreementType',
                  'label'       => __( 'Agreement Type', 'yatco' )
                ),
                array(
                  'id'          => 'ApproxPriceFormatted',
                  'label'       => __( 'Approx Price Formatted', 'yatco' )
                ),
                array(
                  'id'          => 'Condition',
                  'label'       => __( 'Condition', 'yatco' )
                ),
                array(
                  'id'          => 'CruiseSpeedKnots',
                  'label'       => __( 'Cruise Speed Knots  ', 'yatco' )
                ),
                array(
                  'id'          => 'CruiseSpeedRange',
                  'label'       => __( 'Cruise Speed Range', 'yatco' )
                ),
                array(
                  'id'          => 'Flag',
                  'label'       => __( 'Flag', 'yatco' ),
                  'type'        => 'select',
                  'options' => array(
                    'usa' => __( 'United States', 'yatco' ),
                    'uk'  => __( 'United Kingdom', 'yatco' ),
                  ) 
                ),                
            ),
          array(
                array(
                  'id'          => 'HullFinish',
                  'label'       => __( 'Hull Finish', 'yatco' )
                ),
                array(
                  'id'          => 'HullHullColor',
                  'label'       => __( 'Hull Color', 'yatco' )
                ),
                array(
                  'id'          => 'HullInteriorDesigner',
                  'label'       => __( 'Hull Interior Designer', 'yatco' )
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
    'build_data'   => array(),
    'capacities'   => array(),
    'contact'      => array(),
    'crew_areas'   => array(),
    'dimensions'   => array(),
    'engine'       => array(),
    'guest_area'   => array(),
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