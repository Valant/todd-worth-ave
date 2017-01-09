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
						do_action('vessel_specification_category_' . $category, $post);
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
		global $wpdb;

		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !isset($_POST['VesselType']) ) {
			return;
		}

		$specification_fields = get_vessel_specification_fields();

		foreach ($specification_fields as $category => $groups) {
			foreach ($groups as $key => $value) {
				if ( $groups && is_array($groups) ){
					foreach ($groups as $fields) {

						foreach ($fields as $field) {
							self::save_field( $post_id, $field );
						}
						
					}
				}
			}
		}
	}

	/**
	 * Save meta box field.
	 */
	private static function save_field( $post_id, $field )
	{
		$field_name = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field_type = isset($field['type'])  ? $field['type'] : 'text';
		$options    = isset($field['options'])  ? $field['options'] : array();

		switch ($field_type) {
			case 'textarea':
				$value = isset( $_POST[$field_name] ) ? wp_kses_post( stripslashes( $_POST[$field_name] ) ) : '';
				break;
				
			case 'checkbox':
				$cbvalue = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'Yes';
				$value   = isset( $_POST[$field_name] ) ? $cbvalue : ( $cbvalue == 'Yes' ? 'No' : '');
				break;

			case 'taxonomy':
				if( isset($field['taxonomy']) && !empty($field['taxonomy']) && taxonomy_exists($field['taxonomy']) ){
					$value = isset( $_POST[$field_name] ) ? $_POST[$field_name]: '';
					if( is_array($value) ){
			            $value = array_map( 'intval', $value );
			            $value = array_unique( $value );
			        }else{
			        	$value = intval( $value );
			        }
			        wp_set_object_terms( $post_id, $value, $field['taxonomy'] );					
				}
		        return;
				break;

			case 'units':
				$value = isset( $_POST[$field_name] ) ? ya_clean( $_POST[$field_name] ) : '';
				$unit  = isset( $_POST[$field_name . '_unit'] ) ? ya_clean( $_POST[$field_name . '_unit'] ) : '';

				foreach ($options as $key => $optv) {
					$_unit = array(
			            ucfirst($key),
			            strtoupper($key)
			        );
			        $_value = ($key===$unit) ? $value : ya_convert_measurement($value, $unit, $key);
			        if( $_value && !empty($_value) ){
						update_post_meta( $post_id, $field_name . $_unit[0], $_value );
						update_post_meta( $post_id, $field_name . $_unit[1], $_value );                    	
						update_post_meta( $post_id, $field_name . $key, $_value );
                    }
				}
				update_post_meta( $post_id, $field_name . '_unit', $unit );

				break;
			
			default:
				$value = isset( $_POST[$field_name] ) ? ya_clean( $_POST[$field_name] ) : '';
				break;
		}
		update_post_meta( $post_id, $field_name, $value );

        do_action_ref_array('after_save_vessels_field_' . $field_name, array(
            'post_id' => $post_id,
            'field' => $field_name,
            'value' => $value,
            'unit' => isset($unit) ? $unit : null,
        ));

	}
}
