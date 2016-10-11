<?php
/**
 * Meta Boxes
 *
 * @class       YA_Meta_Boxes
 * @version     1.0.1
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
		add_action( 'add_meta_boxes' , array( __CLASS__, 'remove_meta_boxes' ), 99 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ), 100, 1 );
		add_action( 'admin_init', array( __CLASS__, 'reload_vessel' ), 100 );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ), 100 );


		add_action( 'save_post', array( __CLASS__, 'save' ), 777, 2 );		
	}

	public static function remove_meta_boxes()
	{
		remove_meta_box( 'tagsdiv-vessel_builder' , 'vessel' , 'side' ); 
		remove_meta_box( 'postcustom' , 'vessel' , 'normal' ); 
		remove_meta_box( 'postexcerpt', 'vessel', 'normal' );
	}

	/**
	 * Adds the meta box container.
	 */
	public static function add_meta_box( $post_type ) {
		$post_types = array('vessel');   //limit meta box to certain post types
		if ( in_array( $post_type, $post_types )) {
			if( isset($_GET['post']) && !empty($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit'){				
				add_meta_box(
					'source_information'
					,__( 'Source Information', 'yatco' )
					,array( __CLASS__, 'render_meta_box_source_information' )
					,$post_type
					,'side'
					,'high'
				);
			}
			add_meta_box(
				'vessel_specification'
				,__( 'Vessel Specification', 'yatco' )
				,array( 'YA_Meta_Box_Vessel_Specification', 'output' )
				,$post_type
				,'normal'
				,'high'
			);
			add_meta_box( 'postexcerpt',
				__( 'Vessel Short Description', 'yatco' )
				,array( __CLASS__, 'render_meta_box_postexcerpt' )
				,$post_type
				,'normal'
			);
			add_meta_box(
				'photo_gallery'
				,__( 'Photo gallery', 'yatco' )
				,array( 'YA_Meta_Box_Photo_Gallery', 'output' )
				,$post_type
				,'normal'
			);
			add_meta_box(
				'vessel_video'
				,__( 'Videos', 'yatco' )
				,array( 'YA_Meta_Box_Videos', 'output' )
				,$post_type
				,'normal'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public static function save($post_id, $post) {
		if ( isset( $_GET['reload_vessel'] ) )
			return;
		
		YA_Meta_Box_Photo_Gallery::save($post_id, $post);
		YA_Meta_Box_Videos::save($post_id, $post);
		YA_Meta_Box_Vessel_Specification::save($post_id, $post);
	}


	public static function reload_vessel( ) {
	
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
	            add_option('yatco_admin_notice', '2');
	        }else{

		        $api->deactivate_vessel( $VesselID );
	        	add_option('yatco_admin_notice', '1');
	        }
		}
        $url = remove_query_arg( 'reload_vessel' );
        wp_redirect( $url );
        exit;
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public static function render_meta_box_source_information( $post ) {
		$VesselID = get_post_meta($post->ID, 'VesselID', true);
		?>
		<div class="misc-pub-section misc-pub-post-status">
			<?php _e('Source', 'yatco');?>:
			<strong><?php echo ya_get_source($post->ID); ?></strong>
		</div>
		<?php
		if( $VesselID ){
			?>
			<a href="<?php echo esc_url( add_query_arg( 'reload_vessel', $VesselID ) ); ?>" class="button button-primary button-large"><?php _e('Reload vessel', 'yatco'); ?></a>
			<?php			
		}
	}

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function render_meta_box_postexcerpt( $post ) {

		$settings = array(
			'textarea_name' => 'excerpt',
			'quicktags'     => array( 'buttons' => 'em,strong,link' ),
			'tinymce'       => array(
				'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
				'theme_advanced_buttons2' => '',
			),
			'editor_css'    => '<style>#wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}</style>'
		);

		wp_editor( htmlspecialchars_decode( $post->post_excerpt ), 'excerpt', apply_filters( 'ya_vessel_short_description_editor_settings', $settings ) );
	}


	public static function admin_notices()
	{
		$admin_notice = get_option('yatco_admin_notice');
		delete_option('yatco_admin_notice');
		if( $admin_notice == '1'){
		?>
		    <div class="error notice">
		        <p><?php _e( 'Sorry, the vessel cannot be loaded. Mark as inactive.', 'yatco' ); ?></p>
		    </div>
	    <?php
		}else if( $admin_notice == '2'){
		?>
		    <div class="updated notice">
		        <p><?php _e( 'The vessel successfully loaded.', 'yatco' ); ?></p>
		    </div>
	    <?php
		}
	}
}

YA_Meta_Boxes::init();