<?php
/**
 * Admin View: Notice - Update
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div id="message" class="updated yatco-message ya-connect">
	<p><?php _e( '<strong>Yatco Data Update Required</strong> &#8211; We just need to update your install to the latest version', 'yatco' ); ?></p>
	<p class="submit"><a href="<?php echo esc_url( add_query_arg( 'do_update_yatco', 'true', admin_url( 'admin.php?page=ya-settings' ) ) ); ?>" class="ya-update-now button-primary"><?php _e( 'Run the updater', 'yatco' ); ?></a></p>
</div>
<script type="text/javascript">
	jQuery('.ya-update-now').click('click', function(){
		var answer = confirm( '<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'yatco' ); ?>' );
		return answer;
	});
</script>
