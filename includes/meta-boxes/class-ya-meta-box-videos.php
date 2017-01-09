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
class YA_Meta_Box_Videos {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		$video_urls = get_post_meta($post->ID, '_vessel_video_urls', true);
		if( !is_array($video_urls) || empty($video_urls) )
			$video_urls = array(
				array(
				'VideoCaption' => '',
				'VideoURL' => ''
				)
			);
		?>
		<div class="options_group ya-repeater-table" id="vessel_video" >
			<div class="form-field downloadable_files">
				<label><?php _e('Video', 'yatco'); ?></label>
				<table class="wp-list-table widefat fixed" >
					<thead>
						<tr>
							<td><?php _e('Caption', 'yatco'); ?></td>
							<td><?php _e('URL', 'yatco'); ?></td>
							<td class="actions"></td>
						</tr>
					</thead>
					<tbody class="ya-repeater-tbody">
						<?php $i = 0; foreach ($video_urls as $video) {
							?>
							<tr data-index="<?php echo $i; ?>">
								<td><input type="text" name="vessel_video[<?php echo $i; ?>][VideoCaption]" value="<?php echo $video['VideoCaption']; ?>"></td>
								<td><input type="text" name="vessel_video[<?php echo $i; ?>][VideoURL]" value="<?php echo $video['VideoURL']; ?>"></td>
								<td class="actions"><a href="#" class="ya-repeater-remove-row" title="<?php _e('Remove', 'yatco'); ?>">&times;</a></td>
							</tr>
							<?php
							$i++;
						} ?>				
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">
								<a href="#" data-tmpl="tmpl-video-row" class="button button-large alignright ya-repeater-add-row"><?php _e('Add row', 'yatco'); ?></a>
							</td>
						</tr>
					</tfoot>
				</table>
				<script type="text/template" id="tmpl-video-row">
					<tr data-index="__index__">
						<td><input type="text" name="vessel_video[__index__][VideoCaption]" value=""></td>
						<td><input type="text" name="vessel_video[__index__][VideoURL]" value=""></td>
						<td class="actions"><a href="#" class="ya-repeater-remove-row" title="<?php _e('Remove', 'yatco'); ?>">&times;</a></td>
					</tr>
				</script>
			</div>
		</div>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$videos = isset( $_POST['vessel_video'] ) ? $_POST['vessel_video'] : array();

		update_post_meta( $post_id, '_vessel_video_urls', $videos );
	}
}
