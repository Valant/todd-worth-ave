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

function sf_synchronize_products()
{
    include_once( 'admin/class-ya-admin.php' );
    $salesForceApi = new SalesForceApi();
    global $wpdb;

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
                ORDER BY {$wpdb->posts}.ID
                DESC LIMIT 10";

    $vessels = $wpdb->get_results( $query );

    foreach ( $vessels as $item )
    {
        $vessel_detail = get_post_meta( $item->post_id , 'vessel_detail', true );
        if ( !$vessel_detail ) {
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
            $responce = $salesForceApi->updateProduct( $SFProductId, $vessel_detail );
        } else {
            $responce = $salesForceApi->addNewProduct( $vessel_detail );
        }

        if( $responce['status'] == 'error' ) {
            update_post_meta( $item->post_id, 'error_message', $responce['message'] );
        } else {
            update_post_meta( $item->post_id, $salesForceApi->getSyncVersionKey(), $salesForceApi->getSyncVersion() );
            update_post_meta( $item->post_id, $salesForceApi->getSyncIdKey(), $responce['id'] );
        }
    }
}
add_action( 'salesforce_synchronize_products', 'sf_synchronize_products');

function cron_with_params()
{
    // check dev or prod and than set key for SyncVersion

    echo "< mode >";
    echo "<pre>";
    print_r(111);
    echo "</pre>";

    echo "< SyncVersion >";
    echo "<pre>";
    print_r(222); exit;
    echo "</pre>";

}
add_action( 'new_cron_with_params', 'cron_with_params');