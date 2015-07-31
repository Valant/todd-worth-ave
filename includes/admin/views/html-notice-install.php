<?php
/**
 * Admin View: Notice - Install
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div id="message" class="updated yatco-message ya-connect">
	<p><?php _e( '<strong>Welcome to Yatco</strong> &#8211; You\'re almost ready to start :)', 'yatco' ); ?></p>
	<p class="submit">
    <a class="button-primary" href="<?php echo esc_url( add_query_arg( 'ya-hide-notice', 'install' ) ); ?>"><?php _e( 'Load Vessels', 'yatco' ); ?></a>
  </p>
</div>
