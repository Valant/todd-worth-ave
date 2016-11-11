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

		foreach (array('vessel_amenities', 'vessel_toys') as $t_key) {
			add_action( $t_key . '_add_form_fields', array( __CLASS__, 'taxonomy_add_image_url' ), 10 );
			add_action( $t_key . '_edit_form_fields', array( __CLASS__, 'taxonomy_edit_image_url' ), 10 );
			add_action( 'edited_' . $t_key, array( __CLASS__, 'save_taxonomy_custom_meta' ), 10);  
			add_action( 'create_' . $t_key, array( __CLASS__, 'save_taxonomy_custom_meta' ), 10);
		}


		add_action( 'save_post', array( __CLASS__, 'save' ), 777, 2 );		
		add_action( 'save_post', array( __CLASS__, 'save_meta_box_vessel_status' ), 777, 2 );

		add_action('updated_post_meta', array(__CLASS__), 'afterPostMeta', 10, 4);
	}

	public static function remove_meta_boxes()
	{
		$taxonomies = YA_Taxonomies::get_taxonomies_names();
		foreach ($taxonomies as $key => $value) {
			remove_meta_box( 'tagsdiv-' . $value['slug'], 'vessel' , 'side' ); 
		}

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
				add_meta_box(
					'vesssel_status',
					__('Status', 'yatco'),
					array(__CLASS__, 'render_meta_box_vessel_status'),
					$post_type,
					'side',
					'high'
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
			$api      = new YA_Admin_API();
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
	 * Status checkboxes
	 * @param WP_Post $post
	 */
	public static function render_meta_box_vessel_status($post)
	{

		$meta = get_post_meta( $post->ID );
		$timesSoldMax = 4;
		$fieldList = self::statusFields();
		?>

		<div class="misc-pub-section misc-pub-post-status">
				<?php $c=0; ?>
				<?php foreach ($fieldList as $fieldName => $fieldCaption) : $c++; ?>
					<input type="checkbox" name="<?= $fieldName ?>" id="<?= $fieldName ?>" value="1" <?php if ( isset ( $meta[$fieldName] ) && $meta[$fieldName][0] ) echo 'checked'; ?> />
					<label for="<?= $fieldName ?>" class="vessel-status-row-title"><?php _e( $fieldCaption, 'yatco' )?></label>
					<?php if ($c<count($fieldList)) : ?><br><?php endif; ?>
				<?php endforeach; ?>
		</div>
		<hr>
		<h2 class="" style="font-weight: 600;"><span><?= __('Times Sold', 'yatco') ?></span></h2>
		<hr>
		<?php
		for ($i=0;$i<=$timesSoldMax; $i++) : ?>
		<input type="radio" name="times_sold" id="times_sold_<?= $i ?>" value="<?= $i ?>" <?php if (isset($meta['times_sold']) && $meta['times_sold'][0] == $i) echo 'checked'; ?> ><label for="times_sold_<?= $i ?>"><?= $i ?></label>&nbsp;&nbsp;&nbsp;
		<?php endfor;
	}

	/**
	 * @param integer $post_id
	 * @param WP_Post $post
	 */
	public static function save_meta_box_vessel_status( $post_id, $post ) {

		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );

		// Exits script depending on save status
		if ( $is_autosave || $is_revision ) {
			return;
		}

		$metaKeys = array_keys(self::statusFields());

		foreach ($metaKeys as $key) {
			update_post_meta( $post_id, $key, (isset($_POST[$key]) &&  $_POST[$key]) ? 1 : 0);
		}
		update_post_meta( $post_id, 'times_sold', (isset($_POST['times_sold']) &&  $_POST['times_sold']) ? (int)$_POST['times_sold'] : 0);

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

	// Add term page
	public static function taxonomy_add_image_url() {
		// this will add the custom meta field to the add new term page
		?>
		<div class="form-field">
			<label for="term_meta[image_url]"><?php _e( 'Image URL', 'yatco' ); ?></label>
			<input type="text" name="term_meta[image_url]" id="term_meta[image_url]" value="">
		</div>
	<?php
	}

	// Edit term page
	public static function taxonomy_edit_image_url($term) {
	 
		// put the term ID into a variable
		$t_id = $term->term_id;
	 
		$image_url = get_term_meta( $t_id, "image_url", true ); ?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[image_url]"><?php _e( 'Image URL', 'yatco' ); ?></label></th>
			<td>
				<input type="text" name="term_meta[image_url]" id="term_meta[image_url]" value="<?php echo esc_attr( $image_url ) ? esc_attr( $image_url ) : ''; ?>">
			</td>
		</tr>
	<?php
	}

	// Save extra taxonomy fields callback function.
	public static function save_taxonomy_custom_meta($term_id )
	{
		if ( isset( $_POST['term_meta'] ) ) {

			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $meta_key ) {
				if ( isset ( $_POST['term_meta'][$meta_key] ) ) {
					update_term_meta( $term_id, $meta_key, $_POST['term_meta'][$meta_key] );
				}
			}
		}
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

	/**
	 * Vessel status check fields list
	 * @return array
	 */
	public static function statusFields()
	{
		return array(
			'vessel_status_for_sale' => 'For Sale',
			'vessel_status_for_charter' => 'For Charter',
			'vessel_status_featured_for_sale' => 'Featured For Sale',
			'vessel_status_featured_for_charter' => 'Featured For Charter',
			'vessel_status_sold_as_new' => 'Sold As New Construction',
		);
	}

	public static function afterPostMeta($meta_id, $post_id, $meta_key, $meta_value)
	{
		if ($meta_key === 'Flag') {
			$list = ya_get_flags();
			if (!isset($list[$meta_value])) {
				$list[$meta_value] = $meta_value;
				ya_save_flags($list);
			}
		}
	}

}

YA_Meta_Boxes::init();