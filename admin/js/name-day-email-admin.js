(function( $ ) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 */
	 
	 	jQuery( document ).on(
		"click",
		"#namedaysemail-setting #restore_values_btn",
		function(){
			event.preventDefault();
			var nonce = jQuery( this ).attr( 'attr-nonce' );
			var data  = {
				action: 'namedayemail_restore_settings',
				nonce: nonce,
			};
			jQuery.ajax(
				{
					type: "post",
					url: ajaxurl,
					data: data,
					beforeSend: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).addClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).addClass( 'loader' );
					},
					complete: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).removeClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).removeClass( 'loader' );
					},
					success: function(response) {
						location.reload();
					}
				}
			);
			return false;
		}
	);
	 
	 	jQuery( document ).on(
	"click",
	"#birthdaysemail-setting #restore_bd_values_btn",
	function() {
		event.preventDefault();
		var nonce = jQuery( this ).attr( 'attr-nonce' );
		var data  = {
			action: 'birthdayemail_restore_settings',
			nonce: nonce,
		};
		jQuery.ajax(
		{
			type: "post",
			url: ajaxurl,
			data: data,
			beforeSend: function(response) {
				jQuery( "#birthdaysemail-setting .loader_cover" ).addClass( 'active' );
				jQuery( "#birthdaysemail-setting .birthdays_loader" ).addClass( 'loader' );
			},
			complete: function(response) {
				jQuery( "#birthdaysemail-setting .loader_cover" ).removeClass( 'active' );
				jQuery( "#birthdaysemail-setting .birthdays_loader" ).removeClass( 'loader' );
			},
			success: function(response) {
				location.reload();
			}
		}
		);
		return false;
	}
	);
		 
	 	jQuery( document ).on(
	"click",
	"#namedaysemail-setting #download_btn",
	function() {
		event.preventDefault();
		var nonce = jQuery( this ).attr( 'attr-nonce' );
		var data  = {
			action: 'namedayemail_download_csv',
			nonce: nonce,
		};
		jQuery.ajax(
		{
			type: "post",
			url: ajaxurl,
			data: data,
			beforeSend: function(response) {
				jQuery( "#namedaysemail-setting .loader_cover" ).addClass( 'active' );
				jQuery( "#namedaysemail-setting .namedays_loader" ).addClass( 'loader' );
			},
			complete: function(response) {
				jQuery( "#namedaysemail-setting .loader_cover" ).removeClass( 'active' );
				jQuery( "#namedaysemail-setting .namedays_loader" ).removeClass( 'loader' );
			},
			success: function(response) {
				location.reload();
			}
		}
		);
		return false;
	}
	);
		 
	 	jQuery( document ).on(
		"click",
		"#clear_log_btn",
		function(){
			event.preventDefault();
			var nonce = jQuery( this ).attr( 'attr-nonce' );
			var data  = {
				action: 'mydayemail_clear_log',
				nonce: nonce,
			};
			jQuery.ajax(
				{
					type: "post",
					url: ajaxurl,
					data: data,
					beforeSend: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).addClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).addClass( 'loader' );
					},
					complete: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).removeClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).removeClass( 'loader' );
					},
					success: function(response) {
						location.reload();
					}
				}
			);
			return false;
		}
	);	 

	 	jQuery( document ).on(
		"click",
		"#namedaysemail-setting #test_btn",
		function(){
			event.preventDefault();
			var nonce = jQuery( this ).attr( 'attr-nonce' );
			var data  = {
				action: 'namedayemail_make_test',
				nonce: nonce,
			};
			jQuery.ajax(
				{
					type: "post",
					url: ajaxurl,
					data: data,
					beforeSend: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).addClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).addClass( 'loader' );
					},
					complete: function(response){
						jQuery( "#namedaysemail-setting .loader_cover" ).removeClass( 'active' );
						jQuery( "#namedaysemail-setting .namedays_loader" ).removeClass( 'loader' );
					},
					success: function(response) {
						location.reload();
					}
				}
			);
			return false;
		}
	);	


jQuery( document ).on(
"click",
"#birthdaysemail-setting #test_bd_btn",
function() {
	event.preventDefault();
	var nonce = jQuery( this ).attr( 'attr-nonce' );
	var data  = {
		action: 'birthdayemail_make_test',
		nonce: nonce,
	};
	jQuery.ajax(
	{
		type: "post",
		url: ajaxurl,
		data: data,
		beforeSend: function(response) {
			jQuery( "#birthdaysemail-setting .loader_cover" ).addClass( 'active' );
			jQuery( "#birthdaysemail-setting .birthdays_loader" ).addClass( 'loader' );
		},
		complete: function(response) {
			jQuery( "#birthdaysemail-setting .loader_cover" ).removeClass( 'active' );
			jQuery( "#birthdaysemail-setting .birthdays_loader" ).removeClass( 'loader' );
		},
		success: function(response) {
			location.reload();
		}
	}
	);
	return false;
}
);


})( jQuery );

jQuery( function( $ ) {
    $('.woocommerce-help-tip').tipTip({
        'attribute': 'data-tip',
        'fadeIn':    50,
        'fadeOut':   50,
        'delay':     200
    });
});
