var jQuery = jQuery.noConflict();
jQuery(document).ready(function () {
	jQuery('#choose_event').select2();
	jQuery('#filter_value').select2();
	jQuery('#selected_user').select2();
	jQuery('#selected_order_status').select2();
	jQuery( "#wootracking_pie" ).hide();
	jQuery("#user_profile_info").hide();	
	jQuery( "#user_profile_info" ).height("3");
	var selected_person;
	var selected_user_id;
	jQuery("#fetch_user_data").click(function(){
		 jQuery( "#wootracking_pie" ).empty();
		 jQuery( "#user_profile_stats").empty();
		 jQuery( "#user_history" ).hide();
		 jQuery( "#user_profile_info" ).show();
		 jQuery( "#wci_empty_data_msg" ).hide();
		 selected_user_id = jQuery("#selected_user").val();
		 selected_person = jQuery( "#selected_user option:selected" ).text();
		 jQuery("#default-info").hide();

jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'fetch_pie_data',
	     'selected_customer' : selected_person,
	     'selected_user_id' : selected_user_id
        },

        cache: false,
        success: function (response) {  
	var result = JSON.parse(response);    
	if(response == 'null'){
	jQuery("#user_profile_info" ).hide();
	jQuery("#wci_empty_data_msg" ).show();
	jQuery('#wci_empty_data_msg').html("<div style='text-align:center:width:310px;height:400px;padding-top:150px;max-width:620px;margin-left:200px;'><p class='alert alert-danger' style='font-size:20px;font-family:sans-serif;'><strong>ALERT : </strong><br><br>This user is not visited yet</p></div>");
	jQuery('#selected_user_table').html("");

}
	else{  
	jQuery( "#user_profile_info" ).height('30em');
	document.getElementById("user_profile_stats").innerHTML=result.stats;
	document.getElementById("selected_user_table").innerHTML=result.table;
	jQuery( "#user_history" ).hide();
	jQuery( "#wootracking_pie" ).show();
	new Morris.Bar({
//  barGap:4,
  barSizeRatio:0.50,
  element: 'wootracking_pie',
  data: [
    { name: 'VisitedPages', value: result.chart.visited_pages },
    { name: 'AddToCart', value: result.chart.AddToCart },
    { name: 'ApplyCoupon', value: result.chart.ApplyCoupon },
    { name: 'Checkout', value: result.chart.Checkout },
    { name: 'SuccessPurchases', value: result.chart.Success_purchase},
  ],
  xkey: 'name',
  ykeys: ['value'],
  labels: ['Value'],
  parseTime: false,
  xLabelMargin: 10,
  xLabelAngle: 20,
//  pointSize: 2,
});	


	}
      }
    });

   });
});
