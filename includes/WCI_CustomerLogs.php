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
select {
width:230px;
}
label
{ font-size:18px;}
html,body {
background-color: #FFFFFF;
}

</style>
<?php
//QUERY STRING USER
global $wpdb;
$users_list = array();
$blogusers = get_users( );
foreach ( $blogusers as $user ) {
	$ID = $user->data->ID;
	$users_list[$ID] =  $user->data->display_name ;
}
	if( !empty( $users_list) )
	{
		array_push( $users_list , "Guest" );
	}


	global $wpdb;
	echo "<div id='stats_container' style='width:99%;background-color:#FFFFFF;'>"; //Whole container
	echo "<br><div id='chart_title'>" .WCI_TITLE. " :: Customer Stats </div>";
	// MENU BAR
	echo "<div class='wp-common-wciwrapper'>";
	$menu_obj = new SM_Woo_Customer_Insight();
	$menu_obj->wci_menubar();
	echo "</div>";

	echo "<label for='selected_user'> ". esc_html('Select Customer Name :'). " </label>&nbsp;&nbsp;";
	echo "<select id='selected_user'>";
	foreach( $users_list as $u_key => $u_value ){
		echo '<option value="'.$u_key.'">'.$u_value.'</option>';
	}
	echo "</select>&nbsp;&nbsp; "; 
	echo "<input type='button' id='fetch_user_data' class='btn btn-primary' value='Go'>";
	echo "<br><br>";
	
//USER PROFILE 
echo "<div id='wci_empty_data_msg'></div>";
echo "<div id='user_profile_info' style='width:98%;height:30em;margin-bottom:5px;border:1px solid #D3D3D3;border-radius:4px;'>";//USER PROFILE DESIGN
	echo "<div id='user_profile_stats'> </div>";
	echo "<div id='wootracking_pie'></div>"; 

echo "</div>"; // END
	
	echo "<div id='selected_user_table' style='width:100%;margin-top:5%;float:left;font-family: Verdana,Geneva,sans-serif;'></div>";
	echo "<div id='user_history'>";
	echo "<br><label id='title'> Overall Customer's History :</label>";
	echo "<br><br>";
	$html = '';
                        $info = array();
                        $info = $wpdb->get_results("select * from wci_activity order by id desc limit 15");
			$html .= '<table class="table table-hover" style="width:99%;border:1px solid #E5E5E5;">';
			if( empty( $info ) )
			{
			$html .=   '<tr class="thone">
                                   <th class="col-md-2">Name</th>
                                   <th class="col-md-2">Location</th>
                                   <th class="col-md-2">Page Title</th>
                                   <th class="col-md-2">Event</th>
                                   <th class="col-md-2">Time Spent( M&S ) </th>
				   <th class="col-md-2"> date </th>
                                </tr>
				<tr class="rowtwo"> <td></td><td></td><td>-- No Records Found --</td><td></td><td></td><td></td> </tr>';				
			}
                        else
			{
                        $html .=   '<tr class="thone">
                                   <th class="col-md-2">Name</th>
                                   <th class="col-md-2">Location</th>
                                   <th class="col-md-2">Page Title</th>
                                   <th class="col-md-2">Event</th>
                                   <th class="col-md-2">Time Spent( M&S ) </th>
				   <th class="col-md-2"> date </th>
                                </tr>';

                        foreach($info as $info_key => $info_value) {
				
				$hrs = floor($info_value->spent_time / 3600);
                        	$hours = sprintf("%02d", $hrs);
                        	$mins = floor(($info_value->spent_time / 60) % 60);
                        	$minutes = sprintf("%02d", $mins);
                        	$secs = $info_value->spent_time % 60;
                        	$seconds = sprintf("%02d", $secs);
	
				$wci_time_spent = $hours.":".$minutes.":".$seconds;

				if( $info_key % 2 == 0 )
				{
                                $html .= '<tr class="rowtwo">';
                                $html .= '<td>'.esc_html($info_value->user_id) .'</td>';
                                $html .= '<td>'.esc_html($info_value->country) .'</td>';
                                $html .= '<td>'.esc_html($info_value->page_title) .'</td>';
                                $html .= '<td>' .esc_html($info_value->clicked_button). '</td>';
                                $html .= '<td style="padding-left:75px;">' .esc_html($wci_time_spent). '</td>';
				$html .= '<td>' .esc_html($info_value->date_time). '</td>';
                                $html .=  '</tr>';
				}
				else
				{
				$html .= '<tr class="rowone">';
                                $html .= '<td>'.esc_html($info_value->user_id) .'</td>';
                                $html .= '<td>'.esc_html($info_value->country) .'</td>';
                                $html .=  '<td>'.esc_html($info_value->page_title) .'</td>';
                                $html .= '<td>' .esc_html($info_value->clicked_button). '</td>';
                                $html .= '<td style="padding-left:75px;">' .esc_html($wci_time_spent). '</td>';
				$html .= '<td>' .esc_html($info_value->date_time). '</td>';
                                $html .=  '</tr>';
				}
                        }
			}
                        $html .=  '</table>';
			echo $html;
	echo "</div>";

	
	echo "<br></div>"; // stats container END


// DISPLAY SELECTED USER DETAILS

if( isset( $_REQUEST['user_id'] ) )
{
	$query_user = $_REQUEST['user_id'];
}
if( isset($query_user) )
{
	$get_user_name = $wpdb->get_results( $wpdb->prepare( "select display_name from {$wpdb->prefix}users where ID=%d" , $query_user ) ); 
	$query_user_name = $get_user_name[0]->display_name;
?>
	<script type="text/javascript">
        jQuery( document ).ready( function() {
        jQuery( "#selected_user" ).val( "<?php echo $query_user;?>" );
        jQuery("#fetch_user_data").trigger( "click" );
        });
        </script>
	
<?php
}