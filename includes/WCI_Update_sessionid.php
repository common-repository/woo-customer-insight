<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

	$session_key = sanitize_text_field( $_REQUEST['sess_key'] );
	global $wpdb;
	$get_session_id = $wpdb->get_results( $wpdb->prepare( "select session_id from {$wpdb->prefix}woocommerce_sessions where session_key=%s" , $session_key ) );
	$session_id = $get_session_id[0]->session_id;
	if( !empty( $session_id ))
	{
		$wpdb->update( 'wci_user_session' ,
				array( 'session_id' => $session_id ),
				array( 'user_id' => $session_key,
				       'session_id' => '0'	
			   	     )
				 );

		$wpdb->update( 'wci_events' ,
                                array( 'session_id' => $session_id ),
                                array( 'session_key' => $session_key,
                                       'session_id' => '0'
                                     )
                                 );
		$wpdb->update( 'wci_activity' ,
                                array( 'session_id' => $session_id ),
                                array( 'session_key' => $session_key,
                                       'session_id' => '0'
                                     )
                                 );


	}	
die;		
?>
