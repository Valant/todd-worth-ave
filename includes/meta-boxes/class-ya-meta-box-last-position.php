<?php
/**
 * Vessel Last Known Position
 *
 * Display the vessel last position meta box.
 *
 * @author      Valant
 * @category    Admin
 * @package     Yatco/Meta Boxes
 * @version     1.0.0
 * @since       0.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * YA_Meta_Box_Last_Position Class.
 */
class YA_Meta_Box_Last_Position {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		$video_urls = get_post_meta($post->ID, '_last_spotted', true);
		if( !is_array($video_urls) || empty($video_urls) )
			$video_urls = array(
				array(
				'location' => '',
				'date' => ''
				)
			);
		?>
		<div class="options_group ya-repeater-table" id="last_spotted" >
			<div class="form-field downloadable_files">
				<label><?php _e('Last Spotted', 'yatco'); ?></label>
				<table class="wp-list-table widefat fixed" >
					<thead>
						<tr>
							<td><?php _e('Location', 'yatco'); ?></td>
							<td style="width: 150px;"><?php _e('Date', 'yatco'); ?></td>
							<td class="actions"></td>
						</tr>
					</thead>
					<tbody class="ya-repeater-tbody">
						<?php $i = 0; foreach ($video_urls as $location) {
							?>
							<tr data-index="<?php echo $i; ?>">
								<td><input type="text" name="last_spotted[<?php echo $i; ?>][location]" value="<?php echo $location['location']; ?>"></td>
								<td><input type="text" name="last_spotted[<?php echo $i; ?>][date]" value="<?php echo $location['date']; ?>" class="ya-datepicker"></td>
								<td class="actions"><a href="#" class="ya-repeater-remove-row" title="<?php _e('Remove', 'yatco'); ?>">&times;</a></td>
							</tr>
							<?php
							$i++;
						} ?>				
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">
								<a href="#" data-tmpl="tmpl-location-row" class="button button-large alignright ya-repeater-add-row"><?php _e('Add row', 'yatco'); ?></a>
							</td>
						</tr>
					</tfoot>
				</table>
				<script type="text/template" id="tmpl-location-row">
					<tr data-index="__index__">
						<td><input type="text" name="last_spotted[__index__][location]" value=""></td>
						<td><input type="text" name="last_spotted[__index__][date]" value="" class="ya-datepicker"></td>
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
		$videos = isset( $_POST['last_spotted'] ) ? $_POST['last_spotted'] : array();

		update_post_meta( $post_id, '_last_spotted', $videos );
	}
}
