<?php


require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-admin/includes/media.php' );
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-admin/includes/file.php' );
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-admin/includes/image.php' );






function save_tax($page=0)
{
    include_once( __DIR__ . '/includes/admin/class-ya-admin.php' );
    $api = new YA_Admin_API();
    global $wpdb;
    $pageSize = 10;
    $offset = $pageSize * $page;
    $sql = "SELECT id,post_content FROM {$wpdb->posts} WHERE post_type='vessel' ORDER BY id ASC LIMIT {$offset},{$pageSize};";
    $posts = $wpdb->get_results($sql);
    foreach ($posts as $postinfo) {
        $api->saveTagsFromText($postinfo->id, $postinfo->post_content);

    }
}


save_tax(1);
//add_action('wp_loaded', 'save_tax');



