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
		?>
		Video
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$videos = isset( $_POST['vessel_vedeos'] ) ? $_POST['vessel_vedeos'] : array();

		update_post_meta( $post_id, '_vessel_vedeos', implode( ',', $videos ) );
	}
}
