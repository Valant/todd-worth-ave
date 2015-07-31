<?php
/**
 * Yatco Cron.
 */
function ya_load_modification_list()
{
    include_once( 'admin/class-ya-admin.php' );
    $api = new YA_API();
    $modification_list = $api->load_modification_list();

    if($modification_list) {
        foreach ($modification_list as $VesselID) {
            $vessel_detail          = $api->load_vessel_detail($VesselID);
            $vessel_detail->ForSale = true;

            echo "<pre>";
            print_r( $vessel_detail->Boatname );
            echo "</pre>";

            if($vessel_detail !== false) {
                $api->save_vessel( $vessel_detail );

                echo "<pre>";
                print_r( $vessel_detail ); exit;
                echo "</pre>";
            }
        }
    }
}
add_action( 'yatco_cron_update_vassel', 'ya_load_modification_list');

function sf_synchronize_products()
{
    include_once( 'admin/class-ya-admin.php' );
    $salesForceApi = new SalesForceApi();
    global $wpdb;


    $SFProductId_key = '';
    if ( $salesForceApi->mode == 'dev' ) {
        $SFProductId_key = 'SFProductId_sandbox';
    } else if ( $salesForceApi->mode == 'prod' ) {
        $SFProductId_key = 'SFProductId';
    }

    $query = "SELECT {$wpdb->posts}.ID as 'post_id', {$wpdb->posts}.post_title, m1.meta_value as 'vessel_detail', m2.meta_value as '$SFProductId_key' FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} m1
                    ON ( {$wpdb->posts}.ID = m1.post_id AND m1.meta_key =  'vessel_detail' )
                LEFT JOIN {$wpdb->postmeta} m2
                    ON ( {$wpdb->posts}.ID = m2.post_id AND m2.meta_key = '$SFProductId_key' )
                WHERE
                    {$wpdb->posts}.post_type = 'vessel'
                AND {$wpdb->posts}.post_status = 'publish'
                AND ( m1.meta_value != '' )
                AND ( m2.meta_value IS NULL )
                GROUP BY {$wpdb->posts}.ID
                ORDER BY {$wpdb->posts}.ID
                DESC LIMIT 10";

    $vessels = $wpdb->get_results( $query );

    foreach ( $vessels as $item )
    {
        $SFProductId = '';
        $SFProductId = get_post_meta( $item->post_id , $SFProductId_key, true );

        $vessel_detail = unserialize( $item->vessel_detail );
        $vessel_detail['ForSale'] = true;
        $vessel_detail['Boatname'] = $item->post_title;
        $vessel_detail = (object)$vessel_detail;

        if ( $SFProductId ) {
            $responce = $salesForceApi->updateProduct( $SFProductId, $vessel_detail );

            if( $responce['status'] == 'error' ) {
                update_post_meta( $item->post_id, 'error_message', $responce['message'] );
            }
        } else {
            $responce = $salesForceApi->addNewProduct( $vessel_detail );

            if( $responce['status'] == 'error' ) {
                update_post_meta( $item->post_id, 'error_message', $responce['message'] );
            } else {
                $SFProductId = $responce['id'];
            }
        }

        if ( $salesForceApi->mode == 'dev' ) {
            update_post_meta( $item->post_id, 'SFProductId_sandbox', $SFProductId );
        } else if ( $salesForceApi->mode == 'prod' ) {
            update_post_meta( $item->post_id, 'SFProductId', $SFProductId );
        }
    }

}
add_action( 'salesforce_synchronize_products', 'sf_synchronize_products');