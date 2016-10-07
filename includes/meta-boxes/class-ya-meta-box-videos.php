<?php
/**
 * Vessel Videos
 *
 * Display the vessel video meta box.
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
 * YA_Meta_Box_Videos Class.
 */
class 	YA_Meta_Box_Videos {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		$video_urls = get_post_meta($post->ID, '_vessel_video_urls', true);
		if( !is_array($video_urls) )
			$video_urls = array(
				array(
				'VideoCaption' => '',
				'VideoURL' => ''
				)
			);
		?>
		<table class="wp-list-table widefat fixed ">
			<thead>
				<tr>
					<td><?php _e('Caption', 'yatco'); ?></td>
					<td><?php _e('URL', 'yatco'); ?></td>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; foreach ($video_urls as $video) {
					?>
					<tr>
						<td><input type="text" name="vessel_vedeo[<?php echo $i; ?>][VideoCaption]" value="<?php echo $video['VideoCaption']; ?>"></td>
						<td><input type="text" name="vessel_vedeo[<?php echo $i; ?>][VideoURL]" value="<?php echo $video['VideoURL']; ?>"></td>
					</tr>
					<?php
					$i++;
				} ?>				
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$videos = isset( $_POST['vessel_vedeo'] ) ? $_POST['vessel_vedeo'] : array();

		update_post_meta( $post_id, '_vessel_video_urls', implode( ',', $videos ) );
	}
}
