<?php

/**
 * Check if resumes are enabled or not
 */
function jr_resumes_are_disabled() {
	if (get_option('jr_allow_job_seekers')=='no') return true;
	return false;
}

/**
 * Check if resumes are visible or not
 */
function jr_resume_is_visible() {

	/* Support keys so logged out users can view a resume if they are sent the link via email (apply form) */
	if (is_single()) :
		
		if (isset($_GET['key']) && $_GET['key']) :
			
			global $post;
			
			$key = get_post_meta( $post->ID, '_view_key', true );
			
			if ($key==$_GET['key']) :
				return true;
			endif;
			
		endif;
		
	endif;
	
	/* Check user has access */
	if (get_option('jr_resume_require_subscription')=="yes") :
		if ( get_user_meta( get_current_user_id(), '_valid_resume_subscription', true ) ) return true;
		return false;
	endif;
	
	/* Normal visibility checking */
	$visibility = get_option('jr_resume_listing_visibility');
	
	switch ($visibility) :
		
		case "public" :
			return true;
		break;
		case "members" :
			if (!is_user_logged_in()) :
				return false;
			endif;
		break;
		case "recruiters" :
			if (!current_user_can('can_view_resumes')) :
				return false;
			endif;
		break;
		case "listers" :
		default :
			if (!current_user_can('can_submit_job')) :
				return false;
			endif;
		break;
		
	endswitch;
	
	return true;
}

/**
 * Check if resumes require subscription
 */
function jr_viewing_resumes_require_subscription() {
	
	if (get_option('jr_resume_require_subscription')=="yes") return true;
	
	return false;
}

/**
 * Check if current user can actually subscribe
 */
function jr_current_user_can_subscribe_for_resumes() {
	
	if (!is_user_logged_in()) return false;
	
	$visibility = get_option('jr_resume_listing_visibility');
	
	switch ($visibility) :
		
		case "public" :
			return true;
		break;
		case "members" :
			return true;
		break;
		case "recruiters" :
			if (current_user_can('can_view_resumes')) :
				return true;
			endif;
		break;
		case "listers" :
		default :
			if (current_user_can('can_submit_job')) :
				return true;
			endif;
		break;
		
	endswitch;
	
	return false;
	
}

/**
 * Check if resumes are disabled/visible and redirect
 */
function jr_resume_page_auth() {
	
	## Enabled/Disabled
	if (jr_resumes_are_disabled()) :
		wp_redirect(get_bloginfo('url'));
		exit;
	endif;
	
}

/**
 * Function which outputs the paypal subscription button
 */
function jr_resume_subscribe_button() {

	$paypal_email = get_option('jr_jobs_paypal_email');
	$currency = get_option('jr_jobs_paypal_currency');
	$item_name = urlencode(sprintf(__('Access to %s\'s resume database', 'appthemes'), get_bloginfo('name')));	
		
	if (get_option('jr_use_paypal_sandbox')=='yes') :
		$paypal_adr = 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&';
	else :
		$paypal_adr = 'https://www.paypal.com/webscr?';
	endif; 

	if(get_option('jr_enable_paypal_ipn') == 'yes') :
		$notify_url = urlencode(trailingslashit(get_bloginfo('wpurl')).'?paypalListener=RESUME_SUBSCRIPTION'); // FOR IPN - notify_url
		$return = urlencode( home_url() ); // Thank you page - return
	else :
		$notify_url = '';
		$return = urlencode( home_url() ); // Add new confirm page - return
	endif;
	
	// Get subscription options
	$allow_trial = get_option('jr_resume_allow_trial');
	$trial_cost = get_option('jr_resume_trial_cost');
	$trial_length = (int) get_option('jr_resume_trial_length');
	$trial_unit = get_option('jr_resume_trial_unit');
	$access_cost = get_option('jr_resume_access_cost');
	$access_length = (int) get_option('jr_resume_access_length');
	$access_unit = get_option('jr_resume_access_unit');
	
	if (!$access_cost) $access_cost = '0';
	if (!$trial_cost) $trial_cost = '0';
	if (!$trial_unit) $trial_unit = 'M';
	if (!$access_unit) $access_unit = 'M';
	
	$paypal_args = array(
		'cmd' 			=> 	'_xclick-subscriptions',
		'src' 			=>	'1',
		'sra'			=>	'1',
		't3'			=>	$access_unit,
		'p3'			=>	$access_length,
		'business'		=>	$paypal_email,
		'item_name'		=> 	$item_name,
		'a3'			=> 	$access_cost,
		'no_shipping'	=> '1',
		'no_note'		=> '1',
		'currency_code'	=> $currency,
		'charset'		=> 'UTF-8',
		'return'		=> $return,
		'rm'			=> '2',
		'custom'		=> get_current_user_id(),
		'notify_url'	=> $notify_url
	);
	
	if ($allow_trial) :
		$paypal_args = array_merge($paypal_args, array(
			'a1' => $trial_cost,
			'p1' => $trial_length,
			't1' => $trial_unit
		));
	endif;
	
	$paypal_args = apply_filters('jr_resume_subscribe_button_args', $paypal_args);
	
	$paypal_link = $paypal_adr;
	
	foreach ($paypal_args as $key => $value) :
		$paypal_link .= '&' . $key . '=' . $value;
	endforeach;
	
	echo '<p class="button"><a href="'.$paypal_link.'">'.__('Subscribe &rarr;', 'appthemes').'</a></p>';

}


