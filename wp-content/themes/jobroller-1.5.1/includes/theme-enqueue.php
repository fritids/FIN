<?php
/**
 * These are scripts used within the JobRoller theme
 * To increase speed and performance, we only want to
 * load them when needed
 *
 * @package JobRoller
 * @version 1.0
 *
 */

function jr_load_scripts() {
	global $app_abbr;
	
	$http = (is_ssl()) ? 'https' : 'http';
	
	// load google cdn hosted scripts if enabled
    if (get_option($app_abbr.'_google_jquery') == 'yes') :
		
		wp_deregister_script('jquery');
		wp_register_script('jquery', (''.$http.'://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'), false, '1.4.2');
		wp_register_script('jquery-ui-custom', ''.$http.'://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', false, '1.8');
	
	else :
	
		wp_register_script('jquery-ui-custom', get_bloginfo('template_directory').'/includes/js/jquery-ui-1.8.custom.min.js', false, '1.8');
	
	endif;

	wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-custom');
    
    wp_enqueue_script('defaultvalue', get_bloginfo('template_directory').'/includes/js/jquery.defaultvalue.js', array('jquery'), '');
    wp_enqueue_script('jquery-tag', get_bloginfo('template_directory').'/includes/js/jquery.tag.js', array('jquery'), '');
    wp_enqueue_script('smoothscroll', get_bloginfo('template_directory').'/includes/js/smoothscroll.js', array('jquery'), '');
    wp_enqueue_script('lazyload', get_bloginfo('template_directory').'/includes/js/jquery.lazyload.mini.js', array('jquery'), '1.5.0');
    wp_enqueue_script('elastic', get_bloginfo('template_directory').'/includes/js/jquery.elastic.js', array('jquery'), '1.0');
    wp_enqueue_script('fancybox', get_bloginfo('template_directory').'/includes/js/jquery.fancybox-1.3.4.pack.js', array('jquery'), '1.3.4');
    wp_enqueue_script('qtip', get_bloginfo('template_directory').'/includes/js/jquery.qtip.min.js', array('jquery'), '1.0.0-rc3');
    wp_enqueue_script('general', get_bloginfo('template_directory').'/includes/js/theme-scripts.js', array('jquery'), '3.0');
	
	wp_enqueue_script('googlemaps', $http.'://www.google.com/jsapi');
	
	if (!is_ssl()) :
		
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false&language='.get_option('jr_gmaps_lang').'&region='.get_option('jr_gmaps_region').'&gl='.get_option('jr_gmaps_region').'"></script>';
		
	else :
	
		echo '<script type="text/javascript" src="https://maps-api-ssl.google.com/maps/api/js?v=3&sensor=false&language='.get_option('jr_gmaps_lang').'&region='.get_option('jr_gmaps_region').'&gl='.get_option('jr_gmaps_region').'"></script>';

	endif;
	
	
	$jr_enable_indeed_feeds = get_option('jr_enable_indeed_feeds');
	if ($jr_enable_indeed_feeds=='yes') :
		 
		 wp_enqueue_script('indeed-api', ''.$http.'://www.indeed.com/ads/apiresults.js');

	endif;
	
	/* Script variables */
	$params = array(
		'lazyload_placeholder' 			=> get_bloginfo('template_directory').'/images/grey.gif',
		'ajax_url' 						=> admin_url('admin-ajax.php'),
		'get_indeed_results_nonce' 		=> wp_create_nonce("get-indeed"),
	);

	wp_localize_script( 'general', 'jobroller_params', $params );
}

// this function is called when submitting a new job listing
function jr_load_form_scripts() {
    // only load the tinymce editor when html is allowed
    if (get_option('jr_html_allowed') == 'yes') {
        wp_enqueue_script('tiny_mce', get_bloginfo('url').'/wp-includes/js/tinymce/tiny_mce.js');
        wp_enqueue_script('tiny_mce-wp-langs-en', get_bloginfo('url').'/wp-includes/js/tinymce/langs/wp-langs-en.js');
    }
}

// to speed things up, don't load these scripts in the WP back-end (which is the default)
if(!is_admin()) {
    add_action('wp_print_scripts', 'jr_load_scripts');
    // add_action('wp_print_styles', 'cp_load_styles');
}

