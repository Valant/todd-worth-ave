<?php
/**
 * Yatco Admin API.
 *
 * @class       YA_Admin_API
 * @author      VaLant
 * @category    Admin
 * @package     Yatco/Admin/API
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * YA_Admin_API class.
 */
class YA_Admin_API {

    protected $apikey = '';
    protected $taxonomies = null;
    protected $cron_recurrence = '';
    protected $admin_id = 0;
    protected $vessel_detail = false;

    /**
     * Constructor
     */
    public function __construct() {
        global $ya_countries;
        $this->apikey       = get_option('yatco_api_key');
        $this->taxonomies   = YA_Taxonomies::get_taxonomies_names();
        $ya_countries = ya_get_countries();

        $recurrence = get_option( 'yatco_cron_schedule' );
        if(!$recurrence)
            $recurrence = 'two_hourly';

        switch ($recurrence) {
            case 'hourly':
                $this->cron_recurrence = 1;
                break;
            case 'two_hourly':
                $this->cron_recurrence = 1;
                break;
            case 'twicedaily':
                $this->cron_recurrence = 12;
                break;
            case 'daily':
                $this->cron_recurrence = 24;
                break;
            default:
                $this->cron_recurrence = 2;
                break;
        }

    }

    public function get_admin_id()
    {
        if( $this->admin_id){
            return $this->admin_id;
        }else{
            $user = false;
            if(function_exists('wp_get_current_user'))
                $user = wp_get_current_user();

            if($user){
                $this->admin_id = $user->ID;
            }else{
                $users           = get_users( array('role' => 'administrator',) );
                $this->admin_id  = $users[0]->data->ID;
            }
        }
        return $this->admin_id;
    }
    private function createCURL($url){
        if($url != 'nul'){
            $s = curl_init();
            curl_setopt($s, CURLOPT_URL, $url);
            curl_setopt($s, CURLOPT_HTTPGET, true);
            curl_setopt($s, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($s, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
            $result = curl_exec($s);
            #$info   = curl_getinfo($s);
            $status = curl_getinfo($s,CURLINFO_HTTP_CODE);
            curl_close($s);
            if($status == '200')
                return $result;
            else
                return false;
        }
        return false;
    }

    public function get_total_count()
    {
        $url  = 'http://data.yatco.com/dataservice/'.$this->apikey.'/search?Format=json&PageIndex=1&PageSize=10';
        $json_string = $this->createCURL($url);
        $data        = json_decode($json_string);
        $count       = array();
        $count['RecordCount'] = $data->RecordCount;
        $count['PageCount']   = $data->PageCount;
        return $count;
    }

    public function load_page($page_id = 1)
    {
        $url  = 'http://data.yatco.com/dataservice/'.$this->apikey.'/search?Format=json&PageIndex='.$page_id.'&PageSize=10';
        $json_string = $this->createCURL($url);
        $data = json_decode($json_string);
        return $data->Vessels;
    }

    public function load_modification_list()
    {
        if(empty($this->apikey)) return false;

        $url  = 'http://data.yatco.com/dataservice/'.$this->apikey.'/VesselModificationList/'.$this->cron_recurrence;
        $json_string = $this->createCURL($url);
        if($json_string === false) {
            return false;
        }
        return json_decode($json_string);

    }

    public function load_vessel_detail($vessel_id = 0)
    {
        if($vessel_id){
            $url  = 'http://data.yatco.com/dataservice/'.$this->apikey.'/vessel/'.$vessel_id.'?Format=json';
            $json_string = $this->createCURL($url);
            if($json_string === false) {
                $this->vessel_detail = false;
                return false;
            }
            $this->vessel_detail = json_decode($json_string);
            return $this->vessel_detail;
        }
        $this->vessel_detail = false;
        return false;
    }

    public function vessel_exist($VesselID = 0)
    {
        if ( $VesselID ) {
            global $wpdb;
            $sql    = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='VesselID' AND meta_value = {$VesselID} ";
            $result = $wpdb->get_var( $sql );

            if( $result ) {
                return $result;
            }
        }
        return false;
    }

    public function remove_vessel($exist_vassel = array())
    {
        if(!empty($exist_vassel) && is_array($exist_vassel)){
            global $wpdb;
            $ids   = array();
            $a_ids = array();
            $posts = implode(',', $exist_vassel);

            $attachments    = $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE post_parent IN({$posts}) ", ARRAY_N);
            if($attachments){
                foreach ($attachments as $attachment) {
                    $a_ids[] = (int)$attachment[0];
                }
            }

            $ids = array_merge($exist_vassel, $a_ids);

            $ids = implode(',', $ids);

            $wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID IN ({$ids})");
            $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id IN ({$ids})");
        }
    }

    public function save_term_taxonomy($taxonomy = '', $category = '', $parent_term_id = 0)
    {
        if(empty($category) || empty($taxonomy)) return false;
        $term_id = 0;
        if($term = term_exists( $category, $taxonomy )){
            $term_id = $term['term_id']; // get numeric term id
            if($parent_term_id > 0){
                $args = array(
                    'parent' => $parent_term_id
                );
                wp_update_term($term_id, $taxonomy, $args);
            }


        }else{
            $args = array();
            if($parent_term_id > 0)
                $args['parent'] = $parent_term_id;

            $term = wp_insert_term(
                $category,     // the term
                $taxonomy,     // the taxonomy
                $args
            );
            $term_id = $term['term_id']; // get numeric term id
        }
        return $term_id;
    }    
    public function set_term($post_id = 0, $taxonomy_name = '', $term_name = '', $child_term_name = '')
    {
        if(!$post_id || empty($term_name) || empty($taxonomy_name)) return;

        $term_ids       = array();
        $parent_term_id = $this->save_term_taxonomy($taxonomy_name, $term_name);
        if($parent_term_id && $parent_term_id > 0){
            $term_ids[] = $parent_term_id;
        }
         if( !empty($child_term_name) && $parent_term_id > 0 ){
            $term_id = $this->save_term_taxonomy($taxonomy_name, $child_term_name, $parent_term_id);
            if($term_id && $term_id > 0){
                $term_ids[] = $term_id;
            }
        }

        if(!empty($term_ids)){
            $term_ids = array_map( 'intval', $term_ids );
        }
        wp_set_object_terms( $post_id, $term_ids, $taxonomy_name );
    }

    public function set_categories($post_id = 0, $category = '', $subcategory = '')
    {
        $this->set_term($post_id, 'vessel_cat', $category, $subcategory);
    }
    public function set_companies($post_id = 0, $company = '')
    {
        $this->set_term($post_id, 'vessel_company', $company);
    }
    public function set_builders($post_id = 0, $builder = '')
    {
        $this->set_term($post_id, 'vessel_builder', $builder);
    }

    public function save_attachment($thumb_url = '', $post_id = 0, $desc = '')
    {
        if( empty($thumb_url) || !$post_id ) return false;

        $tmp = download_url( $thumb_url );

        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
        
        $file_array['name'] = basename($matches[0]);
        $file_array['tmp_name'] = $tmp;

        if ( is_wp_error( $tmp ) ) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
        }
        

        $thumb_id = media_handle_sideload( $file_array, $post_id, $desc );

        if ( is_wp_error($thumb_id) ) {
            @unlink($file_array['tmp_name']);
            return false;
        }

        return $thumb_id;

    }

    /**
     * Set inactive status.
     *
     * @param int $VesselID (default: 0)
     */
    public function deactivate_vessel( $VesselID = 0 ){
        if( $VesselID > 0 && $post_id = $this->vessel_exist($VesselID) ) {

            $post['ID']                = $post_id;
            $post['post_status']       = 'inactive';
            $post['post_modified']     = current_time( 'mysql' );
            $post['post_modified_gmt'] = current_time( 'mysql', 1 );

            wp_update_post($post);

            // Lets reset SFSyncVersion, so item will be synced to SF on next run
            update_post_meta( $post_id, 'SFSyncVersion', '' );
            update_post_meta( $post_id, 'SFSyncVersion_sandbox', '' );
        }
    }

    public function save_vessel($result = false)
    {
        global $ya_countries, $wpdb;

        if(!$result){
            $result = $this->vessel_detail;
        }
        if(!$result)
            return false;

        $answer = array();
        $VesselSections = $result->VesselSections;

        $post_content = '';
        if(!empty($VesselSections)){
            foreach ($VesselSections as $section) {
                $post_content .= '<h2>'.$section->SectionName.'</h2>';
                $post_content .= $section->SectionText;
            }
        }
        $answer['VesselID'] = $result->VesselID;
        $answer['Boatname'] = $result->Boatname;
        $post_content = trim(ya_remove_attributes($post_content));
        $post_excerpt = ya_remove_attributes($result->DescriptionShortDescription);
        if(empty($post_content))
            $post_content = $post_excerpt;

        $post = array(
            'post_content'   => $post_content,
            'post_excerpt'   => $post_excerpt,
            'post_title'     => $result->Boatname,
            'post_status'    => 'publish',
            'post_type'      => 'vessel',
            'post_author'    => $this->get_admin_id()
        );

        if($post_id = $this->vessel_exist($result->VesselID)) {

            if( !$this->load_vessel_detail( $result->VesselID ) ) {
                $this->deactivate_vessel($result->VesselID);

            } else {

                $post['ID']                = $post_id;
                $post['post_modified']     = current_time( 'mysql' );
                $post['post_modified_gmt'] = current_time( 'mysql', 1 );

                wp_update_post($post);

                // Lets reset SFSyncVersion, so item will be synced to SF on next run
                update_post_meta( $post_id, 'SFSyncVersion', '' );
                update_post_meta( $post_id, 'SFSyncVersion_sandbox', '' );
            }

            $answer['status'] = 'updated';
        } else {

            $post_id = wp_insert_post($post);

            $answer['status'] = 'added';
        }

        if($post_id) {

            if(isset($result->MainCategory) && !empty($result->MainCategory) ){
                $SubCategory = '';
                if(isset($result->SubCategory) && !empty($result->SubCategory) )
                    $SubCategory = $result->SubCategory;

                $this->set_categories($post_id, $result->MainCategory, $SubCategory);
            }

            if(isset($result->CompanyName) && !empty($result->CompanyName) ){
                $this->set_companies($post_id, $result->CompanyName);
            }

            if(isset($result->Builder) && !empty($result->Builder) ){
                $this->set_builders($post_id, $result->Builder);
            }

            if( $this->taxonomies && is_array($this->taxonomies) ){
                foreach ($this->taxonomies as $yatco_key => $taxonomy) {
                    if(isset($result->$yatco_key) && !empty($result->$yatco_key) ){
                        $this->set_term($post_id, $taxonomy['slug'], $result->$yatco_key);
                    }
                }                
            }



            //Save post thumbnail
            $old_thumbnail_id = get_post_thumbnail_id( $post_id );
            if($old_thumbnail_id){
                delete_post_thumbnail( $post_id );
                wp_delete_attachment( $old_thumbnail_id, true );
            }
            if(isset($result->ProfileURL) && !empty($result->ProfileURL)){
                $thumb_id = $this->save_attachment($result->ProfileURL, $post_id);
                if($thumb_id && $thumb_id > 0){
                    set_post_thumbnail( $post_id, $thumb_id );
                }
            }


            //Save additional vessel images
            if( get_option('vessel_save_gallery') == 'wp_media'){

                $new_images    = array();
                if(isset($result->Gallery) && !empty($result->Gallery)){
                    foreach ($result->Gallery as $image) {
                        $query = explode('?', $image->originalimageurl);
                        parse_str($query[1], $data);
                        $yatco_image_id = $data['id'];
                        
                        $attach_id = $wpdb->get_var( "SELECT post_id from $wpdb->postmeta where meta_value = {$yatco_image_id} AND meta_key = 'yatco_image_id' LIMIT 1" );
                        if( !$attach_id ){
                            $attach_id = $this->save_attachment( $image->url, $post_id, $image->caption );
                        }
                        $attach_id = absint($attach_id);

                        if( $attach_id && $attach_id > 0 ){
                            update_post_meta( $attach_id, 'yatco_image_id', $yatco_image_id );                        
                            $new_images[] = $attach_id;
                        }
                    }
                    unset($result->Gallery);
                }
                if( !empty($new_images) ){
                    $image_gallery       = get_post_meta( $post_id, '_vessel_image_gallery', true );
                    $attachments         = array_filter( explode( ',', $image_gallery ) );
                    $updated_gallery_ids = array_merge($attachments, $new_images);
                    update_post_meta( $post_id, '_vessel_image_gallery', implode( ',', $updated_gallery_ids ) );                
                }

            }

            if(isset($result->Videos) && !empty($result->Videos)){
                $vessel_video_url = array();
                $i = 0;
                foreach ($result->Videos as $value) {
                    $vessel_video_url[$i]['VideoCaption'] = $value->VideoCaption;
                    $vessel_video_url[$i]['VideoURL'] = $value->VideoURL;
                    $i++;
                }
                update_post_meta( $post_id, '_vessel_video_urls', $vessel_video_url );
            }


            $remove_fields = ya_remove_api_filds();
            if($remove_fields && !empty($remove_fields) && is_array($remove_fields)){
                foreach ($remove_fields as $field) {
                    unset($result->$field);
                }
            }
            
            $my_data = get_object_vars($result);
            update_post_meta( $post_id, 'vessel_detail', $my_data );

            if( !empty($result->LocationCountry) ){
                $code = array_search($result->LocationCountry, $ya_countries);
                if( $code ){
                    update_post_meta( $post_id, 'LocationCountry', $code );
                    unset($my_data['LocationCountry']);
                }
            }
            #var_dump($my_data);

            $relations = get_vessel_yatco_relations();

            foreach ($relations as $key => $value_type) {

                if( is_array($value_type) && isset($my_data[$key]) ){
                    $_value = $my_data[$key];
                    if( isset($value_type[$_value]) ){
                        $my_data[$key] = $value_type[$_value];                        
                    }
                }else{
                    $fanc_name = 'ya_get_'.$value_type.'_units';
                    if( function_exists($fanc_name) ){
                        $value        = '';
                        $all_units    = call_user_func($fanc_name);
                        $unit = $def_unit = get_option('vessel_speed_unit', key($all_units) );
                        reset($all_units);
                        
                        foreach ($all_units as $uk => $uv) {
                            $_value = '';

                            $unit_keys = $this->get_unitnames($uk);

                            if( isset($my_data[$key . $unit_keys[0] ]) ){
                                $_value = $my_data[$key . $unit_keys[0]];
                            }else if( isset($my_data[$key . $unit_keys[1]]) ){
                                $_value = $my_data[$key . $unit_keys[1]];
                            }

                            if( !empty($_value) ){
                                $value = $_value;
                                $unit  = $uk;
                            }
                            if( $uk == $def_unit && $_value != ''){
                                break;
                            }

                        }
                        if( !empty($value) ){
                            foreach ($all_units as $uk => $uv) {
                                $_value = '';
                                $_uk    = '';

                                $unit_keys = $this->get_unitnames($uk);

                                if( isset($my_data[$key . $unit_keys[0] ]) ){
                                    $_value = $my_data[$key . $unit_keys[0]];
                                    $_uk = $unit_keys[0];
                                }else if( isset($my_data[$key . $unit_keys[1]]) ){
                                    $_value = $my_data[$key . $unit_keys[1]];
                                    $_uk = $unit_keys[1];
                                }
/*var_dump($_value . ' ' .$_uk);
$_value = '';*/
                                if( empty($_value) && !empty($_uk) ){
                                    $_value = ya_convert_measurement($value, $unit, $uk);
                                    if( $_value && !empty($_value) ){
                                        $my_data[$key . $_uk] = $_value;
#var_dump( $value .''. $unit . ' to '. $uk . ' = ' . $_value . ' ( '.$key . $_uk. ')');                                        
                                    }
                                }

                            }
                        }

                        update_post_meta( $post_id, $key, $value );
                        update_post_meta( $post_id, $key . '_unit', $unit );

                    }
                }
            }

            foreach ($my_data as $key => $value) {
                update_post_meta( $post_id, $key, $value );
            }
            update_post_meta( $post_id, '_source', 'yatco' );
            
            return $answer;
        }
        return false;
    }

    private function get_unitnames($unit='')
    {
        $_unit = array(
            ucfirst($unit),
            strtoupper($unit)
        );
        return $_unit;
    }


}