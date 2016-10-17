<?php
/**
 * Yatco Cron.
 */
function ya_load_modification_list()
{
    include_once( 'admin/class-ya-admin.php' );
    $api = new YA_Admin_API();
    $modification_list = $api->load_modification_list();
    if ( $modification_list ) {
        foreach ( $modification_list as $VesselID ) {

            $vessel_detail          = $api->load_vessel_detail($VesselID);

            if( $vessel_detail !== false ) {
                $vessel_detail->ForSale = true;
                $api->save_vessel( $vessel_detail );
            }else{
                $api->deactivate_vessel( $VesselID );
            }

        }
    }
}
add_action( 'yatco_cron_update_vassel', 'ya_load_modification_list');

function yatco_cron_recheck_vassel($limit)
{
    include_once( 'admin/class-ya-admin.php' );
    $api = new YA_Admin_API();
    global $wpdb;

    if ( !$limit ) {
        $limit = 10;
    }

    $query = "SELECT {$wpdb->posts}.ID as 'post_id',
                     m.meta_value FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} m
                    ON ( {$wpdb->posts}.ID = m.post_id AND m.meta_key = 'is_reckeck_done' )
                WHERE
                    {$wpdb->posts}.post_type = 'vessel'
                AND ( m.meta_value IS NULL OR m.meta_value<>'yes' )
                GROUP BY {$wpdb->posts}.ID
                ORDER BY {$wpdb->posts}.post_modified DESC
                LIMIT ".$limit;

    $vessels = $wpdb->get_results( $query );

    if ( function_exists("SimpleLogger") ) {
        SimpleLogger()->info('LOG-recheck: vessels found: '.count($vessels));
    }

    foreach ( $vessels as $item )
    {
        $VesselID = get_post_meta( $item->post_id , 'VesselID', true );
        $vessel_detail          = $api->load_vessel_detail($VesselID);

        if( $vessel_detail !== false ) {

            if ( function_exists("SimpleLogger") ) {
                SimpleLogger()->info('LOG-recheck: vessels exists: '.$item->post_id);
            }
            // do nothing
        }else{

            if ( function_exists("SimpleLogger") ) {
                SimpleLogger()->info('LOG-recheck: vessels REMOVED: '.$item->post_id);
            }
            $api->deactivate_vessel( $VesselID );
        }
        update_post_meta( $item->post_id, 'is_reckeck_done', 'yes' );
    }
}
add_action( 'yatco_cron_recheck_vassel', 'yatco_cron_recheck_vassel');

function sf_synchronize_products( $mode, $version, $limit )
{
    $salesForceApi = new SalesForceApi($mode);
    global $wpdb;

    if ( $mode ) {
        $salesForceApi->setSyncMode( $mode );
    }
    if ( $version ) {
        $salesForceApi->setSyncVersion( $version );
    }
    if ( !$limit ) {
        $limit = 10;
    }

    $query = "SELECT {$wpdb->posts}.ID as 'post_id',
                     {$wpdb->posts}.post_title,
                     {$wpdb->posts}.post_status,
                     m.meta_value as '{$salesForceApi->getSyncVersionKey()}' FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} m
                    ON ( {$wpdb->posts}.ID = m.post_id AND m.meta_key = '{$salesForceApi->getSyncVersionKey()}' )
                WHERE
                    {$wpdb->posts}.post_type = 'vessel'
                AND ( m.meta_value IS NULL OR m.meta_value = '' OR m.meta_value < '{$salesForceApi->getSyncVersion()}' )
                GROUP BY {$wpdb->posts}.ID
                ORDER BY {$wpdb->posts}.post_modified DESC
                LIMIT ".$limit;

    $vessels = $wpdb->get_results( $query );

    $remove_arr = array();

    if ( function_exists("SimpleLogger") ) {
        SimpleLogger()->info('LOG:mode: '.$mode.'; version: '.$version.'; vessels found: '.count($vessels));
    }

    try {

        foreach ( $vessels as $item )
        {
            if ( function_exists("SimpleLogger") ) {
                SimpleLogger()->info('LOG:TRY (post_id='.$item->post_id.')');
            }

            $vessel_detail = get_post_meta( $item->post_id , 'vessel_detail', true );
            if ( !$vessel_detail ) {
                if ( function_exists("SimpleLogger") ) {
                    SimpleLogger()->info('LOG:no details for post_id: '.$item->post_id);
                }
                update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                continue;
            }

            $SFProductId = get_post_meta( $item->post_id , $salesForceApi->getSyncIdKey(), true );

            if( $item->post_status == 'publish'){
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

                if( $response['status'] == 'success' ) {
                    update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                    update_post_meta( $item->post_id, $salesForceApi->getSyncIdKey(), $response['id'] );
                } else {
                    update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                    if ( function_exists("SimpleLogger") ) {
                        SimpleLogger()->info('LOG:error response (post_id='.$item->post_id.'): ', array('api_response'=>json_encode($response)));
                    }
                }

            }else if ( $SFProductId ) {
                update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
                $remove_arr[] = $SFProductId;
            }

        }

        if( !empty($remove_arr) ){
            $salesForceApi->deleteProducts($remove_arr);
        }

    } catch ( Exception $e ) {
        if ( function_exists("SimpleLogger") ) {
            SimpleLogger()->info('LOG:Exception', array('e_message'=>$e->getMessage()));
        }
    }
}
add_action( 'salesforce_synchronize_products', 'sf_synchronize_products', 10, 3);