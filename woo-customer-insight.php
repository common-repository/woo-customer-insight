<?php
/**
 * Woo Customer Insight.
 *
 * Woo Customer Insight plugin file.
 *
 * @package   Smackcoders\WCI
 * @copyright Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Woo Customer Insight
 * Version:     1.0.1
 * Plugin URI:  https://www.smackcoders.com/wp-leads-builder-any-crm-pro.html
 * Description: Track your Customer activities ( Visits and Events ) and enhance Customer Flow. Opportunity Funnel helps you identify Customer drop offs.
 * Author:      Smackcoders
 * Author URI:  https://www.smackcoders.com/wordpress.html
 * Text Domain: woo-customer-insight
 * Domain Path: /languages
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SM_Woo_Customer_Insight {

	public $current_url = ''; 

	public function __construct() {
		$this->wci_includes();
		$this->wci_plugin_actions();
		$this->current_url = $_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
	}

	public function wci_define_constants() {
		define('WCI_SLUG', 'woo-customer-insight');
		define('WCI_SLUG_NAME', 'woo_customer_insight');
		define('WCI_TITLE', 'Woo Customer Insight');
		define('WCI_ICON' , WP_PLUGIN_URL . '/'. WCI_SLUG .'/images/eye2Icons24.png' );
	}
	
	public function wci_menubar()
	{
		require_once( 'includes/WCI_Menu.php' );
	}	

	public function wci_includes() {
		require_once('includes/WooCustomerInsightUI.php');
		require_once('config/WooCustomerInsightSchema.php');
		require_once('includes/WooCustomerInsightHelper.php');
		require_once('includes/Countries.php');
		require_once('includes/WCI_ChartData.php');
		require_once('includes/WCI_AjaxActions.php');
	}

	public function wci_plugin_actions() {
		register_activation_hook(__FILE__, array('WooCustomerInsightSchema','generate_tables'));
		add_action('init', array( $this , 'wci_define_constants' ) );
		add_action('admin_menu' , array($this, 'wci_admin_menu' )) ;
		add_action('admin_enqueue_scripts', array( $this, 'wci_admin_styles' ) );
	}

	public function wci_admin_menu(){
		global $submenu;
		$helperObj = new WooCustomerInsightUI();
		add_menu_page( WCI_SLUG, 'Woo Customer Insight','manage_options',WCI_SLUG_NAME,array( $helperObj, 'WCI_init' ), WCI_ICON);	
		add_submenu_page( WCI_SLUG_NAME, 'Dashboard' , 'Dashboard' , 'manage_options' , 'dashboard' , array( $helperObj , 'WCI_init'));
		add_submenu_page( WCI_SLUG_NAME, 'Opportunities' , 'Opportunities' , 'manage_options' , 'user_payments' , array( $helperObj , 'WCI_StageUsers'));
		
		add_submenu_page( WCI_SLUG_NAME, 'Customer Stats', 'Customer Stats', 'manage_options','customer_logs', array( $helperObj , 'WCI_CustomerLogs'));
		add_submenu_page( WCI_SLUG_NAME, 'Reports', 'Reports', 'manage_options', 'reports', array( $helperObj , 'WCI_ClickInfo'));
		unset( $submenu[WCI_SLUG_NAME][0] );
	}

	public function wci_admin_styles() {
	$site_url = site_url();
        $page_scheme = parse_url($site_url,PHP_URL_SCHEME);
	$dashboard_url = $page_scheme."://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
	if( (isset($_REQUEST['page'] ) && ($_REQUEST['page'] == "dashboard" || $_REQUEST['page'] == "user_payments" || $_REQUEST['page'] == "customer_logs" || $_REQUEST['page'] == "reports")) || !is_admin() || isset( $dashboard_url ) )	
	{
		//Js 
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('wci_select2.min.js', plugins_url('js/wootracking_select2.min.js',__FILE__));
		wp_enqueue_script('wci_chart.js', plugins_url('js/wootracking-chart.js',__FILE__));
		wp_enqueue_script('wci_pie-chart.js', plugins_url('js/wootracking-pie-chart.js',__FILE__));
		wp_enqueue_script('wci_dashboard.js', plugins_url('js/wootracking-dashboard.js',__FILE__));
		wp_enqueue_script('wci_d3.min.js', plugins_url('js/d3.min.js',__FILE__));
		wp_enqueue_script('wci_funnel.js', plugins_url('js/d3-funnel.js',__FILE__));
		wp_enqueue_script('wci_morris.min.js', plugins_url('js/morris.min.js',__FILE__));
		wp_enqueue_script('wci_raphael-min.js', plugins_url('js/raphael-min.js',__FILE__));

		//CSS
		wp_enqueue_style('wci_morris.css', plugins_url('css/morris.css',__FILE__));
		wp_enqueue_style('wci_jquery-ui.css', plugins_url('css/wootracking_jquery-ui.css',__FILE__));
		wp_enqueue_style('wci_select2.min.css', plugins_url('css/wootracking_select2.min.css',__FILE__));
		wp_enqueue_style('wci_product_view.css', plugins_url('css/wootracking_product_view.css',__FILE__));
		wp_enqueue_style('wci_font-awesome.min.css', plugins_url('css/font-awesome.min.css',__FILE__));
	}
	if( (isset($_REQUEST['page'] ) && ($_REQUEST['page'] == "dashboard" || $_REQUEST['page'] == "user_payments" || $_REQUEST['page'] == "customer_logs" || $_REQUEST['page'] == "reports")) )
	{
		wp_enqueue_style('wci_bootstrap.min.css', plugins_url('css/wootracking_bootstrap.min.css',__FILE__));

	}
}

	public function wci_tracking_history() {
		$helperObj = new WooCustomerInsightHelper();
		$helperObj->trackingHistory();
	}
}

if( !is_admin() )
{
	add_action('wp_footer', array('SM_Woo_Customer_Insight', 'wci_tracking_history'));
}

add_action( 'woocommerce_order_status_failed' , array( 'WooCustomerInsightHelper' , 'WCI_order_status_failed'));
add_action( 'woocommerce_order_status_completed' , array( 'WooCustomerInsightHelper' , 'WCI_order_status_completed'));

new SM_Woo_Customer_Insight();

add_action( 'woocommerce_thankyou' , 'thankyou' );
function thankyou( $order_id ) {
		global $wpdb;
		$current_user = wp_get_current_user();
                if( is_user_logged_in() ) {
                        $session_key = $current_user->ID;
		}
		else
		{	
			$WC_session_obj = new WC_Session_Handler();
                	$session_key = $WC_session_obj->get_customer_id();
		}
			$user_session = $wpdb->get_results( $wpdb->prepare( "select * from {$wpdb->prefix}woocommerce_sessions where session_key=%s" , $session_key ) );
                        $session_id = $user_session[0]->session_id;
			if( !empty( $session_id ))
			{
				$check_order_already_present = $wpdb->get_results( $wpdb->prepare( "select order_id from wci_user_session where session_id=%d and order_id !=%d and is_payment=%d " , $session_id , '0' , '1' ) );
				// Check any Order ID already present in order_id column , UPDATE 1st order for customer
				if( empty( $check_order_already_present ))
				{
					$wpdb->update( 'wci_user_session' , array( 'order_id' => $order_id ) , array( 'session_id' => $session_id  ));
				}
				else
				{
					$get_id = $wpdb->get_results( "select id from wci_user_session where session_id='$session_id' and is_payment ='1' and order_id=0" );
					$id = $get_id[0]->id; 
					if( !empty( $id ))
					{
						$wpdb->update( 'wci_user_session' , array( 'order_id' => $order_id ) , array( 'session_id' => $session_id , 'order_id' => '0' ));
					}
				}
			}
	

		$order = new WC_Order( $order_id );
		$total_price = $order->get_total();
                $coupon = $order->get_used_coupons();
		if( !empty($coupon) )
		{
			$coupon_code = $coupon[0];
			$coupon_details = new WC_Coupon( $coupon_code );
			$coupon_amount = $coupon_details->coupon_amount;
			$coupon_discount = $coupon_details->discount_type;
		}
		else
		{
			$coupon_code = "None";
			$coupon_amount = "--";
			$coupon_discount = "--";
		}
		$order_status = $order->post->post_status;		
                $items = $order->get_items();
                foreach( $items as $item_vals )
                {
                        $guest_prods[] = $item_vals['name'];
                }

                foreach( $guest_prods as $vall )
                {
                        $pro_name .= $vall.",";
                }
                $pro_name = rtrim( $pro_name , ",");
                $order_email = $order->billing_email;
                $customer = get_userdata($order->customer_user);
                if( !empty( $customer ) )
                {
                        $order_cust_name =  $customer->display_name;
                }
                else
                {
                        $order_cust_name = "Guest" ;
                }
                $order_date = $order->order_date;
		$wpdb->insert( 'wci_successful_purchases' , array( 'user_name' => $order_cust_name , 'user_email' => $order_email , 'order_id' => $order_id , 'order_status' => $order_status , 'date' => $order_date , 'products' => $pro_name , 'coupon_code' => $coupon_code , 'coupon_amount' => $coupon_amount , 'total_price' => $total_price , 'discount_type' => $coupon_discount ) );
}