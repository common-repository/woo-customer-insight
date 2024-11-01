<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

	$count = intval( $_REQUEST['count'] );
	$btn_name = sanitize_text_field( $_REQUEST['btn_name'] );
	$wpuser_id = sanitize_text_field( $_REQUEST['user_id'] );
	$user_email = sanitize_text_field( $_REQUEST['user_email'] );
	$page_url = sanitize_text_field( $_REQUEST['page_url'] );
	$http_host = sanitize_text_field( $_REQUEST['http_host'] );
	$country = sanitize_text_field( $_REQUEST['country'] );
	$prodid = intval( $_REQUEST['prod_id'] ) ;
	$session_id = sanitize_text_field( $_REQUEST['session_id'] );
	$session_key = sanitize_text_field( $_REQUEST['session_key'] ) ;
	$session_value = sanitize_text_field( $_REQUEST['session_value'] );
	$prod = sanitize_text_field( $_REQUEST['product'] );

	switch( $btn_name )
	{
		case 'Checkout':
		$Checkout = "1" ;
		break;		

		case 'CashOnDelivery':
		$Payment = "1";
		break;

		case 'ProceedToPaypal':
		$Payment = "1";
		break;

		case 'DirectBankTransfer':
		$Payment = "1";
		break;

		case 'ChequePayment':
		$Payment = "1";
		break;

	}
	$prod = str_replace('\\','',$prod);
	$pro = json_decode($prod);
	$dt = new DateTime();
	$date = $dt->format('Y-m-d H:i:s' );
	$date_without_time = date('Y-m-d');
	global $wpdb;
	foreach($pro as $key=>$product)
	{
		$products .= $product.',';
	}
		$product_list = rtrim( $products , "," );
		$wpdb->insert( 'wci_events' , array( 'session_id' => $session_id , 'session_key' => $session_key , 'user_id'=> $wpuser_id , 'user_email' => $user_email , 'user_ip' => $http_host, 'country' => $country, 'prod_id' => $prodid, 'product' => $product_list, 'button_name' => $btn_name, 'page_url' => $page_url, 'date' => $date, 'count' => $count, 'date_without_time' => $date_without_time ), '%s' );	

		if( !empty( $Checkout ) ) 
		{
			$wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>$wpuser_id , 'country' => $country , 'product_data' => $product_list , 'is_checkout' => $Checkout , 'session_value' => "{$session_value}" , 'date' => $date), array( '%d' , '%s' , '%s' ,'%s', '%s', '%d' , '%s','%s' ) );
		}
	
		if( !empty( $Payment ) )	
		{
			$wpdb->insert( 'wci_user_session' , array( 'session_id' => $session_id , 'user_id' => $session_key , 'user_name' =>$wpuser_id , 'country' => $country , 'product_data' => $product_list ,'is_payment' => $Payment , 'session_value' => "{$session_value}", 'date' => $date) , array( '%d' , '%s' , '%s' ,'%s', '%s', '%d' , '%s','%s' ) );
		}