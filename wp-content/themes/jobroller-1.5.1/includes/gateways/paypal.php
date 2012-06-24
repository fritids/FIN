<?php
/* Process Order Payment - PayPal IPN
 *
 * @author AppThemes
 * @version 1.2
 *
 *
 */
function jr_update_listing_after_payment($posted) {

	global $jr_log;
	
	$jr_log->write_log('Valid IPN response detected: '. print_r( $posted, true ) ); 
    
    // Custom holds post ID
    if ( !empty($posted['txn_type']) && !empty($posted['custom']) && is_numeric($posted['custom']) && $posted['custom']>0 ) {

        $accepted_types = array('cart', 'express_checkout', 'web_accept');

        // Check transation is what we want
        if (!in_array(strtolower($posted['txn_type']), $accepted_types)) exit;
		
		$jr_order = new jr_order( $posted['custom'] );

        if ($jr_order->order_key!==$posted['item_number']) exit;

        if ($posted['test_ipn']==1 && $posted['payment_status']=='Pending') $posted['payment_status'] = 'completed';

        // We are here so lets check status and do actions
        switch (strtolower($posted['payment_status'])) :
            case 'completed' :
            	// Payment was made so we can approve the job
                $jr_order->complete_order('IPN');

                $payment_data = array();
		        $payment_data['payment_date'] 		= date("Y-m-d H:i:s");
		        $payment_data['payer_first_name'] 	= stripslashes(trim($_POST['first_name']));
		        $payment_data['payer_last_name'] 	= stripslashes(trim($_POST['last_name']));
		        $payment_data['payer_email'] 		= stripslashes(trim($_POST['payer_email']));
		        $payment_data['payment_type'] 		= 'PayPal';
		        $payment_data['approval_method'] 	= 'IPN'; 
		        $payment_data['payer_address']		= stripslashes(trim($_POST['residence_country']));
		        $payment_data['transaction_id']		= stripslashes(trim($_POST['txn_id']));
		        
		        $jr_order->add_payment( $payment_data );
		        
		        $jr_log->write_log( 'IPN Transaction Completed for Order #'.$posted['custom'] );
            break;
            case 'denied' :
            case 'expired' :
            case 'failed' :
            case 'voided' :
                // In these cases the payment failed so we can trash the job
                $jr_order->cancel_order();
                $jr_log->write_log( 'IPN Transaction Failed for Order #'.$posted['custom'] );
            break;
            default:
            	// Default if action not recognised
            	$jr_log->write_log( 'IPN Transaction default action. Nothing done. Order #'.$posted['custom'] );
            break;
        endswitch;

    }

}
add_action('valid-paypal-ipn-request', 'jr_update_listing_after_payment');

function jr_handle_resume_subscriptions_ipn($posted) {

	global $jr_log;
	
	$jr_log->write_log('Valid IPN response detected: '. print_r( $posted, true ) ); 
    
    // Custom holds post ID
    if ( !empty($posted['txn_type']) && !empty($posted['custom']) && is_numeric($posted['custom']) && $posted['custom']>0 ) {

		$user_id = (int) $posted['custom'];
		
		switch (strtolower($posted['txn_type'])) :
			
			case "subscr_signup" :
				update_user_meta( $user_id, '_valid_resume_subscription', '1' );
				do_action('user_resume_subscription_started', $user_id);
				exit;
			break;
			case "subscr_payment" :
				exit;
			break;
			case "subscr_cancel" :
			case "subscr_failed" :
			case "subscr_eot" :
				update_user_meta( $user_id, '_valid_resume_subscription', '0' );
				do_action('user_resume_subscription_ended', $user_id);
				exit;
			break;
			
		endswitch;

    }

}
add_action('valid-paypal-resume-subscription-ipn-request', 'jr_handle_resume_subscriptions_ipn');

function jr_process_payment() {

    function jr_ipn_request_is_valid() {
    
    	global $jr_log;
	
		$jr_log->write_log( 'Checking validity of IPN Request. '. print_r( $_POST, true ) ); 

        // add the paypal cmd to the post array
        $_POST['cmd'] = '_notify-validate';

        // send the message back to PayPal just as we received it
        $params = array( 
        	'body' => $_POST,
			'timeout' 	=> 30
        );

        // get the correct paypal url to post request to
        if (get_option('jr_use_paypal_sandbox')=='yes')
            $paypal_adr = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $paypal_adr = 'https://www.paypal.com/cgi-bin/webscr';
            
        // post it all back to paypal to get a response code
        $response = wp_remote_post( $paypal_adr, $params );
        
        // Retry
		if ( is_wp_error($response) ) {
			$params['sslverify'] = false;
			$response = wp_remote_post( $paypal_adr, $params );
		}
        
        // send debug email to see paypal ipn response array
        if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'PayPal IPN Response Debug - ' . $paypal_adr, "".print_r($response, true));
		
        // cleanup
        unset($_POST['cmd']);

        // check to see if the request was valid
        if ( !is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && (strcmp( $response['body'], "VERIFIED") == 0)) {
            return true;
        } else {
            // response was invalid so don't proceed and send email to admin
            wp_mail(get_option('admin_email'), 'PayPal IPN - Response', print_r($response, true)."\n\n\n".print_r($_REQUEST, true));
            return false;
        }

    }
    
    if (isset($_GET['paypalListener']) && $_GET['paypalListener'] == 'IPN') {

        $_POST = stripslashes_deep($_POST);

        if (jr_ipn_request_is_valid()) {
            do_action("valid-paypal-ipn-request", $_POST);
            // send debug email to see paypal ipn post vars
            if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'Valid IPN Message', "".print_r($_POST, true));
        } else {
        	global $jr_log;
			$jr_log->write_log( 'IPN Request was invalid :(' ); 
        }
        exit;

    }
    
    if (isset($_GET['paypalListener']) && $_GET['paypalListener'] == 'RESUME_SUBSCRIPTION') {
    
    	$_POST = stripslashes_deep($_POST);

        if (jr_ipn_request_is_valid()) {
            do_action("valid-paypal-resume-subscription-ipn-request", $_POST);
            // send debug email to see paypal ipn post vars
            if (get_option('jr_paypal_ipn_debug') == 'true') wp_mail(get_option('admin_email'), 'Valid RESUME_SUBSCRIPTION IPN Message', "".print_r($_POST, true));
        } else {
        	global $jr_log;
			$jr_log->write_log( 'RESUME_SUBSCRIPTION IPN Request was invalid :(' ); 
        }
        exit;

    }
    
}
add_action('init', 'jr_process_payment');