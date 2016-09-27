<?php
/**
 * Vessel Images
 *
 * Display the vessel specification meta box.
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
 * YA_Meta_Box_Vessel_Specification Class.
 */
class 	YA_Meta_Box_Vessel_Specification {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		$vessel_detail = get_post_meta($post->ID, 'vessel_detail', true);
		#var_dump($vessel_detail);
		
		/*if ( $vessel_detail && is_array($vessel_detail) ){
			foreach ($vessel_detail as $key => $v) {
				preg_match_all('/((?:^|[A-Z])[a-z]+)/', $key, $matches);
  				$label = $matches ? implode(' ', $matches[0]) : $key;
				$value = get_post_meta($post->ID, $key, true);

				if( isset($fields[$matches[0][0]]) ){
					$fields[ $matches[0][0] ][$label] = $value;
					#unset(var);
				}
			}
		}*/
		$categories           = get_vessel_specification_categories();
		$specification_fields = get_vessel_specification_fields();
		?>
		<div class="panel-wrap vessel_data">
			<ul class="vessel_data_tabs ya-tabs">
				<?php
				$class = 'active';
				foreach (array_keys($specification_fields) as $key) {
					$value = isset($categories[$key]) ? $categories[$key] : $key;
					?>
					<li class="<?php echo $key; ?>_options <?php echo $key; ?>_tab <?php echo $class; ?>">
						<a href="#<?php echo $key; ?>_vessel_data"><?php echo $value; ?></a>
					</li>
					<?php
					$class = '';					
				} ?>
			</ul>
			<?php
			$style = 'style="display: block;"';
			foreach ($specification_fields as $category => $groups) {
				?>
				<div id="<?php echo $category; ?>_vessel_data" class="panel yatco_options_panel" <?php echo $style; ?>>					
						<?php
						if ( $groups && is_array($groups) ){
							foreach ($groups as $fields) {
							?>
							<div class="options_group">
							<?php
								foreach ($fields as $field) {
									ya_admin_field($field);
								}
							?>
							</div>
							<?php
							}
						}
						?>					
				</div>
				<?php
				$style = '';
			}
			?>
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
		
	}
}
