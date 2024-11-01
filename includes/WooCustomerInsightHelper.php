<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

class WooCustomerInsightHelper {

	public $wci_customer_id;
	public $wc_cookie_id;
	public $wci_cookie_value;
	public function __construct()
	{
		$WC_session_obj = new WC_Session_Handler();
		$this->wci_customer_id = $WC_session_obj->get_customer_id();
		$wci_get_cookie = $WC_session_obj->get_session_cookie();
		$this->wc_cookie_id = $wci_get_cookie[0];
		$this->wci_cookie_value = maybe_serialize( $WC_session_obj->get_session_data() );
	}	

	//Get session id using session_key	
	public function wci_get_session_id( $wci_session_key )
	{
		global $wpdb;
		$get_session_id = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}woocommerce_sessions where session_key=%s" , $wci_session_key ) );
		if( !empty( $get_session_id )) {
			$session_id = $get_session_id[0]->session_id;
			return $session_id;
		}
	}

	public function ip_details($IPAddress)
	{
		$json       = file_get_contents("http://ipinfo.io/{$IPAddress}");
		$details    = json_decode($json);
		return $details;
	}

	// Set cookie for guest user
	public function set_wci_session_key( $sess_key )
	{
		setcookie( 'wci_session_key' , $sess_key , time() + (86400 * 2 ) ); // set cookie for 2 days
	}	
	
	//get guest cookie id
	public function get_wci_session_key( )
	{
		$wci_session_key = $_COOKIE['wci_session_key'];
		return $wci_session_key;
	}	
	// Changed function from all_info into trackingHistory
	public function trackingHistory() {
		global $countries;
		global $wpdb;
		global $woocommerce;
		global $current_user;
		// Set cookie for guest
		$WC_cookie_key = $this->wc_cookie_id;
		$WCI_cookie_key = $this->get_wci_session_key();
		if( !empty( $WC_cookie_key ))
		{
			$temp_session_id = $this->wci_get_session_id( $WC_cookie_key );
			$temp_session_key = $WC_cookie_key;
			if( is_user_logged_in() )
			{	
				$current_user = wp_get_current_user();
				$user_email = $current_user->user_email;
                        	$user_name = $current_user->display_name;
				$wpdb->update( 'wci_activity' ,
					array( 'session_id' => $temp_session_id,
					       'session_key'=> $temp_session_key,
					       'user_id' => $user_name,
					       'user_email' => $user_email),
					array( 'session_id' => '0' ,
					       'session_key'=> $WCI_cookie_key,
					       'user_id' => "Guest",
					       'user_email' => "" ),
					array( '%s' , '%s' , '%s' , '%s' )
				     );	
			}
			else
			{
				$wpdb->update( 'wci_activity' ,
                                        array( 'session_id' => $temp_session_id,
                                               'session_key'=> $temp_session_key),
                                        array( 'session_id' => '0' ,
                                               'session_key'=> $WCI_cookie_key)
                                     );
			}
			$this->set_wci_session_key( $temp_session_key );
		}
		else
		{	//check cookie value exist
			if( !empty( $WCI_cookie_key ))
			{
				if( is_user_logged_in() )
				{
					$temp_session_key = get_current_user_id();
					//UPDATE WHEN GUEST => USER 
					$current_user = wp_get_current_user();
                                	$user_email = $current_user->user_email;
                                	$user_name = $current_user->display_name;
                                	$wpdb->update( 'wci_activity' ,
                                        array( 'session_key'=> $temp_session_key,
                                               'user_id' => $user_name,
                                               'user_email' => $user_email),
                                        array( 'session_id' => '0' ,
                                               'session_key'=> $WCI_cookie_key,
                                               'user_id' => "Guest",
                                               'user_email' => "" ),
                                        array( '%s' , '%s' , '%s' , '%s' )
                                     );
					$this->set_wci_session_key( $temp_session_key );
				}
				else
				{
					$temp_session_key = $WCI_cookie_key;
				}
			}
			else
			{
				if( is_user_logged_in() )
				{
					$temp_session_key = get_current_user_id();
					
					$this->set_wci_session_key( $temp_session_key );					
				}
				else
				{
				$sess_key = $this->wci_customer_id;
				$this->set_wci_session_key($sess_key);
                        	$temp_session_key = $sess_key;
				}
			}
		}

		// Get visitor's location information based on the IP address
		$myPublicIP = $_SERVER['REMOTE_ADDR'];
		$details    = $this->ip_details("$myPublicIP");
		$get_country = '';
		$city = $details->city;
		$country_code = $details->country;
		$country_keys = array_keys( $countries );
		if( in_array($country_code,$country_keys ))
		{
			$get_country .= $countries[$country_code];
		}
		$country_name = $city . "," . $get_country; 
		echo '<input type="hidden" id="req" value="'. esc_url($_SERVER['REQUEST_URI']) .'">';
		echo '<input type="hidden" id="ip" value="'. esc_attr($_SERVER['REMOTE_ADDR']) .'">';
		echo '<input type="hidden" id="country" value="'. esc_attr($country_name) .'">';
		$ajaxURL = admin_url( 'admin-ajax.php' );
		echo '<input type="hidden" id="ajaxurl" value="'. esc_url($ajaxURL) .'">';
		$get_current_url = new SM_Woo_Customer_Insight();
		echo '<input type="hidden" id="page_url" value="'.esc_url($get_current_url->current_url).'">';
		$current_url = $get_current_url->current_url;
		$cart_url = $woocommerce->cart->get_cart_url();
		$scheme = parse_url($cart_url, PHP_URL_SCHEME);
		$rmscheme = str_replace($scheme,'',$cart_url);
		$cart_url = str_replace('://','',$rmscheme);
		echo '<input type="hidden" id="cart_url" value="'. esc_url($cart_url) .'">';
		require_once('hasPurchased.php');

		$shop_page_url = '';
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		if ( $shop_page_id ) {
			$shop_page_url = get_permalink( $shop_page_id );
		}

		$shop_scheme = parse_url($shop_page_url, PHP_URL_SCHEME);
		$rm_shop_scheme = str_replace($shop_scheme,'',$shop_page_url);
		$shop_page_url = str_replace('://','',$rm_shop_scheme);

		$myaccount_page_url = '';
		$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
		if ( $myaccount_page_id ) {
			$myaccount_page_url = get_permalink( $myaccount_page_id );
			$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
		}
		$myacc_scheme = parse_url($myaccount_page_url, PHP_URL_SCHEME);
		$rm_myacc_scheme = str_replace( $myacc_scheme , '' , $myaccount_page_url );
		$myaccount_page_url = str_replace( '://','',$rm_myacc_scheme );

		$checkout_url = $woocommerce->cart->get_checkout_url();
		$checkout_scheme = parse_url($checkout_url, PHP_URL_SCHEME);
		$rm_shop_scheme = str_replace($checkout_scheme,'',$checkout_url);
		$checkout_url = str_replace('://','',$rm_shop_scheme);

		$site_url = site_url();
		$page_scheme = parse_url($site_url,PHP_URL_SCHEME);
		$post_id =  url_to_postid( $page_scheme."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ) ;
		$page_title = get_the_title( $post_id );
		// If we can't get shop id then get it from option table
		if( $post_id == 0 && $current_url == $shop_page_url)
		{
			$post_id = get_option( 'woocommerce_shop_page_id' );
		} 
		echo '<input type="hidden" id="prod_id" value="'. esc_attr($post_id) .'">';

		if( preg_match( "/product/" , $current_url ) )
		{
			$page_title = get_the_title( $post_id );
		}
		if( preg_match( "/cart/" , $current_url ) )
		{
			$page_title = "Cart";
		}
		if( preg_match( "/checkout/" , $current_url ) )
		{
			$page_title = "Checkout";
		}
		if( preg_match( "/my-account/" , $current_url ) )
		{
			$page_title = "My-account";
		}
		if( preg_match( "/shop/" , $current_url ) )
		{
			$page_title = "Shop";
		}
		echo '<input type="hidden" id="page_title" value="'. esc_attr($page_title) .'">';
		$post_type = get_post_type( $post_id );
		$product = get_the_title( $post_id );

		echo  '<input type="hidden" id="product" value="'. esc_attr($product) .'">';
		if( $post_type == 'product' )
		{
			$prod_page = $current_url ;
		}
		// Get cart items
		$get_cart_items = $woocommerce->cart->get_cart();
		$cart_items = array();
		foreach($get_cart_items as $item => $values) {
			$_product = $values['data']->post;
			$cart_items[]  =  $_product->post_title ;
		}
		$cart_items = json_encode($cart_items);
		echo "<input type='hidden' id='cart_items' value=' {$cart_items} '>";
		$id = $wpdb->get_results( $wpdb->prepare("select ID,post_date from ".$wpdb->posts." where post_status IN (%s,%s)" , 'wc-pending','wc-on-hold' ),ARRAY_A );
		foreach( $id as $key=>$val )
		{
			$prod_id = $val['ID'];
			$post_date = $val['post_date'];
			$curr_date= date('Y-m-d H:i:s');
			$diff = strtotime($curr_date) - strtotime($post_date);
			$time_diff = round( $diff /3600 );
			$order = new WC_Order($val['ID']);
			// here the customer data
			$order_email = $order->billing_email;
			$customer = get_userdata($order->customer_user);
			$order_cust_name =  $customer->display_name;
			$check_entry = $wpdb->get_results($wpdb->prepare( "select order_id from wci_abandon_cart where order_id=%d" , $prod_id ), ARRAY_A);
			$check_order_id = $check_entry[0]['order_id'];
			if( empty( $check_order_id ) )
			{
				if( $time_diff >= 1 ){
					$wpdb->insert( 'wci_abandon_cart' , array( 'user_email' => $order_email , 'order_id' => $prod_id , 'date' => $post_date , 'time_difference' => $time_diff ) );
			}
			}
			else
			{
				$wpdb->update( 'wci_abandon_cart' , array( 'time_difference' => $time_diff ) , array( 'order_id' => $prod_id ) );
			}
			$check_pending = $wpdb->get_results("select order_id from wci_abandon_cart",ARRAY_A);
			foreach( $check_pending as $arr=>$ord_id['order_id']  )
			{
				$ordr_id = $ord_id['order_id'];
				foreach( $ordr_id as $idd )
				{
					$post_stat = $wpdb->get_var($wpdb->prepare("select post_status from ".$wpdb->posts." where ID=%d" , $idd));
					if( $post_stat != 'wc-pending' && $post_stat != 'wc-on-hold' )
					{
						$wpdb->query("delete from wci_abandon_cart where order_id='$idd'");
					}
				}
			}

			$items = $wpdb->get_results( $wpdb->prepare("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=%d", "$prod_id"),ARRAY_A );

			foreach( $items as $itm=>$prod ){
				$pp = $prod['order_item_name'];
			}
		}
		
		$current_user = wp_get_current_user();
		if( is_user_logged_in() ) {
			$user_id = $current_user->ID;
			$user_login = $current_user->user_login;
			$user_email = $current_user->user_email;
			$user_name = $current_user->display_name;
			$is_user = "1";
//User Session
			$user_session = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}woocommerce_sessions where session_key=%s" , $user_id ) );
			if( !empty( $user_session ))
			{
				$session_id = $user_session[0]->session_id;
				$session_key = $user_session[0]->session_key;
				$session_val = $user_session[0]->session_value;
			}
			else
			{	
                		$session_key = $user_id;
				$session_id = '0';
				$session_val = $this->wci_cookie_value;
			}
		} 
		else {
			$session_key = $temp_session_key;
			$session_val = $this->wci_cookie_value;
			$session_id = $this->wci_get_session_id($session_key);
			$user_name = "Guest";
			$is_user = "0";
			if( !empty( $session_id ) )
			{
			// Use the above session_id
			}
			else
			{
				$session_id = '0';
			}
		}
		echo '<input type="hidden" id="user_email" value="'. sanitize_email($user_email) .'">';
		echo '<input type="hidden" id="user_name" value="'. esc_attr($user_name) .'">';
		echo '<input type="hidden" id="is_user" value="'. esc_attr($is_user) .'">';
		echo '<input type="hidden" id="session_id" value="' . esc_attr($session_id). '">';
		echo '<input type="hidden" id="session_key" value="' . esc_attr($session_key).'">';
		echo "<input type='hidden' id='session_val' value=' {$session_val}'>";

		if( $current_url == $cart_url || $current_url == $shop_page_url || $current_url == $myaccount_page_url || $current_url == $checkout_url || $current_url == $logout_url || $current_url == $prod_page || $post_id != 0 )
		{
			wp_enqueue_script('jquery');
			wp_enqueue_script('timespent',plugins_url('woo-customer-insight/js/wci_timespent.js'));
			wp_enqueue_script('buttonclick',plugins_url('woo-customer-insight/js/wci_button-click.js'));
		}
		
	}


	public function WCI_order_status_completed( $order_id ) {
		$order_status = "Completed";
		global $wpdb;
		$check_order_id = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_id=%d" , $order_id) );		
		if( !empty( $check_order_id ) ) 
		{
			$wpdb->update( 'wci_successful_purchases' , array( 'order_status' => $order_status  ) , array( 'order_id' => $order_id ) );
		}
		$checkorder_in_session = $wpdb->get_results( $wpdb->prepare( "select order_id from wci_user_session where order_id=%d" , $order_id ) );		       if( !empty( $checkorder_in_session ) )
		{
			$wpdb->update( 'wci_user_session' ,array( 'payment_success' => '1' ) , array( 'order_id' => $order_id ) );
		}
	}

	public function WCI_order_status_failed( $order_id ) {
		$order_status = "Failed";
		global $wpdb;
		$check_order_id = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_id=%d", $order_id) );
		if( !empty( $check_order_id ) )
		{
			$wpdb->update( 'wci_successful_purchases' , array( 'order_status' => $order_status ) , array( 'order_id' => $order_id ) );
		}
		$checkorder_in_session = $wpdb->get_results( $wpdb->prepare( "select order_id from wci_user_session where order_id=%d" , $order_id ) );
                if( !empty( $checkorder_in_session ) )
                {
                        $wpdb->update( 'wci_user_session' ,array( 'payment_failure' => '1' ) , array( 'order_id' => $order_id ) );
                }

	}

}

