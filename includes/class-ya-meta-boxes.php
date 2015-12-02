<?php
/**
 * Meta Boxes
 *
 * @class       YA_Meta_Boxes
 * @version     1.0.0
 * @package     Yatco/Classes/Vessels
 * @category    Class
 * @author      Valant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YA_Meta_Boxes Class
 */
class YA_Meta_Boxes {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ), 100, 1 );
		add_action( 'admin_init', array( __CLASS__, 'save' ), 100 );
	}

	/**
	 * Adds the meta box container.
	 */
	public static function add_meta_box( $post_type ) {
		$post_types = array('vessel');   //limit meta box to certain post types
		if ( in_array( $post_type, $post_types )) {
			if( isset($_GET['post']) && !empty($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit'){
				$VesselID = get_post_meta($_GET['post'], 'VesselID', true);
				if( $VesselID ){
					add_meta_box(
						'reload_vessel'
						,__( 'Reload vessel', 'yatco' )
						,array( __CLASS__, 'render_meta_box_reload_vessel' )
						,$post_type
						,'side'
						,'high'
					);
				}

			}
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public static function save( ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_GET['reload_vessel'] ) || empty($_GET['reload_vessel']) )
			return;

		if( !isset($_GET['post']) || empty($_GET['post']) || !isset($_GET['action']) || $_GET['action'] != 'edit')
			return;

		$post_id = (int)$_GET['post'];
		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		$VesselID = (int)$_GET['reload_vessel'];
		if( $VesselID && $VesselID > 0 ){
			$api      = new YA_API();
			$vessel_detail          = $api->load_vessel_detail($VesselID);

	        if( $vessel_detail !== false ) {
	            $vessel_detail->ForSale = true;
	            $api->save_vessel( $vessel_detail );

	            $url = remove_query_arg( 'reload_vessel' );
	            //var_dump($url); die;
	            wp_redirect( $url );
	            exit;
	        }
		}
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public static function render_meta_box_reload_vessel( $post ) {
		$VesselID = get_post_meta($post->ID, 'VesselID', true);
		?>
		<a href="<?php echo esc_url( add_query_arg( 'reload_vessel', $VesselID ) ); ?>" class="button button-primary button-large"><?php _e('Reload vessel', 'yatco'); ?></a>
		<?php
	}
}

YA_Meta_Boxes::init();
