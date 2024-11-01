<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

?>
<nav class='navbar navbar-default' style='background-color:#F1F1F1;width:100%;' role='navigation'>
   <div>
<?php
	global $wpdb;	
        $admin_url = 'admin.php';
	$latest_customer = $wpdb->get_results( "select session_key from wci_activity where length(session_key)<10 order by id desc limit 1" );

	if( !empty( $latest_customer ))
	{	
		$wci_cust = $latest_customer[0]->session_key;
		$customer_stats = add_query_arg( array( 'page' => 'customer_logs' , 'user_id' => $wci_cust ) , $admin_url );
	}
	else
	{
		$customer_stats = add_query_arg( array( 'page' => 'customer_logs') , $admin_url );
	}
        $dashboard = add_query_arg( array( 'page' => 'dashboard' ) , $admin_url );
        $user_payments = add_query_arg( array( 'page' => 'user_payments' ) , $admin_url );
        $reports = add_query_arg( array( 'page' => 'reports') , $admin_url );
?>
      <ul class='nav navbar-nav main_menu' style='width:99%;height:30px;'>

        <li class="<?php if( (sanitize_text_field($_REQUEST['page'])=='dashboard' ) ){ echo 'wci_activate'; }else{ echo 'wci_deactivate'; }?>">

        <a href='<?php echo esc_url( $dashboard ); ?>'><span id='settingstab'> <i style='padding-right:3px;' class="fa fa-tachometer fa-lg " aria-hidden="true"></i>
<?php echo esc_html__("Dashboard" , "woo-customer-insight" ); ?> </span></a>
        </li>
<!-- for third party plugin settings -->
        <li class="<?php if( sanitize_text_field($_REQUEST['page']) =='user_payments' ){ echo 'wci_activate'; }else { echo 'wci_deactivate'; }?>" >
                <a href='<?php echo esc_url( $user_payments ); ?>'><span id='shortcodetab'><i style='padding-right:3px;' class="fa fa-filter fa-lg" aria-hidden="true"></i>
 <?php echo esc_html__("Opportunities" , "woo-customer-insight" ) ; ?></span></a>
        </li>

         <li class="<?php if( (sanitize_text_field($_REQUEST['page'])=='customer_logs' ) ) { echo 'wci_activate'; }else{ echo 'wci_deactivate'; }?>">
                <a href='<?php echo esc_url( $customer_stats ) ?>'><span id='settingstab'> <i style='padding-right:3px;' class="fa fa-bar-chart fa-lg " aria-hidden="true"></i><?php echo esc_html__("Customer Stats" , "woo-customer-insight" ); ?> </span></a>
        </li>

        <li class="<?php if( sanitize_text_field($_REQUEST['page']) =='reports' ) { echo 'wci_activate'; }else{ echo 'wci_deactivate'; }?>">
                <a href='<?php echo esc_url( $reports ) ?>'><span id='settingstab'><i style='padding-right:3px;' class="fa fa-clipboard fa-lg " aria-hidden="true"></i><?php echo esc_html__('Reports' , "woo-customer-insight" ); ?> </span></a>
        </li>

      </ul>
   </div>
</nav>

