<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

	global $wpdb;
	$post_title = $wpdb->get_results($wpdb->prepare("select post_title from ".$wpdb->prefix."posts where post_type=%s", 'product'));
	$prod_list = array();
	foreach( $post_title as $title=>$prod)
	{
    		foreach( $prod as $key=>$productt )
    		{
        		$prod_list[] = $productt;
    		}
	}
	$prod_id = $wpdb->get_results($wpdb->prepare("select ID from ".$wpdb->prefix."posts where post_type=%s" , 'product'));
	$prodid_list = array();
	foreach( $prod_id as $ke=>$ID)
	{
    		foreach( $ID as $pdt_key=>$prodid )
    		{
        		$prodid_list[] = $prodid;
    		}
	}

	$items = array_combine( $prodid_list , $prod_list );
	global $wpdb;
	$users = $wpdb->get_results( "select distinct user_id,email from wci_history",ARRAY_A );
	foreach($users as $userkey=>$userval )
	{
    		foreach( $items as $pid => $pname )
    		{
        		$user_id = $userval['user_id'];
        		$customer_email = $userval['email'];
        		$user_ip = $_SERVER['REMOTE_ADDR'];
        		if ( wc_customer_bought_product( $customer_email, $user_id, $pid) )
        		{
            			$email_list = array();
				$update_user_purchased = $wpdb->get_results($wpdb->prepare("select user_email,product_id from wci_user_purchased_history where user_email=%s AND product_id=%d" , $customer_email , $pid));
				
            			if(empty($update_user_purchased)){
                		
			$wpdb->insert( 'wci_user_purchased_history' , array( 'user_ip' => $user_ip , 'user_id' => $user_id , 'user_email' => $customer_email , 'product_id' => $pid , 'product_name' => $pname ) );		
            			}
            			else {

                		$wpdb->query("update wci_user_purchased_history set user_ip = $user_ip,user_id='$user_id', user_email = $customer_email,product_id = $pdt_id, product_name = $pdt_name");
            		    	}
        		}
    		}
}
