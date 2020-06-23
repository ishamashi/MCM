/**
 * Eael Admin Script
 *
 * @since  v1.0.0
 */

;( function( $ ) {
	'use strict';
	/**
	 * Eael Tabs
	 */
	$( '.eael-tabs li a' ).on( 'click', function(e) {
		e.preventDefault();
		$( '.eael-tabs li a' ).removeClass( 'active' );
		$(this).addClass( 'active' );
		var tab = $(this).attr( 'href' );
		$( '.eael-settings-tab' ).removeClass( 'active' );
		$( '.eael-settings-tabs' ).find( tab ).addClass( 'active' );
	});

	/**
	 * Save Button Reacting on Any Changes
	 */
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function() {
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
		headerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
		footerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
	} );

	/**
	 * Google Map API
	 */
	$( '#eael-popup-api-modal' ).on('click', function(e) {
		e.preventDefault();
		swal({
			title: "Google Map API Key",
			html: '<input type="text" id="google-map-api" class="swal2-input" name="google-map-api" placeholder="Google Map API" value="'+eaelAdmin.eael_google_api+'" />',
  			closeOnClickOutside: false,
  			closeOnEsc: false,
  			showCloseButton: true
		})
		.then(function(result) {
			if( !result.dismiss ) {
				var eaelGoogleMapApi = $('#google-map-api').val();
				$('#google-map-api-hidden').val(eaelGoogleMapApi);
				eael_save_settings_with_ajax(js_eael_pro_settings, headerSaveBtn, footerSaveBtn);
			}
		});
	});

	/**
	 * Mailchimp API
	 */
	$( '#eael-popup-mailchimp-api-modal' ).on('click', function(e) {
		e.preventDefault();
		swal({
			title: "Mailchimp API Key",
			html: '<input type="text" id="mailchimp-api" class="swal2-input" name="mailchimp-api" placeholder="Mailchimp API" value="'+eaelAdmin.eael_mailchimp_api+'" />',
  			closeOnClickOutside: false,
  			closeOnEsc: false,
  			showCloseButton: true
		})
		.then(function(result) {
			if( !result.dismiss ) {
				var eaelGoogleMapApi = $('#mailchimp-api').val();
				$('#mailchimp-api-hidden').val(eaelGoogleMapApi);
				eael_save_settings_with_ajax(js_eael_pro_settings, headerSaveBtn, footerSaveBtn);
			}
		});
	});

	/**
	 * Saving Data With Ajax Request
	 */	
	$( '.js-eael-settings-save' ).on( 'click', function(e) {
		e.preventDefault();
		if( $(this).hasClass('save-now') ) {
			eael_save_settings_with_ajax(js_eael_pro_settings, headerSaveBtn, footerSaveBtn, $(this));
		}else {
			$(this).attr('disabled', 'true').css('cursor', 'not-allowed');
		}
	} );
	$('#essential-addons-elementor-license-key').on('keypress', function(e) {
		if(e.which == 13) {
			$('.eael-license-activation-btn').click();
			return false;
		}
	});
	

	/**
	 * Ajax Save
	 */
	function eael_save_settings_with_ajax(js_eael_pro_settings, headerSaveBtn, footerSaveBtn, _this) {
		$.ajax( {
			url: js_eael_pro_settings.ajaxurl,
			type: 'post',
			data: {
				action: 'save_settings_with_ajax',
				fields: $( 'form#eael-settings' ).serialize(),
			},
			beforeSend: function() {
				_this.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Saving Data..');
			},
			success: function( response ) {
				
				setTimeout(function() {
					_this.html('Save Settings');
					swal({
						title: 'Settings Saved!',
						test: 'Click OK to continue',
						type: 'success',
					});
					headerSaveBtn.removeClass( 'save-now' );
					footerSaveBtn.removeClass( 'save-now' );
				}, 2000);
				
			},
			error: function() {
				swal({
					title: 'Opps..',
					test: 'Something went wrong!',
					type: 'error',
				});
			}
		} );
	}
} )( jQuery );
