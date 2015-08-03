<?php
/**
 * Yatco Admin.
 *
 * @class       YA_API
 * @author      VaLant
 * @category    Admin
 * @package     Yatco/Admin/API
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * YA_API class.
 */
class YA_API {

    protected $api_key = '';
    protected $cron_recurrence = '';
    protected $admin_id = 0;
    protected $vessel_detail = false;

    /**
     * Constructor
     */
    public function __construct() {
        $this->apikey = get_option('yatco_api_key');

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
        if($VesselID){
            global $wpdb;
            $sql    = "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key='VesselID' AND meta_value = {$VesselID} ";
            $result = $wpdb->get_results($sql);
            $post_id = 0;
            $remove  = array();
            if($result){
                foreach ($result as $post) {
                    if( $post_id == 0){
                        $post_id = $post->post_id;
                    }else{
                        $remove[] = $post->post_id;
                    }
                }
            }
            if(!empty($remove))
                $this->remove_vessel($remove);

            if($post_id)
                return $post_id;
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
            $args = array(
                'name' => $category,
                'slug' => $category
            );
            if($parent_term_id > 0)
                $args['parent'] = $parent_term_id;

            wp_update_term($term_id, $taxonomy, $args);

        }else{
            $args = array();
            if($parent_term_id > 0)
                $args['parent'] = $parent_term_id;

            $term_id = wp_insert_term(
                $category,     // the term
                $taxonomy,     // the taxonomy
                $args
            );
        }
        return $term_id;
    }
    public function set_categories($post_id = 0, $category = '', $subcategory = '')
    {
        if(!$post_id && empty($category)) return;
        $term_ids       = array();
        $parent_term_id = $this->save_term_taxonomy('vessel_cat', $category);
        if($parent_term_id && $parent_term_id > 0){
            $term_ids[] = $parent_term_id;
        }
        if( !empty($subcategory) && $parent_term_id){
            $term_id = $this->save_term_taxonomy('vessel_cat', $subcategory, $parent_term_id);
            if($term_id && $term_id > 0){
                $term_ids[] = $term_id;
            }
        }
        if(!empty($term_ids))
            wp_set_post_terms( $post_id, $term_ids, 'vessel_cat' );
    }
    public function set_companies($post_id = 0, $company = '')
    {
        if(!$post_id && empty($company)) return;
        $term_ids       = array();
        $term_id = $this->save_term_taxonomy('vessel_company', $company);
        if($term_id && $term_id > 0){
            $term_ids[] = $term_id;
        }
        if(!empty($term_ids))
            wp_set_post_terms( $post_id, $term_ids, 'vessel_company' );
    }

    public function save_attachment($thumb_url = '', $post_id = 0)
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

        $thumb_id = media_handle_sideload( $file_array, $post_id, '' );

        if ( is_wp_error($thumb_id) ) {
            @unlink($file_array['tmp_name']);
        }
        if($thumb_id && $thumb_id > 0)
            set_post_thumbnail( $post_id, $thumb_id );

    }

    public function save_vessel($result = false)
    {
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

            $post['ID']                = $post_id;
            $post['post_modified']     = current_time( 'mysql' );
            $post['post_modified_gmt'] = current_time( 'mysql', 1 );

            wp_update_post($post);

            $answer['status'] = 'updated';
        } else {

            $post_id = wp_insert_post($post);

            $answer['status'] = 'added';
        }

        if($post_id) {

            $individual_meta = ya_get_individual_meta();
            if(!empty($individual_meta) && is_array($individual_meta)){
                foreach ($individual_meta as $meta_key) {
                    if( isset($result->$meta_key) )
                        update_post_meta( $post_id, $meta_key, $result->$meta_key );
                }
            }
            if(isset($result->MainCategory) && !empty($result->MainCategory) ){
                $SubCategory = '';
                if(isset($result->SubCategory) && !empty($result->SubCategory) )
                    $SubCategory = $result->SubCategory;

                $this->set_categories($post_id, $result->MainCategory, $SubCategory);
            }

            if(isset($result->CompanyName) && !empty($result->CompanyName) ){
                $this->set_companies($post_id, $result->CompanyName);
            }

            $old_thumbnail_id = get_post_thumbnail_id( $post_id );
            if($old_thumbnail_id){
                delete_post_thumbnail( $post_id );
                wp_delete_attachment( $old_thumbnail_id, true );
            }
            if(isset($result->ProfileURL) && !empty($result->ProfileURL)){
                $this->save_attachment($result->ProfileURL, $post_id);
            }
            if(isset($result->Videos) && !empty($result->Videos)){
                $vessel_video_url = array();
                $i = 0;
                foreach ($result->Videos as $value) {
                    $vessel_video_url[$i]['VideoCaption'] = $value->VideoCaption;
                    $vessel_video_url[$i]['VideoURL'] = $value->VideoURL;
                    $i++;
                }
                update_post_meta( $post_id, 'vessel_video_url', $vessel_video_url );
            }

            $remove_fields = ya_remove_api_filds();
            if($remove_fields && !empty($remove_fields) && is_array($remove_fields)){
                foreach ($remove_fields as $field) {
                    unset($result->$field);
                }
            }
            $my_data = get_object_vars($result);
            update_post_meta( $post_id, 'vessel_detail', $my_data );
            return $answer;
        }
        return false;
    }


}