<?php

// require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die( '0' );

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
