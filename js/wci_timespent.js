var jQuery = jQuery.noConflict();

jQuery(document).ready(function() { 
var start;
var d = new Date();
start = d.getTime();
var view_cart = jQuery("#cart_url").val();
var view_cart_url = 'a[href="'+view_cart+'"]';
var btn_name = "Page visit";
jQuery('.single_add_to_cart_button').click( function(){
	btn_name = "AddToCart";
	});
jQuery('.add_to_cart_button').click( function(){
	btn_name = "AddToCart";
	});
jQuery('.wc-forward').click( function(){   
	btn_name = "ViewCart";  
	});
jQuery('input[name="apply_coupon"]').click( function(){
	btn_name = "ApplyCoupon" ;
	});
jQuery('input[name="update_cart"]').click( function(){   
	btn_name = "UpdateCart";  
	});    
jQuery(".checkout-button").click( function(){
		btn_name = "Checkout";
		});

jQuery(".woocommerce-checkout").submit( function(){

		if(jQuery('#payment_method_cod').prop("checked")){
		btn_name = "CashOnDelivery";
		}
		if(jQuery('#payment_method_paypal').prop("checked")){

		btn_name = "ProceedToPaypal";
		}
		if(jQuery('#payment_method_bacs').prop("checked")){

		btn_name = "DirectBankTransfer";
		}
		if(jQuery('#payment_method_cheque').prop("checked")){
		btn_name = "ChequePayment";
		}

		});


  jQuery(window).on('beforeunload',function() {
	var d = new Date();
    	var end = d.getTime();
	var total = end - start;
	var timespt = total / 1000.0 ;
	var timespent = Math.round(timespt);
	var requri = document.getElementById('req').value;
	var http_host = document.getElementById('ip').value;
	var country = document.getElementById('country').value;
	var user_id = document.getElementById('user_name').value;
	var user_email = document.getElementById('user_email').value;
	var ajaxurl = document.getElementById('ajaxurl').value;
	var page_url = document.getElementById('page_url').value;
	var page_id = document.getElementById('prod_id').value;
	var page_title = document.getElementById('page_title').value;
	var is_user = document.getElementById('is_user').value;	
	var session_id = jQuery( "#session_id" ).val();
	var session_key = jQuery( "#session_key" ).val();
      jQuery.ajax({ 
        type: 'post',
        url: ajaxurl,
        data: {
	'action' : 'visitor_time_spent',
	'postdata' : timespent,
	'page_url' : page_url,
	'http_host': http_host,
	'country' : country,
	'user_id' : user_id,
	'user_email': user_email,
	'page_id' : page_id,
	'page_title' : page_title,
	'is_user' : is_user,
	'button_name' : btn_name,
	'session_id' : session_id,
	'session_key' : session_key
	},
	success:function(data)
	{
	},
	error:function(errorThrown){
	console.log(errorThrown);
	}
      });
    });
});

