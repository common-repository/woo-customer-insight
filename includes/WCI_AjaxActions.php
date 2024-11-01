<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

class WCI_AjaxActions {

	public function __construct()
	{

	}

	public static  function visitor_time_spent()
	{
		require_once('WCI_VisitorTimeSpent.php');
	}

	public static function button_click()
	{
		require_once( 'WCI_TrackButtonClicks.php' );
		die;
	}

	public static function shop_cart()
	{
		require_once( 'WCI_TrackShopCart.php' );
		die;
	}

	public static function cart()
	{
		require_once( 'WCI_TrackCart.php' );
		die;
	}

	public static function update_sessionid()
	{
		require_once( 'WCI_Update_sessionid.php' );
		die;
	}

	public static function insert_sessionid_for_guest()
	{
		require_once( 'WCI_insert_session_for_guest.php' );
		die;
	}
	public static function wci_funnel_chart()
	{
		$funnelData = new WCI_ChartData();
		$funnelData->wci_funnel_chart();
	}

	public static function wootracking_dashboard()
	{
		$dashboardFunnelData = new WCI_ChartData();
		$dashboardFunnelData->wci_dashboard_chart();
	}

	public static function fetch_pie_data(){
		$pieData = new WCI_ChartData();
		$pieData->wci_get_pie_data();
	}
	
	public static function wci_abandon_filter()
	{
		$funnelData = new WCI_ChartData();
                $funnelData->wci_abandon_filter();
	}
	
	public static function wci_abandon_filter_oneday()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_abandon_filter_oneday();
        }

	public static function wci_abandon_filter_oneweek()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_abandon_filter_oneweek();
        }

	public static function wci_abandon_filter_onemonth()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_abandon_filter_onemonth();
        }
	
	public static function wci_opportunity_filter_oneday()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_opportunity_filter_oneday();
        }

	public static function wci_opportunity_filter_oneweek()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_opportunity_filter_oneweek();
        }

	public static function wci_opportunity_filter_onemonth()
        {
                $funnelData = new WCI_ChartData();
                $funnelData->wci_opportunity_filter_onemonth();
        }

	public static function cart_submit()
	{
		require_once( 'wci_cart_submit.php' );
		die;
	}
}

add_action('wp_ajax_visitor_time_spent', array('WCI_AjaxActions', 'visitor_time_spent'));
add_action('wp_ajax_nopriv_visitor_time_spent', array('WCI_AjaxActions', 'visitor_time_spent'));
add_action('wp_ajax_button_click', array('WCI_AjaxActions', 'button_click'));
add_action('wp_ajax_nopriv_button_click', array('WCI_AjaxActions', 'button_click'));
add_action('wp_ajax_shop_cart', array('WCI_AjaxActions', 'shop_cart'));
add_action('wp_ajax_nopriv_shop_cart', array('WCI_AjaxActions', 'shop_cart'));
add_action('wp_ajax_cart', array('WCI_AjaxActions', 'cart'));
add_action('wp_ajax_nopriv_cart' , array('WCI_AjaxActions', 'cart'));
add_action('wp_ajax_wci_funnel_chart', array('WCI_AjaxActions', 'wci_funnel_chart'));
add_action('wp_ajax_wootracking_dashboard', array('WCI_AjaxActions', 'wootracking_dashboard'));
add_action('wp_ajax_fetch_pie_data', array('WCI_AjaxActions', 'fetch_pie_data'));
add_action('wp_ajax_update_sessionid',array('WCI_AjaxActions', 'update_sessionid'));
add_action('wp_ajax_nopriv_update_sessionid',array('WCI_AjaxActions', 'update_sessionid'));

add_action('wp_ajax_insert_sessionid_for_guest',array('WCI_AjaxActions', 'insert_sessionid_for_guest'));
add_action('wp_ajax_nopriv_insert_sessionid_for_guest',array('WCI_AjaxActions', 'insert_sessionid_for_guest'));

add_action('wp_ajax_cart_submit', array( 'WCI_AjaxActions' , 'cart_submit' ));
//NEW
add_action('wp_ajax_wci_abandon_filter',array('WCI_AjaxActions', 'wci_abandon_filter'));
add_action('wp_ajax_wci_abandon_filter_oneday',array('WCI_AjaxActions', 'wci_abandon_filter_oneday'));
add_action('wp_ajax_wci_abandon_filter_oneweek',array('WCI_AjaxActions', 'wci_abandon_filter_oneweek'));
add_action('wp_ajax_wci_abandon_filter_onemonth',array('WCI_AjaxActions', 'wci_abandon_filter_onemonth'));

//OPPORTUNITIES
add_action('wp_ajax_wci_opportunity_filter_oneday',array('WCI_AjaxActions', 'wci_opportunity_filter_oneday'));
add_action('wp_ajax_wci_opportunity_filter_oneweek',array('WCI_AjaxActions', 'wci_opportunity_filter_oneweek'));
add_action('wp_ajax_wci_opportunity_filter_onemonth',array('WCI_AjaxActions', 'wci_opportunity_filter_onemonth'));


class WCI_NoAjaxhookCalls {

	 public function wci_last_login_time($login) {
    		global $user_ID,$wpdb;
    		$user = get_user_by('login', $login);
    		$login_time = date("Y-m-d h:i:s");
		$logout_time = "0000-00-00 00-00-00";
    		$login_user_ip = $_SERVER['REMOTE_ADDR'];
    		update_user_meta($user->ID, 'loginTime', $login_time);
    		update_user_meta($user->ID, 'login_user_ip', $login_user_ip);
    		$usr_id = $user->ID;
    		$usr_name = $user->display_name;
    		$usr_email = $user->user_email;
    		$login = get_user_meta($usr_id,'loginTime');
    		$usr_registered_date = $user->user_registered;
    		$usr_role =  $user->roles[0];
		$status = "login";
		$wpdb->insert( 'wci_history' , array( 'user_id' => $usr_id , 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time , 'status' => $status ) );

		$user_list = get_users();

        foreach($user_list as $usr_key => $usr_value)
        {
                global $wpdb;
                $usr_id = $usr_value->data->ID;
                $usr_name = $usr_value->data->display_name;
                $usr_email = $usr_value->data->user_email;
                $login = get_user_meta($usr_id,'loginTime');
                $login_time = $login[0];
                $logout = get_user_meta($usr_id,'logoutTime');
                $logout_time = $logout[0];
                $usr_registered_date = $usr_value->data->user_registered;
                $usr_role =  $usr_value->roles[0];
                $existing_users = $wpdb->get_results($wpdb->prepare("select user_id from wci_user_profile_updated where user_id=%d" , $usr_id));
if(empty($existing_users)){
                $wpdb->insert( 'wci_user_profile_updated' , array( 'user_id' => $usr_id , 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time ) );
               }
else{
                $wpdb->update( 'wci_user_profile_updated' , array( 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time ) , array( 'user_id' => $usr_id ) );

    }
       }
		}

	 public function wci_time_on_logout($user_id) {

		// Clear cookies when user logout
		unset( $_COOKIE['wci_session_key']);
		setcookie('wci_session_key' , "" , time() - 3600 , '/' , $_SERVER['SERVER_NAME'] );

    		global $user_ID,$wpdb;
    		$user = get_user_by('id', $user_ID);
    		$logout_time = date("Y-m-d h:i:s");
		$login_time = "0000-00-00 00-00-00";
    		$logout_user_ip = $_SERVER['REMOTE_ADDR'];
    		update_user_meta($user->ID, 'logoutTime',  $logout_time);
    		update_user_meta($user->ID, 'logout_user_ip', $logout_user_ip);
    		$usr_id = $user->ID;
    		$usr_name = $user->display_name;
    		$usr_email = $user->user_email;
    		$usr_registered_date = $user->user_registered;
    		$usr_role =  $user->roles[0];
		$status = "logout";
		$wpdb->insert( 'wci_history' , array( 'user_id' => $usr_id , 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time , 'status' => $status) );
		
	$user_list = get_users();

        foreach($user_list as $usr_key => $usr_value)
        {
                global $wpdb;
                $usr_id = $usr_value->data->ID;
                $usr_name = $usr_value->data->display_name;
                $usr_email = $usr_value->data->user_email;
                $login = get_user_meta($usr_id,'loginTime');
                $login_time = $login[0];
                $logout = get_user_meta($usr_id,'logoutTime');
                $logout_time = $logout[0];
                $usr_registered_date = $usr_value->data->user_registered;
                $usr_role =  $usr_value->roles[0];
                $existing_users = $wpdb->get_results($wpdb->prepare("select user_id from wci_user_profile_updated where user_id=%d" , $usr_id));
if(empty($existing_users)){
                $wpdb->insert( 'wci_user_profile_updated' , array( 'user_id' => $usr_id , 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time ) );

               }
else{
                $wpdb->update( 'wci_user_profile_updated' , array( 'user_name' => $usr_name , 'email' => $usr_email , 'date' => $usr_registered_date , 'role' => $usr_role , 'login_time' => $login_time , 'logout_time' => $logout_time ) , array( 'user_id' => $usr_id ) );

    }
       }


				} // END Logout
}

add_action('wp_login', array( 'WCI_NoAjaxhookCalls' , 'wci_last_login_time'));
add_action('wp_logout', array( 'WCI_NoAjaxhookCalls' , 'wci_time_on_logout'));