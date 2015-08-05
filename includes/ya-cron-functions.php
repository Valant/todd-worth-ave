<?php
/**
 * Yatco Cron.
 */
function ya_load_modification_list()
{
    include_once( 'admin/class-ya-admin.php' );
    $api = new YA_API();
    $modification_list = $api->load_modification_list();

    if ( $modification_list ) {
        foreach ( $modification_list as $VesselID ) {
            $vessel_detail          = $api->load_vessel_detail($VesselID);
            $vessel_detail->ForSale = true;

            if( $vessel_detail !== false ) {
                $api->save_vessel( $vessel_detail );
            }
        }
    }
}
add_action( 'yatco_cron_update_vassel', 'ya_load_modification_list');

function sf_synchronize_products( $mode, $version )
{
    $salesForceApi = new SalesForceApi($mode);
    global $wpdb;

    if ( $mode ) {
        $salesForceApi->setSyncMode( $mode );
    }
    if ( $version ) {
        $salesForceApi->setSyncVersion( $version );
    }

    $query = "SELECT {$wpdb->posts}.ID as 'post_id',
                     {$wpdb->posts}.post_title,
                     m.meta_value as '{$salesForceApi->getSyncVersionKey()}' FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} m
                    ON ( {$wpdb->posts}.ID = m.post_id AND m.meta_key = '{$salesForceApi->getSyncVersionKey()}' )
                WHERE
                    {$wpdb->posts}.post_type = 'vessel'
                AND {$wpdb->posts}.post_status = 'publish'
                AND ( m.meta_value IS NULL OR m.meta_value = '' OR m.meta_value < '{$salesForceApi->getSyncVersion()}' )
                GROUP BY {$wpdb->posts}.ID
                ORDER BY {$wpdb->posts}.post_modified DESC
                LIMIT 10";

    $vessels = $wpdb->get_results( $query );




     $whole_info = array();
     $countries = $states = $cities = $regions = $types = $builders = $profileURLs = array();

     foreach ( $vessels as $item )
     {
         $vessel_detail = unserialize( $item->vessel_detail );

         if( isset( $vessel_detail['LocationCity'] ) && !empty( $vessel_detail['LocationCity'] ) ) {
             if ( isset( $cities[$vessel_detail['LocationCity']] ) )  {
                 $cities[$vessel_detail['LocationCity']]['amount'] += 1;
             } else {
                 $cities[$vessel_detail['LocationCity']]['name']   = $vessel_detail['LocationCity'];
                 $cities[$vessel_detail['LocationCity']]['amount'] = 1;
             }
         }
         if( isset( $vessel_detail['LocationCountry'] ) && !empty( $vessel_detail['LocationCountry'] ) ) {
             if ( isset( $countries[$vessel_detail['LocationCountry']] ) )  {
                 $countries[$vessel_detail['LocationCountry']]['amount'] += 1;
             } else {
                 $countries[$vessel_detail['LocationCountry']]['name']   = $vessel_detail['LocationCountry'];
                 $countries[$vessel_detail['LocationCountry']]['amount'] = 1;
             }
         }
         if( isset( $vessel_detail['LocationRegionName'] ) && !empty( $vessel_detail['LocationRegionName'] ) ) {
             if ( isset( $regions[$vessel_detail['LocationRegionName']] ) )  {
                 $regions[$vessel_detail['LocationRegionName']]['amount'] += 1;
             } else {
                 $regions[$vessel_detail['LocationRegionName']]['name']   = $vessel_detail['LocationRegionName'];
                 $regions[$vessel_detail['LocationRegionName']]['amount'] = 1;
             }
         }
         if( isset( $vessel_detail['LocationState'] ) && !empty( $vessel_detail['LocationState'] ) ) {
             if ( isset( $states[$vessel_detail['LocationState']] ) )  {
                 $states[$vessel_detail['LocationState']]['amount'] += 1;
             } else {
                 $states[$vessel_detail['LocationState']]['name']   = $vessel_detail['LocationState'];
                 $states[$vessel_detail['LocationState']]['amount'] = 1;
             }
         }
         if( isset( $vessel_detail['Builder'] ) && !empty( $vessel_detail['Builder'] ) ) {
             if ( isset( $builders[$vessel_detail['Builder']] ) )  {
                 $builders[$vessel_detail['Builder']]['amount'] += 1;
             } else {
                 $builders[$vessel_detail['Builder']]['name']   = $vessel_detail['Builder'];
                 $builders[$vessel_detail['Builder']]['amount'] = 1;
             }
         }
         if( isset( $vessel_detail['VesselType'] ) && !empty( $vessel_detail['VesselType'] ) ) {
             if ( isset( $types[$vessel_detail['VesselType']] ) )  {
                 $types[$vessel_detail['VesselType']]['amount'] += 1;
             } else {
                 $types[$vessel_detail['VesselType']]['name']   = $vessel_detail['VesselType'];
                 $types[$vessel_detail['VesselType']]['amount'] = 1;
             }
         }
     }

//     $whole_info['countries'] = array_values( $countries );
     $whole_info['regions']   = array_values( $regions );
//     $whole_info['states']    = array_values( $states );
//     $whole_info['cities']    = array_values( $cities );
//     $whole_info['types']     = array_values( $types );
     $whole_info['builders']  = array_values( $builders );

    $fp1 = fopen('1.csv', 'w');
    foreach ( $whole_info['regions'] as $item ) {
        fputcsv($fp1, split(',', $item));
    }
    fclose($fp1);

    $fp2 = fopen('file2.csv', 'w');
    foreach ( $whole_info['builders'] as $item ) {
        fputcsv($fp2, split(',', $item));
    }
    fclose($fp2);

    echo "<pre>";
    print_r( $whole_info ); exit;
    echo "</pre>";


    if ( function_exists("SimpleLogger") ) {
        SimpleLogger()->info('mode: '.$mode.'; version: '.$version.'; vessels found: '.count($vessels));
    }

    try {

        foreach ( $vessels as $item )
        {
            $vessel_detail = get_post_meta( $item->post_id , 'vessel_detail', true );
            if ( !$vessel_detail ) {
                if ( function_exists("SimpleLogger") ) {
                    SimpleLogger()->info('no details for post_id: '.$item->post_id);
                }
                update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                continue;
            }

            $SFProductId = get_post_meta( $item->post_id , $salesForceApi->getSyncIdKey(), true );
            $vessel_detail['ForSale'] = true;
            $vessel_detail['Boatname'] = $item->post_title;

            $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($item->post_id), 'large');
            if (!empty($image_src[0])) {
                $vessel_detail['Image_URL'] = $image_src[0];
            }

            $vessel_detail = (object)$vessel_detail;

            if ( $SFProductId ) {
                $response = $salesForceApi->updateProduct( $SFProductId, $vessel_detail );
            } else {
                $response = $salesForceApi->addNewProduct( $vessel_detail );
            }

            if( $response['status'] == 'error' ) {
                if ( function_exists("SimpleLogger") ) {
                    SimpleLogger()->info('error response (post_id='.$item->post_id.'): '.json_encode($response));
                }
            } else {
                if ( function_exists("SimpleLogger") ) {
                    SimpleLogger()->info('success response (post_id='.$item->post_id.'): '.json_encode($response));
                }
                update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                update_post_meta( $item->post_id, $salesForceApi->getSyncIdKey(), $response['id'] );
            }
        }

    } catch ( Exception $e ) {
        if ( function_exists("SimpleLogger") ) {
            SimpleLogger()->info('Exception: '.$e->getMessage());
        }
    }
}
add_action( 'salesforce_synchronize_products', 'sf_synchronize_products', 10, 2);