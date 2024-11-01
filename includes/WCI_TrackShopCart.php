<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

	$dt = new DateTime();
	$date = $dt->format('Y-m-d H:i:s');
	$date_without_time = date('Y-m-d');
	$count = sanitize_text_field( $_REQUEST['count'] );
	$btn_name = sanitize_text_field( $_REQUEST['btn_name'] );
	$wpuser_id = sanitize_text_field( $_REQUEST['user_id'] );
	$user_email = sanitize_text_field( $_REQUEST['user_email'] );
	$page_url = sanitize_text_field( $_REQUEST['page_url'] );
	$http_host = sanitize_text_field( $_REQUEST['http_host'] );
	$country = sanitize_text_field( $_REQUEST['country'] );
	$session_id = sanitize_text_field($_REQUEST['session_id']) ;
        $session_key = sanitize_text_field($_REQUEST['session_key']) ;
        $session_value = sanitize_text_field($_REQUEST['session_value']);
	$prodid = sanitize_text_field( $_REQUEST['prod_id'] );
	global $wpdb;
	$product = $wpdb->get_var($wpdb->prepare("select post_title from ".$wpdb->posts." where ID=%d" , $prodid ));
	
	//Check for user session or guest session
	if( !(strlen( $session_key) >30) ) {
	$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key ,  'user_id' => $wpuser_id, 'user_email' => $user_email , 'user_ip' => $http_host , 'country'=> $country , 'prod_id' => $prodid , 'product' => $product , 'button_name' => $btn_name , 'page_url' => $page_url , 'date' => $date , 'count' => $count , 'date_without_time' => $date_without_time ) , '%s' );

	$wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>"$wpuser_id" ,'country' => $country, 'is_cart' => '1' , 'product_key' => $prodid ,'product_data'=> $product , 'session_value' => "{$session_value}", 'date' => $date) , array( '%d' , '%s' , '%s' ,'%s', '%d','%d','%s', '%s','%s' ));

		if( $session_id == 0 || $session_id == "" )
                {
                        $id = 0;
                }
                else
                {
                        $id = 1;
                }
                $update_session['id'] = $id;
                $update_session['sess_key'] = $session_key;
                $update_session_array = json_encode( $update_session );
                print_r( $update_session_array );die;
		}
	//Guest session
		else
        	{
                $insert_session['id'] = 11 ; // set 11 for guest to get in js
                $insert_session['count'] = $count;
                $insert_session['btn_name'] = $btn_name;
                $insert_session['wpuser_id'] = $wpuser_id;
                $insert_session['user_email'] = $user_email;
                $insert_session['page_url'] = $page_url;
                $insert_session['http_host'] = $http_host;
                $insert_session['country'] = $country;
                $insert_session['prodid'] = $prodid;
                $insert_session['product'] = $product;
                $insert_session['date'] = $date;
                $insert_session['date_without_time'] = $date_without_time;
                $insert_session_array = json_encode(  $insert_session );
                print_r( $insert_session_array );die;   
        	}
