/*
 * JobRoller theme jQuery functions
 * Written by AppThemes
 *
 * Copyright (c) 2010 AppThemes (http://appthemes.com)
 *
 * Built for use with the jQuery library
 * http://jquery.com
 *
 * Version 1.0
 *
 * Left .js uncompressed so it's easier to customize
 */

jQuery(document).ready(function() {
	
	/* Load more indeed results */
	jQuery('a.more_indeed_results').click(function(){
		
		var link = jQuery(this);
		
		jQuery(link).fadeOut('fast');
		
		if (jQuery(link).is('.front_page')) {
			load = 'front';
		} else if (jQuery(link).is('.search_page')) {
			load = 'search';
		} else {
			load = '';
		}
		
		var data = {
			action: 			'get_more_indeed_results',
			security: 			jobroller_params.get_indeed_results_nonce,
			page:				jQuery(link).attr('rel'),
			load:				load
		};
			
		jQuery.post( jobroller_params.ajax_url, data, function(response) {

			jQuery('ol.indeed_results').append(response);
			var current = parseInt(jQuery(link).attr('rel'));
			jQuery(link).attr('rel', (current + 1)).fadeIn();
			
			jQuery('html, body').animate({
			    scrollTop: jQuery("li#more-" + current).offset().top
			}, 500);
		
		});
		
		return false;

	});
	
	/* Search Geo Location */
	function clientside_geo_lookup() {
		
		var address_string = jQuery('#near').val();
		if (address_string) {
			
			var geo = new google.maps.Geocoder();
			geo.geocode({'address' : address_string}, function(results, status){
			    
			    latitude 			= results[0].geometry.location.lat();
			    longitude 			= results[0].geometry.location.lng();
			    north_east_lat		= results[0].geometry.bounds.getNorthEast().lat();
			    south_west_lat		= results[0].geometry.bounds.getSouthWest().lat();
			    north_east_lng		= results[0].geometry.bounds.getNorthEast().lng();
			    south_west_lng		= results[0].geometry.bounds.getSouthWest().lng();
			    
			    full_address 	= results[0]['formatted_address'];
			    
			    jQuery('input#field_longitude').val( longitude );
				jQuery('input#field_latitude').val( latitude );
				jQuery('input#field_full_address').val( full_address );
				jQuery('input#field_north_east_lat').val( north_east_lat );
				jQuery('input#field_south_west_lat').val( south_west_lat );
				jQuery('input#field_north_east_lng').val( north_east_lng );
				jQuery('input#field_south_west_lng').val( south_west_lng );

			});
		}
		jQuery('#searchform').unbind('submit');
		setTimeout("jQuery('#searchform').submit();", 100);
		return false;
	}
	jQuery('#searchform').bind('submit', function() {
		return clientside_geo_lookup();
	});
	
	/* Placeholder fallback */
	jQuery(' [placeholder] ').defaultValue();
	
	/* Tag input */
	jQuery('.tag-input-commas').tag({separator: ','});

    /* Apply for job slider */
    jQuery('#share_form, #apply_form:not(.open)').hide();

    if (jQuery('#apply_form').is('.open')) jQuery('a.apply_online').addClass('active');

    jQuery('a.apply_online').click(function(){
    	jQuery('#job_map').slideUp();
        jQuery('#share_form').slideUp();
        jQuery('#apply_form').slideToggle();
        jQuery('a.share').removeClass('active');
        jQuery(this).toggleClass('active');
        return false;
    });

    jQuery('a.share').click(function(){
    	jQuery('#job_map').slideUp();
        jQuery('#apply_form').slideUp();
        jQuery('#share_form').slideToggle();
        jQuery('a.apply_online').removeClass('active');
        jQuery(this).toggleClass('active');
        return false;
    });
    
    // Show single job apply and print section 
    jQuery('ul.section_footer').show();

    // add jquery lazy load for images 
    jQuery('img:not(.load)').lazyload({
        effect:'fadeIn',
        placeholder: jobroller_params.lazyload_placeholder
    });
	
	jQuery('textarea.grow').autogrow();

	// qTips
	jQuery('h1.resume-title span, .resume_header img').qtip({
		content: {
			text: jQuery('.user_prefs_wrap')
		},
		position: {
			corner: {
				tooltip: 'bottomMiddle',
				target: 'topMiddle'
			},
		  	adjust: {
		  		y: 8
			}
		},
		style: {
		  width: 300,
		  border: {
		     width: 0,
		     radius: 5
		  },
		  padding: 12, 
		  textAlign: 'left',
		  tip: true, 
		  name: 'light' 
		}
    });
	jQuery('ol.resumes li').qtip({
		position: {
			corner: {
				tooltip: 'bottomMiddle',
				target: 'topMiddle'
			},
		  	adjust: {
		  		y: 14
			}
		},
		style: {
		  width: 300,
		  border: {
		     width: 0,
		     radius: 5
		  },
		  padding: 12, 
		  textAlign: 'left',
		  tip: true,
		  name: 'light'
		}
    });
});