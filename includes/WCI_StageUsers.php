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

<style>

html,body
{
background-color: #FFFFFF;
}

</style>


<?php
echo "<div id='opportunities_container' style='width:100%;background-color:#FFFFFF;font-family: Verdana,Geneva,sans-serif;'><br>";
echo "<div id='chart_title'>" .WCI_TITLE. " :: Opportunities </div>";
?>

<div class='wp-common-wciwrapper'>
<?php

$menu_obj = new SM_Woo_Customer_Insight();
$menu_obj->wci_menubar();
?>

<?php

echo '<table id="opportunity_filter" style="width:99%" ><tr>
<td class="col-md-10">
</td>
<td>
<a href="#" id="opportunity_oneday" style="text-align:right;">1D </a><span style="padding-left:10px;"> | </span> </td>
<td style="float:left;">
<a href="#" id="opportunity_oneweek"  style="text-align:right;">1W </a> <span style="padding-left:10px;"> | </span></td>
<td style="float:left;">
<a href="#" id="opportunity_onemonth" style="margin-left:15px;text-align:right;">1M </a> </td>
</tr></table>';
global $wpdb;

	
		echo "<div class='header_title'><h4>".esc_html('Unsuccessful Payments')." </h4></div>";
		echo "<div id='failed_payment'>";
//Failed Payments
$failed_payments = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_status=%s" , 'Failed' ),ARRAY_A );
if(empty( $failed_payments )  )
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone" style='width:100%;'>
		<th class='col-md-3'> Customer Name</th>
		<th class='col-md-2'> Email</th>
		<th class='col-md-2'> Order Id</th>
		<th class='col-md-3'> Products</th>
		<th class='col-md-2'> Date </th>
		</tr>
		</thead>
		<tr class="rowtwo"><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
		</table>

		<?php
}
else
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3;'> Customer Name </th>
		<th class='col-md-2'> Email</th>
		<th class='col-md-2'> Order Id</th>
		<th class='col-md-3'> Products</th>
		<th class='col-md-2'> Date</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach( $failed_payments as  $failed_payments_users )
		{
			$i++;
			if( $i % 2 == 0 )
			{
				echo "<tr class='rowone'>";
				echo "<td>". esc_html($failed_payments_users['user_name']) ."</td>";
				echo "<td>". sanitize_email($failed_payments_users['user_email']) ."</td>";
				echo "<td style='padding-left:40px;'>". esc_html($failed_payments_users['order_id']) ."</td>";
				echo "<td>". esc_html($failed_payments_users['products']) ."</td>";
				echo "<td>". esc_html($failed_payments_users['date']) ."</td>";
				echo "</tr>";	
			}
			else
			{
				echo "<tr class='rowtwo'>";
                        	echo "<td>". esc_html($failed_payments_users['user_name']) ."</td>";
                        	echo "<td>". sanitize_email($failed_payments_users['user_email']) ."</td>";
                        	echo "<td style='padding-left:40px;'>". esc_html($failed_payments_users['order_id']) ."</td>";
                        	echo "<td>". esc_html($failed_payments_users['products']) ."</td>";
                        	echo "<td>". esc_html($failed_payments_users['date']) ."</td>";
                        	echo "</tr>";

			}
		}
	?>
		</table>
		<?php
}

echo "</div>";
// END Failed Payments

//ABUNDANT 

$get_session_ids = array();
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where is_cart='1'" , ARRAY_A);

		echo "<div class='header_title'><h4>".esc_html('Abandon Cart')." </h4></div>";
		echo "<div id='abundant_cart'>";
if(empty( $get_session_ids )  )
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3;'> Customer Name</th>
		<th class='col-md-3;'> Country</th>
		<th class='col-md-3;'> Product</th>
		<th class='col-md-3;'> Date</th>
		</tr>
		</thead>
		<tr class="rowtwo"><td></td><td style="text-align:center;">--No Records Found--</td><td></td><td></td></tr>
		</table>
		<?php
}
else
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3;'> Customer Name </th>
		<th class='col-md-3;'> Country</th>
		<th class='col-md-3;'> Product</th>
		<th class='col-md-3;'> Date</th>
		</tr>
		</thead>
		<?php

		foreach( $get_session_ids as $sess_key => $sess_val)
		{
        		$Checkout_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
        		$Payment_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_payment=%d" , $sess_val['session_id'] , '1' ));
        		if( empty( $Payment_users ))
        		{		
                		if( !empty( $Checkout_users ))
                		{
					$i = 0;
                        		foreach( $Checkout_users as $Checkout_key => $Checkout_val )
                        		{
					$i++;
					if( $i %  2 == 0 )
					{
                        			echo "<tr class='rowone'>";
	                        		echo "<td class='col-md-3'>". esc_html($Checkout_val->user_name) ."</td>";
        	                		echo "<td class='col-md-3'>". esc_html($Checkout_val->country) ."</td>";
                	        		echo "<td class='col-md-3'>". esc_html($Checkout_val->product_data) ."</td>";
                       				echo "<td class='col-md-3'>". esc_html($Checkout_val->date) ."</td>";
                        			echo "</tr>";
					}
					else
					{
						echo "<tr class='rowtwo'>";
                                                echo "<td class='col-md-3'>". esc_html($Checkout_val->user_name) ."</td>";
                                                echo "<td class='col-md-3'>". esc_html($Checkout_val->country) ."</td>";
                                                echo "<td class='col-md-3'>". esc_html($Checkout_val->product_data) ."</td>";
                                                echo "<td class='col-md-3'>". esc_html($Checkout_val->date) ."</td>";
                                                echo "</tr>";

					}
                        		}
                		}
				        }
		}		
	?>
		</table>
		<?php
}
echo "</div>";
//END Checkout and not completed payment users

//ADDTOCart Users


// New code Add To Cart

		echo "<div class='header_title'><h4>". esc_html('Add To Cart') ." </h4></div>";
echo '<div id="Addtocart_users">';
$get_session_ids = $wpdb->get_results( "select distinct session_id from wci_user_session where is_cart='1'" , ARRAY_A);
if(empty( $get_session_ids )  )
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone" style="width:100%;">
		<th class='col-md-3;'> Customer Name</th>
		<th class='col-md-3;'> Country </th>
		<th class='col-md-3;'> Product </th>
		<th class='col-md-3;'> Date </th>
		</tr>
		</thead>
		<tr class="rowtwo"><td></td><td style="text-align:center;">--No Records Found--</td><td></td><td></td></tr>
		</table>
		<?php
}
else
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3;'> Customer Name </th>
		<th class='col-md-3;'> Country </th>
		<th class='col-md-3;'> Product </th>
		<th class='col-md-3;'> Date </th>

		</tr>
		</thead>
		<?php

//New code
		foreach( $get_session_ids as $sess_key => $sess_val)
		{
        		$Cart_users = $wpdb->get_results( $wpdb->prepare( "select user_name,country,product_data,date from wci_user_session where session_id=%d and is_cart=%d" , $sess_val['session_id'] , '1') );
        		$Checkout_users = $wpdb->get_results( $wpdb->prepare( "select distinct user_name from wci_user_session where session_id=%d and is_checkout=%d" , $sess_val['session_id'] , '1' ));
        		if( empty( $Checkout_users ))
        		{
                		if( !empty( $Cart_users ))
                		{
				$i = 0;
                        		foreach( $Cart_users as $Cart_key => $Cart_val )
                        		{
						$i++;
						if( $i % 2 == 0 )
						{
                        				echo "<tr class='rowone'>";
                        				echo "<td class='col-md-3'>". esc_html($Cart_val->user_name) ."</td>";
                        				echo "<td class='col-md-3'>". esc_html($Cart_val->country) ."</td>";
                        				echo "<td class='col-md-3'>". esc_html($Cart_val->product_data) ."</td>";
                        				echo "<td class='col-md-3'>". esc_html($Cart_val->date) ."</td>";
                        				echo "</tr>";
						}
						else
						{
							echo "<tr class='rowtwo'>";
                                                	echo "<td class='col-md-3'>". esc_html($Cart_val->user_name) ."</td>";
                                                	echo "<td class='col-md-3'>". esc_html($Cart_val->country) ."</td>";
                                                	echo "<td class='col-md-3'>". esc_html($Cart_val->product_data) ."</td>";
                                                	echo "<td class='col-md-3'>". esc_html($Cart_val->date) ."</td>";
                                                	echo "</tr>";
						}
                        		}
                		}
        		}
		}	

	?>
		</table>
		<?php
}
echo "</div>";
//Successful_payments

$success_payments = $wpdb->get_results( $wpdb->prepare("select * from wci_successful_purchases where order_status=%s" , 'Completed' ),ARRAY_A );
	
		echo "<div class='header_title'><h4>Successful Payments </h4></div>";
		echo "<div id='success_payment'>";
if(empty( $success_payments )  )
{
	?>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3'> Customer Name</th>
		<th class='col-md-2'> Email</th>
		<th class='col-md-2'> Order Id</th>
		<th class='col-md-3'> Products</th>
		<th class='col-md-2'> Date </th>
		</tr>
		</thead>
		<tr class="rowtwo"><td></td><td></td><td>--No Records Found--</td><td></td><td></td></tr>
		</table>

		<?php
}
else
{
	?>
		<br>
		<table class="table table-hover" style='width:99%;border:1px solid #E5E5E5;'>
		<thead>
		<tr class="thone">
		<th class='col-md-3'> Customer Name </th>
		<th class='col-md-2'> Email</th>
		<th class='col-md-2'> Order Id</th>
		<th class='col-md-3'> Products</th>
		<th class='col-md-2'> Date</th>
		</tr>
		</thead>
		<?php
		$i = 0;
		foreach( $success_payments as  $success_payments_users )
		{
		$i++;
			if( $i % 2 == 0 )
			{
				echo "<tr class='rowone'>";
				echo "<td>". esc_html($success_payments_users['user_name']) ."</td>";
				echo "<td>". sanitize_email($success_payments_users['user_email']) ."</td>";
				echo "<td style='padding-left:40px;'>". esc_html($success_payments_users['order_id']) ."</td>";
				echo "<td>". esc_html($success_payments_users['products']) ."</td>";
				echo "<td>". esc_html($success_payments_users['date']) ."</td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr class='rowtwo'>";
                        	echo "<td>". esc_html($success_payments_users['user_name']) ."</td>";
                        	echo "<td>". sanitize_email($success_payments_users['user_email']) ."</td>";
                        	echo "<td style='padding-left:40px;'>". esc_html($success_payments_users['order_id']) ."</td>";
                        	echo "<td>". esc_html($success_payments_users['products']) ."</td>";
                        	echo "<td>". esc_html($success_payments_users['date']) ."</td>";
                        	echo "</tr>";
			}
		}
	?>
		</table>
		<?php
}
echo "</div>"; // success Payment
echo "</div>";
?>

