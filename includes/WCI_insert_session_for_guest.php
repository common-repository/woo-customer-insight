<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

	$count = sanitize_text_field( $_REQUEST['count'] );
	$btn_name = sanitize_text_field( $_REQUEST['btn_name'] );
	$page_url = sanitize_text_field( $_REQUEST['page_url'] );
	$http_host = sanitize_text_field( $_REQUEST['http_host'] );
	$country = sanitize_text_field( $_REQUEST['country'] );
	$user_name = sanitize_text_field( $_REQUEST['user_id'] );
	$user_email = sanitize_text_field( $_REQUEST['user_email'] );
	$prodid = sanitize_text_field( $_REQUEST['prod_id'] );
	$product = sanitize_text_field( $_REQUEST['product'] );
	$date = sanitize_text_field( $_REQUEST['date'] );
	$date_without_time = sanitize_text_field( $_REQUEST['date_without_time'] );
	$WC_session_obj = new WC_Session_Handler();
	$get_cookie_details = $WC_session_obj->get_session_cookie();
                $session_key = $get_cookie_details[0];
	global $wpdb;
	$last_guest_id = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}woocommerce_sessions where session_key=%s" , $session_key ) );
	$guest_session_key = $last_guest_id[0]->session_key;
	$guest_session_id = $last_guest_id[0]->session_id;
	$guest_session_value = $last_guest_id[0]->session_value;

	$wpdb->insert( 'wci_events' , array( 'session_id' => $guest_session_id , 'session_key' => $guest_session_key ,  'user_id' => $user_name, 'user_email' => $user_email , 'user_ip' => $http_host , 'country'=> $country , 'prod_id' => $prodid , 'product' => $product , 'button_name' => $btn_name , 'page_url' => $page_url , 'date' => $date , 'count' => $count , 'date_without_time' => $date_without_time ) , '%s' );

        $wpdb->insert( 'wci_user_session' , array( 'session_id' => $guest_session_id , 'user_id' => $guest_session_key , 'user_name' =>"$user_name" , 'country' => $country ,'is_cart' => '1' , 'product_key' => $prodid , 'product_data' => $product , 'session_value' => "{$guest_session_value}" , 'date' => $date) , array( '%d' , '%s' , '%s' , '%s' , '%d' , '%d' , '%s' , '%s' , '%s' ));

die;

?>
