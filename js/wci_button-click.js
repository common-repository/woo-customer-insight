var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {
      
	jQuery('.cart').submit(function() {
		var action_name = "form_submit";
		var ajaxurl = jQuery("#ajaxurl").val();
		jQuery.ajax({
			url : ajaxurl,
			type: 'POST',
			data: {
				'action': 'cart_submit',
				'action_name' : action_name,
			      },
			success:function(data)
			{
				add_to_cart_after_submit();
			},
			error:function( errorThrown )
			{
				add_to_cart_after_submit();
			}
		}); 

	});
 
	function add_to_cart_after_submit() 
	{
                var count = 1;
                var btn_name = "AddToCart";
                var requri = document.getElementById('req').value;
                var http_host = document.getElementById('ip').value;
                var country = document.getElementById('country').value;
                var user_id = document.getElementById('user_name').value;
		var user_email = document.getElementById('user_email').value;
                var ajaxurl = document.getElementById('ajaxurl').value;
                var page_url = document.getElementById('page_url').value;
		var page_title = document.getElementById('page_title').value;
                var prod_id = document.getElementById('prod_id').value;
                var product = document.getElementById('product').value;
		var session_id = jQuery( "#session_id" ).val();
		var session_key = jQuery( "#session_key" ).val();
		var session_value = jQuery( "#session_val" ).val();
		
		var data_array = {
				'action' : 'cart',
                                'count' : count,
                                'btn_name' : btn_name,
                                'page_url' : page_url,
                                'page_title': page_title,
                                'http_host': http_host,
                                'country' : country,
                                'user_id' : user_id,
                                'user_email': user_email,
                                'prod_id' : prod_id,
                                'product' : product,
                                'session_id': session_id,
                                'session_key': session_key,
                                'session_value': session_value,
		};
                jQuery.ajax({
                        url: ajaxurl,
                        type: 'post',
			async: false,
                        data: data_array,
                        success:function(data)
                        {
				var session_arr = JSON.parse(data);
				var id = session_arr.id;
				if( id == 0 )
				{
					var sess_key = session_arr.sess_key;
					update_session_id(sess_key); // update session_id for user
				}
				if( id == 11 )
				{
					insert_session_for_guest( session_arr ); // Insert entry for guest
				}
			},
                        error:function( errorThrown ) {
				console.log( errorThrown );
			}
                               
                        
                });
}


	function update_session_id(sess_key)
	{
		var ajaxurl = document.getElementById('ajaxurl').value;
		jQuery.ajax({
			type: 'post',
			url : ajaxurl,
			data: {
				'action' : 'update_sessionid',
				'sess_key': sess_key
			      },
			success:function(data)
                        {
                        },
                        error:function(errorThrown){
                                console.log(errorThrown);
                        }

		});

	}

        jQuery('.add_to_cart_button').click( function()
        {
                var count = 1 ;
                var prod_id = jQuery(this).data('product_id');
                var btn_name = "AddToCart";
                var http_host = document.getElementById('ip').value;
                var country = document.getElementById('country').value;
                var user_id = document.getElementById('user_name').value;
		var user_email = document.getElementById('user_email').value;
                var ajaxurl = document.getElementById('ajaxurl').value;
                var page_url = document.getElementById('page_url').value;
		var page_title = document.getElementById('page_title').value;
		var session_id = jQuery( "#session_id" ).val();
                var session_key = jQuery( "#session_key" ).val();
                var session_value = jQuery( "#session_val" ).val();
                jQuery.ajax({
                        type: 'post',
                        url: ajaxurl,
                        data: {
                                'action' : 'shop_cart',
                                'count' : count,
                                'btn_name' : btn_name,
                                'page_url' : page_url,
				'page_title': page_title,
                                'http_host': http_host,
                                'country' : country,
                                'user_id' : user_id,
				'user_email':user_email,
                                'prod_id' : prod_id,
				'session_id': session_id,
                                'session_key': session_key,
                                'session_value': session_value,
                        },
                        success:function(data)
                        {
				var session_arr = JSON.parse(data);
				var id = session_arr.id;
				if( id == 0 )
				{
					var sess_key = session_arr.sess_key;
					console.log( "session id is empty");
					update_session_id(sess_key);
				}
				if( id == 11 )
				{
                                        insert_session_for_guest( session_arr );
				}
                        },
			
                        error:function(errorThrown){
                                console.log(errorThrown);
                        }
                });
        });


	function insert_session_for_guest( session_arr )
	{
		var ajaxurl = document.getElementById('ajaxurl').value;
                jQuery.ajax({
                        type: 'post',
                        url : ajaxurl,
                        data: {
                                'action' : 'insert_sessionid_for_guest',
                                'count' : session_arr.count,
                                'btn_name' : session_arr.btn_name,
                                'page_url' : session_arr.page_url,
                                'http_host': session_arr.http_host,
                                'country' : session_arr.country,
                                'user_id' : session_arr.wpuser_id,
                                'user_email': session_arr.user_email,
                                'prod_id' : session_arr.prodid,
				'product' : session_arr.product,
				'date' : session_arr.date,
				'date_without_time' : session_arr.date_without_time
                              },
                        success:function(data)
                        {				
                        },
                        error:function(errorThrown){
                                console.log(errorThrown);
                        }

                });

	}

        jQuery('input[name="apply_coupon"]').click( function()
            {
                    var count = 1 ;
                    var btn_name = "ApplyCoupon" ;
                    var requri = document.getElementById('req').value;
                    var http_host = document.getElementById('ip').value;
                    var country = document.getElementById('country').value;
                    var user_id = document.getElementById('user_name').value;
		    var user_email = document.getElementById('user_email').value;
                    var ajaxurl = document.getElementById('ajaxurl').value;
                    var page_url = document.getElementById('page_url').value;
		    var page_title = document.getElementById('page_title').value;
                    var prod_id = document.getElementById('prod_id').value;
                    var product = document.getElementById('cart_items').value;
		    var session_id = jQuery( "#session_id" ).val();
                    var session_key = jQuery( "#session_key" ).val();
                    var session_value = jQuery( "#session_val" ).val();

                    jQuery.ajax({
                            type: 'post',
                            url: ajaxurl,
                            data: {
                                    'action' : 'button_click',
                                    'count' : count,
                                    'btn_name' : btn_name,
                                    'page_url' : page_url,
				    'page_title': page_title,
                                    'http_host': http_host,
                                    'country' : country,
                                    'user_id' : user_id,
				    'user_email': user_email,
                                    'prod_id' : prod_id,
                                    'product' : product,
				    'session_id': session_id,
                                    'session_key': session_key,
                                    'session_value': session_value,
                            },
                            success:function(data)
                            {
                            },
                            error:function(errorThrown){
                                    console.log(errorThrown);
                            }
                    });

            }
        );


        jQuery('input[name="update_cart"]').click( function()
            {

                    var count = 1;
                    var btn_name = "UpdateCart";
                    var requri = document.getElementById('req').value;
                    var http_host = document.getElementById('ip').value;
                    var country = document.getElementById('country').value;
                    var user_id = document.getElementById('user_name').value;
		    var user_email = document.getElementById('user_email').value;
                    var ajaxurl = document.getElementById('ajaxurl').value;
                    var page_url = document.getElementById('page_url').value;
		    var page_title = document.getElementById('page_title').value;
                    var prod_id = document.getElementById('prod_id').value;
                    var product = document.getElementById('cart_items').value;
		    var session_id = jQuery( "#session_id" ).val();
                    var session_key = jQuery( "#session_key" ).val();
                    var session_value = jQuery( "#session_val" ).val();


                    jQuery.ajax({
                            type: 'post',
                            url: ajaxurl,
                            data: {
                                    'action' : 'button_click',
                                    'count' : count,
                                    'btn_name' : btn_name,
                                    'page_url' : page_url,
				    'page_title': page_title,
                                    'http_host': http_host,
                                    'country' : country,
                                    'user_id' : user_id,
				    'user_email': user_email,
                                    'prod_id' : prod_id,
                                    'product' : product,
				    'session_id': session_id,
                                    'session_key': session_key,
                                    'session_value': session_value,
                            },
                            success:function(data)
                            {
                                    console.log( data );
                            },
                            error:function(errorThrown){
                                    console.log(errorThrown);
                            }
                    });

            }
        );



        jQuery(".checkout-button").click( function()
            {

                    var count = 1;
                    var btn_name = "Checkout";
                    var requri = document.getElementById('req').value;
                    var http_host = document.getElementById('ip').value;
                    var country = document.getElementById('country').value;
                    var user_id = document.getElementById('user_name').value;
		    var user_email = document.getElementById('user_email').value;
                    var ajaxurl = document.getElementById('ajaxurl').value;
                    var page_url = document.getElementById('page_url').value;
		    var page_title = document.getElementById('page_title').value;
                    var prod_id = document.getElementById('prod_id').value;
                    var product = document.getElementById('cart_items').value;
		    var session_id = jQuery( "#session_id" ).val();
                    var session_key = jQuery( "#session_key" ).val();
                    var session_value = jQuery( "#session_val" ).val();


                    jQuery.ajax({
                            type: 'post',
                            url: ajaxurl,
			    
                            data: {
                                    'action' : 'button_click',
                                    'count' : count,
                                    'btn_name' : btn_name,
                                    'page_url' : page_url,
				    'page_title': page_title,
                                    'http_host': http_host,
                                    'country' : country,
                                    'user_id' : user_id,
				    'user_email': user_email,
                                    'prod_id' : prod_id,
                                    'product' : product,
				    'session_id': session_id,
                               	    'session_key': session_key,
                                    'session_value': session_value,
                            },
                            success:function(data)
                            {
                            },
                            error:function(errorThrown){
                                    console.log(errorThrown);
                            }
                    });

            });

        jQuery('.woocommerce-checkout').submit( function()
        {
		var btn_name;
		btn_name = "Payment";
		if(jQuery('#payment_method_cod').prop("checked")){
			btn_name = "CashOnDelivery";
		}

                if(jQuery('#payment_method_paypal').prop("checked"))
		{
			btn_name = "ProceedToPaypal";
		}
		if(jQuery('#payment_method_bacs').prop("checked")){
			btn_name = "DirectBankTransfer";
		}
		if(jQuery('#payment_method_cheque').prop("checked")){
			btn_name = "ChequePayment";
		}
		
                        var count = 1;
                        var requri = document.getElementById('req').value;
                        var http_host = document.getElementById('ip').value;
                        var country = document.getElementById('country').value;
			var page_title = document.getElementById('page_title').value;
                        var user_id = document.getElementById('user_name').value;
			var user_email = document.getElementById('user_email').value;
                        var ajaxurl = document.getElementById('ajaxurl').value;
                        var page_url = document.getElementById('page_url').value;
                        var prod_id = document.getElementById('prod_id').value;
                        var product = document.getElementById('cart_items').value;
			var session_id = jQuery( "#session_id" ).val();
   	                var session_key = jQuery( "#session_key" ).val();
        	        var session_value = jQuery( "#session_val" ).val();

                        jQuery.ajax({
                                type: 'post',
                                url: ajaxurl,
                                data: {
                                        'action' : 'button_click',
                                        'count' : count,
                                        'btn_name' : btn_name,
                                        'page_url' : page_url,
					'page_title': page_title,
                                        'http_host': http_host,
                                        'country' : country,
                                        'user_id' : user_id,
					'user_email': user_email,
                                        'prod_id' : prod_id,
                                        'product' : product,
					'session_id': session_id,
                                	'session_key': session_key,
                                	'session_value': session_value,
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
