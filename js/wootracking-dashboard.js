var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {

	display_dashboard_chart();

});

function display_dashboard_chart()
{
var  dash_date = jQuery('#dashboard_date').val();
jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'wootracking_dashboard',
	    'date'  : dash_date
        },

        cache: false,
        success: function (response) {  
	var result = JSON.parse(response);   
            if (result.info.total_visitors == 0 && result.info.AddToCart == 0 && result.info.Checkout == 0  && result.info.SuccessfulPurchases == 0) {     
		jQuery('#wootracking_dashboard_chart').html("<div class='alert alert-info' style='text-align:left;color:#286992;padding-left:20px;'><h2 style='font-family:times new roman' >" + 'Welcome to Woo Customer Insight'+ "</h2><h1 style='font-family:times new roman'>About the Plugin...</h1><br><ul style='list-style-type:square'><li style='font-size:14px;'>Tracking User Info(Login,Abandon orders,purchased...etc)</li><li style='font-size:14px;'>Page Tracking</li><li style='font-size:14px;'>Events Tracking</li></ul></div>");
            }
            else {  

	var data = [
                ['Total Visitors',   result.info.total_visitors , '#2F91B8' , '#FFFFFF'],
                ['AddToCart', result.info.AddToCart , '#3E3E3E', '#FFFFFF'],
                ['Checkout',    result.info.Checkout, '#9B9B9B' , '#FFFFFF'],
                ['SuccessfulPurchases',  result.info.SuccessfulPurchases , '#95A926' , '#FFFFFF'],
            ];

        width = jQuery('#wootracking_d3_funnel').width();
        var options = {
        chart: {
            width: width - 650,
            height: 280,
            bottomWidth: 1 / 2,
            animate : 300 ,
        },
        block: {
            dynamicHeight: true,
            hoverEffects: true,
        fill : {
                type : "gradient" ,
                }
        },
        label : {
                fontSize : "15px",
        },
    };

    var funnel = new D3Funnel("#wootracking_dashboard_chart");
    funnel.draw(data, options);
}

}
});

}














