<?php

// define( 'DOING_AJAX', true );
// if ( ! defined( 'WP_ADMIN' ) ) {
	// define( 'WP_ADMIN', true );
// }

/** Load WordPress Bootstrap */
require_once( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php' );

/** Allow for cross-domain requests (from the frontend). */
// send_origin_headers();

// Require an action parameter
if ( empty( $_REQUEST['action'] ) )
	die( '0' );

/** Load WordPress Administration APIs */
require_once( ABSPATH . 'wp-admin/includes/admin.php' );

/** Load Ajax Handlers for WordPress Core */
require_once( ABSPATH . 'wp-admin/includes/ajax-actions.php' );

/** This action is documented in wp-admin/admin.php */
// do_action( 'admin_init' );


	$params = $_REQUEST;

	echo "<params>";
	echo "<pre>";
	print_r( $params );
	echo "</pre>";

    $args = array();
    if ( isset( $params['args'] ) && ! empty( $params['args'] ) ) {
        $args = explode( ",", $params['args'] );
    }

	echo "<args>";
	echo "<pre>";
	print_r( $args );
	echo "</pre>";

    if ( $args ) {
        do_action_ref_array( $params['task'], $args );
    } else {
        do_action( $params['task'] );
    }


    die( json_encode( array('status' => 'success') ) );

// Default status
die( '0' );
