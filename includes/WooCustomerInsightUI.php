<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

class WooCustomerInsightUI {

	public static function wci_dashboard_widget() {
		wp_add_dashboard_widget(
				'wootracking_info',
				'Woo Tracking Report',
				array('WooCustomerInsightUI', 'my_dashboard_widget_display')
		);
	}

	public static function my_dashboard_widget_display() {
		$widget = "<label for='to_date'>Pick Date</label>&nbsp;&nbsp;<input type='text' id='dashboard_date' class='datepicker' value=".date('Y-m-d')."  placeholder='enter the to date' />
<input type='button' class=\"btn btn-primary\" id='dashbord_date_submit' value='Go' onClick=\"window.location.reload()\" />
<div id='d3-dashboard-chart'>
<div id='wootracking_dashboard_chart' style='width:450px;height:300px;padding:10px; auto;'></div></div>
";
		echo $widget;
	}

	public function WCI_init() {
                require_once( 'WCI_HomePage.php' );
        }
	
	public function WCI_ClickInfo() {
		require_once( 'WCI_ClickInfo.php' );
	}

	public function WCI_CustomerLogs() {
		require_once( 'WCI_CustomerLogs.php' );
	}

	public function WCI_StageUsers() {
		require_once( 'WCI_StageUsers.php' );
	}

}

add_action('wp_dashboard_setup', array('WooCustomerInsightUI', 'wci_dashboard_widget'));
