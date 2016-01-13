<?php

/** Load WordPress Bootstrap */
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die( '0' );

/** Load WordPress Administration APIs */
require_once( ABSPATH . 'wp-admin/includes/admin.php' );

/** Load Ajax Handlers for WordPress Core */
// require_once( ABSPATH . 'wp-admin/includes/ajax-actions.php' );

$params = $_REQUEST;

$args = array();
if ( isset( $params['args'] ) && ! empty( $params['args'] ) ) {
    $args = explode( ",", $params['args'] );
}

if ( $args ) {
    do_action_ref_array( $params['task'], $args );
} else {
    do_action( $params['task'] );
}

die( json_encode( array('status' => 'success') ) );

// Default status
die( '0' );
