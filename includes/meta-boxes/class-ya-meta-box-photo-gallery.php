<?php
/**
 * Vessel Images
 *
 * Display the vessel images meta box.
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Meta Boxes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * YA_Meta_Box_Photo_Gallery Class.
 */
class 	YA_Meta_Box_Photo_Gallery {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		?>
		<div id="vessel_images_container">
			<ul class="vessel_images">
				<?php
					if ( metadata_exists( 'post', $post->ID, '_vessel_image_gallery' ) ) {
						$vessel_image_gallery = get_post_meta( $post->ID, '_vessel_image_gallery', true );
					} else {
						// Backwards compat
						$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_yatco_exclude_image&meta_value=0' );
						$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
						$vessel_image_gallery = implode( ',', $attachment_ids );
					}

					$attachments         = array_filter( explode( ',', $vessel_image_gallery ) );
					$update_meta         = false;
					$updated_gallery_ids = array();

					if ( ! empty( $attachments ) ) {
						foreach ( $attachments as $attachment_id ) {
							$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

							// if attachment is empty skip
							if ( empty( $attachment ) ) {
								$update_meta = true;
								continue;
							}

							echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . $attachment . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'yatco' ) . '">' . __( 'Delete', 'yatco' ) . '</a></li>
								</ul>
							</li>';

							// rebuild ids to be saved
							$updated_gallery_ids[] = $attachment_id;
						}

						// need to update vessel meta to set new gallery ids
						if ( $update_meta ) {
							update_post_meta( $post->ID, '_vessel_image_gallery', implode( ',', $updated_gallery_ids ) );
						}
					}
				?>
			</ul>

			<input type="hidden" id="vessel_image_gallery" name="vessel_image_gallery" value="<?php echo esc_attr( $vessel_image_gallery ); ?>" />

		</div>
		<p class="add_vessel_images hide-if-no-js">
			<a href="#" data-choose="<?php esc_attr_e( 'Add Images to Photo Gallery', 'yatco' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'yatco' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'yatco' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'yatco' ); ?>"><?php _e( 'Add vessel gallery images', 'yatco' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$attachment_ids = isset( $_POST['vessel_image_gallery'] ) ? array_filter( explode( ',', $_POST['vessel_image_gallery'] ) ) : array();

		update_post_meta( $post_id, '_vessel_image_gallery', implode( ',', $attachment_ids ) );
	}
}
