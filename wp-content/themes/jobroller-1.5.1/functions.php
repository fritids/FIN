<?php
/**
 * JobRoller functions file
 * Do not modify this file unless it's 
 * below the noted comment section
 *
 * @package JobRoller
 * @author AppThemes
 * @copyright 2010 all rights reserved
 *
 */


// error reporting options
// error_reporting(0);
// $wpdb->show_errors();
// $wpdb->hide_errors();

// load JobRoller theme functions
require_once (TEMPLATEPATH . '/includes/theme-functions.php');

if ( is_admin() )
	require_once( dirname(__FILE__) . '/framework/admin/updater.php' );

/**
 * add any of your custom functions below this section
 */



?>