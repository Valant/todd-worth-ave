<?php
/**
 * REST API Vessels controller
 *
 * Handles requests to the /vessels endpoint.
 *
 * @author   Valant
 * @category API
 * @package  Yatco/API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API Vessels controller class.
 *
 * @package Yatco/API
 */
class YA_REST_Vessel_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wp/v2';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'vessels';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'vessel';

	/**
	 * Initialize vessels actions.
	 */
	public function __construct() {
		#add_filter( "woocommerce_rest_{$this->post_type}_query", array( $this, 'query_args' ), 10, 2 );
	}

	/**
	 * Register the routes for vessels.
	 */
	public function register_routes() {
	}


	/**
	 * Query args.
	 *
	 * @param array $args
	 * @param WP_REST_Request $request
	 * @return array
	 */
	public function query_args( $args, $request ) {
		global $wpdb;

		return $args;
	}

    public function register_fields() {

        /**
         * Adding specification fields to api output
         * @todo: remove unneeded
         */

        // add specification fields
        $fieldList = array();
        $excludedFieldsList = array(
            'VesselID',
            'source'
        );
        $specificationFiels = get_vessel_specification_fields();
        foreach ($specificationFiels as $group) {
            foreach ($group as $tab) {
                foreach ($tab as $field) {
                    $name = isset($field['name']) ? $field['name'] : $field['id'];
                    $type = isset($field['type']) ? $field['type'] : 'text';
                    if (!in_array($name, $excludedFieldsList)) {
                        if ($type !== 'taxonomy') {
                            $fieldList[] = $name;
                            if ($type === 'units') {
                                $fieldList[] = $name . '_unit';
                            }
                        }
                    }
                }
            }
        }
        // add vessel status
        $statusFields = YA_Meta_Box_Vessel_Status::statusFields();
        foreach ($statusFields as $fieldName => $_) {
            $fieldList[] = $fieldName;
        }
        $fieldList[] = 'times_sold';
        foreach ($fieldList as $field) {
            register_rest_field( 'vessel',
                $field,
                array(
                    'get_callback'    => array($this, 'getMeta'),
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        // add photo gallery
        register_rest_field('vessel',
            'photos',
            array(
                'get_callback'    => array($this, 'getPhotos'),
                'update_callback' => null,
                'schema'          => null,
            )
            );

        // add videos
        register_rest_field('vessel',
            'videos',
            array(
                'get_callback'    => array($this, 'getVideos'),
                'update_callback' => null,
                'schema'          => null,
            )
        );

    }

    public function getMeta($object, $field_name, $request)
    {
        $val = get_post_meta($object['id'],$field_name,true);
        return $val;
    }

    public function getPhotos($object, $field_name, $request)
    {
        $photos = array();
        if ( metadata_exists( 'post', $object['id'], '_vessel_image_gallery' ) ) {
            $vessel_image_gallery = get_post_meta( $object['id'], '_vessel_image_gallery', true );
        } else {
            // Backwards compat
            $attachment_ids = get_posts( 'post_parent=' . $object['id'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_yatco_exclude_image&meta_value=0' );
            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
            $vessel_image_gallery = implode( ',', $attachment_ids );
        }
        $attachments         = array_filter( explode( ',', $vessel_image_gallery ) );
        $thumb_id = (int)get_post_thumbnail_id($object['id']);
        if ($thumb_id) {
            array_unshift($attachments,$thumb_id);
        }
        foreach ($attachments as $a_id) {
            $categories = get_post_meta($a_id, 'photo_categories', true);
            $meta = get_post_meta($a_id, '_wp_attachment_metadata', true);

            $photo = array(
                'width' => $meta['width'],
                'height' => $meta['height'],
                'id' => (int)$a_id,
                'url' => wp_get_attachment_image_url($a_id, 'full'),
                'categories' => $categories,
                'mime-type' => get_post_mime_type($a_id),
                'placement' => ($thumb_id && $thumb_id == $a_id) ? 'main' : 'gallery',
            );
            if ($meta['sizes']) {
                $photo['sizes'] = array();
                foreach ($meta['sizes'] as $sz=>$info) {
                    $photo['sizes'][$sz] = array(
                        'file' => $info['file'],
                        'width' => $info['width'],
                        'height' => $info['height'],
                        'mime-type' => $info['mime-type'],
                    );
                }
            }
            $photos[] = $photo;
        }
        return $photos;
    }

    public function getVideos($object, $field_name, $request)
    {
        $videos = array();
        $video_urls = get_post_meta($object['id'], '_vessel_video_urls', true);
        if (is_array($video_urls)) {
            foreach ($video_urls as $item) {
                if ($item['VideoURL']) {
                    $videos[] = $item;
                }
            }
        }
        return $videos;
    }

}
