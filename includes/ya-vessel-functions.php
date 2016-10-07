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
    'historical'   => __( 'Historical Data', 'yatco' ),
    'location'     => __( 'Location Information', 'yatco' ),
    'measurements' => __( 'Measurements', 'yatco' ),
    'media'        => __( 'Media', 'yatco' ),
    'official_num' => __( 'Official Numbers', 'yatco' ),
    'sale'         => __( 'Sale Information', 'yatco' ),
    'additional'   => __( 'Additional Information', 'yatco' )
    );
}
function get_vessel_specification_fields()
{
  return include 'meta-boxes/vessel-specification-fields.php';
}

function get_vessel_yatco_relations()
{
    return array(
        'CruiseSpeed'     => 'speed',
        'MaxSpeed'        => 'speed',
        'BallastWeight'   => 'weight',
        'FuelCapacity'    => 'volume',
        'FuelConsumption' => 'volume',
        'HoldingTank'     => 'volume',
        'WaterCapacity'   => 'volume',
        'Beam'            => 'length',
        'LOD'             => 'length',
        'LWL'             => 'length',
        'MaxDraft'        => 'length',
        'MinDraft'        => 'length',
        'BridgeClearance' => 'length',
        'BuilderLength'   => 'length',
        'Weight'          => 'weight',
        'CaptainOnBoard'  => array(
            'True'   => 'Yes',
            'False'  => 'No',
        ),
        'CaptainQuarters'  => array(
            'True'   => 'Yes',
            'False'  => 'No',
        )
    );

}