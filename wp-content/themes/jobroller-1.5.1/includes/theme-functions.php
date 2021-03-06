<?php
/**
 * JobRoller core theme functions
 * This file is the backbone and includes all the core functions
 * Modifying this will void your warranty and could cause
 * problems with your instance of JR. Proceed at your own risk!
 *
 *
 * @package JobRoller
 * @author AppThemes
* @url http://www.appthemes.com
 *
 */

// Define vars and globals
global $app_version, $app_form_results, $featured_job_cat_id, $jr_log, $app_abbr;

// current jobroller version
$app_theme = 'JobRoller';
$app_abbr = 'jr';
$app_version = '1.5.1';


define('THE_POSITION', 3);
define('FAVICON', get_bloginfo('template_directory').'/images/job_icon.png');

$featured_job_cat_id = get_option('jr_featured_category_id');

load_theme_textdomain('appthemes');

// Define rss feed urls
$app_rss_feed = 'http://feeds2.feedburner.com/appthemes';
$app_twitter_rss_feed = 'http://twitter.com/statuses/user_timeline/appthemes.rss';
$app_forum_rss_feed = 'http://forums.appthemes.com/external.php?type=RSS2';

// Define the db tables we use
$jr_db_tables = array('jr_job_packs', 'jr_customer_packs', 'jr_orders', 'jr_counter_daily', 'jr_counter_total');

// setup the custom post types and taxonomies as constants
// do not modify this after installing or it will break your theme!
// started using in places in 1.4. slowly migrate over with the next version
define('APP_POST_TYPE', 'job_listing');
define('APP_POST_TYPE_RESUME', 'resume');
define('APP_TAX_CAT', 'job_cat');
define('APP_TAX_TAG', 'job_tag');
define('APP_TAX_TYPE', 'job_type');
define('APP_TAX_SALARY', 'job_salary');
define('APP_TAX_RESUME_SPECIALITIES', 'resume_specialities');
define('APP_TAX_RESUME_GROUPS', 'resume_groups');
define('APP_TAX_RESUME_LANGUAGES', 'resume_languages');
define('APP_TAX_RESUME_CATEGORY', 'resume_category');
define('APP_TAX_RESUME_JOB_TYPE', 'resume_job_type');


// Classes
get_template_part('includes/classes/packs.class');
get_template_part('includes/classes/orders.class');

// Include functions

// Payment
get_template_part('includes/gateways/paypal');

// Logging
get_template_part('includes/theme-log');
$jr_log = new jrLog();

// Framework functions
get_template_part('includes/appthemes-hooks');
get_template_part('includes/theme-hooks');
get_template_part('includes/appthemes-functions');
	
// Theme functions
get_template_part('includes/theme-sidebars');
get_template_part('includes/theme-support');
get_template_part('includes/theme-security');
get_template_part('includes/theme-comments');
get_template_part('includes/theme-header');
get_template_part('includes/theme-footer');
get_template_part('includes/theme-widgets');
get_template_part('includes/theme-emails');
get_template_part('includes/theme-geolocation');
get_template_part('includes/theme-actions');
get_template_part('includes/theme-cron');
get_template_part('includes/indeed/theme-indeed');
get_template_part('includes/theme-enqueue');
get_template_part('includes/theme-stats');
get_template_part('includes/theme-users');
get_template_part('includes/theme-resumes');

// include the new custom post type and taxonomy declarations.
// must be included on all pages to work with site functions
get_template_part('includes/admin/admin-post-types');
	
// Front-end includes
if (!is_admin()) :
    get_template_part('includes/countries');
    get_template_part('includes/theme-login');
    get_template_part('includes/forms/submit-job/submit-job-process');
    get_template_part('includes/forms/submit-job/submit-job-form');
    get_template_part('includes/forms/edit-job/edit-job-process');
    get_template_part('includes/forms/edit-job/relist-job-process');
    get_template_part('includes/forms/edit-job/edit-job-form');
    get_template_part('includes/forms/confirm-job/confirm-job-process');
    get_template_part('includes/forms/confirm-job/confirm-job-form');
    get_template_part('includes/forms/preview-job/preview-job-form');
    get_template_part('includes/forms/application/application-process');
    get_template_part('includes/forms/application/application-form');
    get_template_part('includes/forms/filter/filter-process');
    get_template_part('includes/forms/filter/filter-form');
    get_template_part('includes/forms/share/share-form');
    get_template_part('includes/forms/login/login-form');
    get_template_part('includes/forms/login/login-process');
    get_template_part('includes/forms/register/register-form');
    get_template_part('includes/forms/register/register-process');
    get_template_part('includes/forms/forgot-password/forgot-password-form');
    get_template_part('includes/forms/submit-resume/submit-resume-process');
    get_template_part('includes/forms/submit-resume/submit-resume-form');
    get_template_part('includes/forms/resume/edit_parts');
    get_template_part('includes/forms/seeker-prefs/seeker-prefs-form');
    get_template_part('includes/forms/seeker-prefs/seeker-prefs-process');
endif;

// Admin Only Functions
if (is_admin()) :
    get_template_part('includes/admin/admin-enqueue');
    get_template_part('includes/admin/admin-options');
    get_template_part('includes/admin/write-panel');
    get_template_part('includes/admin/install-script');
endif;


// run the appthemes_init() action hook
appthemes_init();

################################################################################
// Fix paging on author page
################################################################################

function custom_post_author_archive( &$query )
{
    if ( $query->is_author )
        $query->set( 'post_type', array('resume', 'job_listing') );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' );
}
add_action( 'pre_get_posts', 'custom_post_author_archive' );
        
################################################################################
// Fix location encoding in urls
################################################################################

function location_query_arg( $link ) {
	
	if (isset($_GET['location']) && $_GET['location']) :
	
		$link = add_query_arg('location', urlencode( utf8_uri_encode( $_GET['location'] ) ), $link);

	endif;
	
	return $link;
}
add_filter('get_pagenum_link', 'location_query_arg');

################################################################################
// Check theme is installed correctly
################################################################################

add_action('admin_notices', 'check_jr_environment');

function check_jr_environment() {
	$errors = array();
	
	$files = array(
		'includes/gateways/paypal.php',
		'includes/theme-cron.php',
		'includes/theme-login.php',
		'includes/theme-sidebars.php',
		'includes/theme-support.php',
		'includes/theme-comments.php',
		'includes/forms/application/application-process.php',
		'includes/forms/application/application-form.php',
		'includes/forms/filter/filter-process.php',
		'includes/forms/filter/filter-form.php',
		'includes/forms/share/share-form.php',
		'includes/theme-actions.php',
		'includes/admin/admin-options.php',
		'includes/admin/write-panel.php'
	);
	
	foreach ($files as $file) {
		if (!is_readable(TEMPLATEPATH.'/'.$file)) $errors[] = $file.__(' is not readable or does not exist - check file permissions.','appthemes');
	}
	
	if (isset($errors) && sizeof($errors)>0) {
		echo '<div class="error" style="padding:10px"><strong>'.__('JobRoller theme errors:','appthemes').'</strong>';
		foreach ($errors as $error) {
			echo '<p>'.$error.'</p>';
		}
		echo '</div>';
	}
}



// Buffer the output so headers work correctly
add_action('init', 'buffer_the_output');

function buffer_the_output() {
	ob_start();
}


// Add custom post types to the Main RSS feed
function jr_rss_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type'])) :
		$qv['post_type'] = array('post', 'job_listing');
	endif;
	return $qv;
}
add_filter('request', 'jr_rss_request');

function jr_rss_pre_get_posts($query) {
	if ($query->is_feed) $query->set('post_status','publish');
	return $query;
}
add_filter('pre_get_posts', 'jr_rss_pre_get_posts');



// get the custom taxonomy array and loop through the values
function jr_get_custom_taxonomy($post_id, $tax_name, $tax_class) {
    $tax_array = get_terms( $tax_name, array( 'hide_empty' => '0' ) );
    if ($tax_array && sizeof($tax_array) > 0) {
        foreach ($tax_array as $tax_val) {
            if ( is_object_in_term( $post_id, $tax_name, array( $tax_val->term_id ) ) ) {
                echo '<span class="'.$tax_class . ' '. $tax_val->slug.'">'.$tax_val->name.'</span>';
                break;
            }
        }
    }
}

// deletes all the database tables
function jr_delete_db_tables() {
    global $wpdb, $jr_db_tables;

    foreach ($jr_db_tables as $key => $value) :

        $sql = "DROP TABLE IF EXISTS ". $wpdb->prefix . $value;
        $wpdb->query($sql);

        printf(__("Table '%s' has been deleted.", 'appthemes'), $value);
        echo '<br/>';

    endforeach;
}

// deletes all the theme options from wp_options
function jr_delete_all_options() {
    global $wpdb;

    $sql = "DELETE FROM ". $wpdb->options
          ." WHERE option_name like 'jr_%'";
    $wpdb->query($sql);

    echo __("All JobRoller options have been deleted.", 'appthemes');
}

// Define Nav Bar Locations
register_nav_menus( array(
	'primary' => __( 'Primary Navigation' ),
	'top' => __( 'Top Bar Navigation' ),
) );

// Function to output pagination
if (!function_exists('jr_paging')) {
function jr_paging() { 
	
	?>
	<div class="clear"></div>
    <div class="paging">
        <?php if(function_exists('wp_pagenavi')) {
                wp_pagenavi();
        } else { ?>

            <div style="float:left; margin-right:10px"><?php previous_posts_link(__('&laquo; Previous page', 'appthemes')) ?></div>
            <div style="float:left;"><?php next_posts_link(__('Next page &raquo;', 'appthemes')) ?></div>

        <?php } ?>
        <div class="top"><a href="#top" title="<?php _e('Back to top', 'appthemes'); ?>"><?php _e('Top &uarr;', 'appthemes'); ?></a></div>
    </div>
    <?php
}
}


// Function to get theme image directory (so we can support sub themes)
if (!function_exists('get_template_image_url')) {
function get_template_image_url($image = '') {
    $theme = str_replace('.css','', get_option('jr_child_theme'));
    if ($theme && $theme!=='style-default') return get_bloginfo('template_url').'/images/'.$theme.'/'.$image;
    else return get_bloginfo('template_url').'/images/'.$image;
}
}


// Remaining days function
if (!function_exists('jr_remaining_days')) {
function jr_remaining_days($post) { 
    $date = get_post_meta($post->ID, '_expires', true);
   
    if ($date) :
    
    $days = floor(($date-strtotime('NOW'))/86400);
	if ($days==1) return $days.' '.__('day','appthemes');
	if ($days<1) return __('Expired', 'appthemes'); 
	return $days.' '.__('days','appthemes');
	
	endif;
	
	return '-';
}
}

// Expiry check function
if (!function_exists('jr_check_expired')) {
function jr_check_expired($post) { 
    $date = get_post_meta($post->ID, '_expires', true);
   	
   	if ($date) if ( $date < strtotime('NOW') ) return true;
    
    return false;
}
}


// Expired Message
if (!function_exists('jr_expired_message')) {
function jr_expired_message($post) {
	$expired = jr_check_expired($post); 
	if ($expired) :
		?><p class="expired"><?php _e('<strong>NOTE:</strong> This job listing has expired and may no longer be relevant!','appthemes'); ?></p><?php
	endif;
}
}

// Filter out expired posts
function jr_job_not_expired($where = '') {
	
	global $wpdb;
	
	// First we need to find posts that are expired by looking at the custom value
	$exlude_ids = $wpdb->get_col($wpdb->prepare("
		SELECT      postmeta.post_id
		FROM        $wpdb->postmeta postmeta
		WHERE       postmeta.meta_key = '_expires' 
		            and postmeta.meta_value < '%s'
	", strtotime('NOW'))); 
	
	if (sizeof($exlude_ids)>0) $where .= " AND ID NOT IN (".implode(',', $exlude_ids).") ";
	
	return $where;
}
function remove_jr_job_not_expired() {
	remove_filter('posts_where', 'jr_job_not_expired');
}
add_action('get_footer', 'remove_jr_job_not_expired');


// Get Page URL
if ( !function_exists('jr_get_current_url') ) {
function jr_get_current_url($url = '') {

	if (is_front_page() || is_search() || is_front_page()) :
		return trailingslashit(get_bloginfo('wpurl'));
	elseif (is_category()) :
		return trailingslashit(get_category_link(get_cat_id(single_cat_title("", false))));
	elseif (is_tax()) :
		
		$job_cat = get_query_var('job_cat');
		$job_type = get_query_var('job_type');
		
		if (isset($job_cat) && $job_cat) :
			$slug = $job_cat;
			return trailingslashit(get_term_link( $slug, 'job_cat' ));
		elseif (isset($job_type) && $job_type) :
			$slug = $job_type;
			return trailingslashit(get_term_link( $job_type, 'job_type' ));
		endif;
		
	endif;
	return trailingslashit($url);	
}
}

// Get currency function
if (!function_exists('jr_get_currency')) {
function jr_get_currency( $amount = '' ) {
    $currency = get_option('jr_jobs_paypal_currency');
    $currency_pos = get_option('jr_curr_symbol_pos');
    $currency_symbol = '';
    
    switch ($currency) :
        case 'GBP':
           $currency_symbol = '&pound;';
        break;
        case 'JPY':
            $currency_symbol = '&yen;';
        break;
        case 'EUR':
            $currency_symbol = '&euro;';
        break;
        case 'PLN' :
        	$currency_symbol = 'zł';
        break;
        default:
            $currency_symbol = '$';
        break;
    endswitch;
	    
    if ($amount) :
    
    	$amount_string = '';
    	
    	$thousands = (get_option('jr_curr_thousands_separator')=='decimal') ? '.' : ',';
    	$decimal = (get_option('jr_curr_decimal_separator')=='comma') ? ',' : '.';

    	$amount = number_format($amount, 2, $decimal, $thousands);
    	
    	switch ($currency_pos) :
    		case 'left_space' :
    			$amount_string = '{currency} '.$amount;
    		break;
    		case 'right' :
    			$amount_string = $amount.'{currency}';
    		break;
    		case 'right_space' :
    			$amount_string = $amount.' {currency}';
    		break;
    		default:
    			$amount_string = '{currency}'.$amount;
    		break;
    	endswitch;
    	
    	return str_replace('{currency}', $currency_symbol, $amount_string);
    
    else :
    	return $currency_symbol;
    endif;

    return;
}
function jr_get_currency_in_position( $position = 'left' ) {
    $currency = get_option('jr_jobs_paypal_currency');
    $currency_pos = get_option('jr_curr_symbol_pos');
    
    switch ($currency) :
        case 'GBP':
           $currency_symbol = '&pound;';
        break;
        case 'JPY':
            $currency_symbol = '&yen;';
        break;
        case 'EUR':
            $currency_symbol = '&euro;';
        break;
        case 'PLN' :
        	$currency_symbol = 'zł';
        break;
        default:
            $currency_symbol = '$';
        break;
    endswitch;
    
    switch ($currency_pos) :
		case 'left_space' :
			if ($position=='left') return $currency_symbol.' ';
		break;
		case 'right' :
			if ($position=='right') return $currency_symbol;
		break;
		case 'right_space' :
			if ($position=='right') return ' '.$currency_symbol;
		break;
		default:
			if ($position=='left') return $currency_symbol;
		break;
	endswitch;

    return '';
}
}


// get the visitor IP so we can include it with the job submission
if (!function_exists('jr_getIP')) {
function jr_getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
}

// tinyMCE text editor
if (!function_exists('jr_tinymce')) {
function jr_tinymce($width='', $height='') {
?>
<script type="text/javascript">
    <!--

	tinyMCEPreInit = {
		base : "<?php echo includes_url('js/tinymce'); ?>",
		suffix : "",
		mceInit : {
			mode : "specific_textareas",
			editor_selector : "mceEditor",
			theme : "advanced",
			skin : "default",
	        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
	        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,cleanup,code,|,forecolor,backcolor,|,media",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			content_css : "<?php echo get_bloginfo('stylesheet_directory'); ?>/style.css",
			languages : 'en',
			disk_cache : true,
			width : "<?php echo $width; ?>",
			height : "<?php echo $height; ?>",
			language : 'en'
		},
		load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
	};
	
	(function(){var t=tinyMCEPreInit,sl=tinymce.ScriptLoader,ln=t.mceInit.language,th=t.mceInit.theme;sl.markDone(t.base+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'_dlg.js');})(); 
	tinyMCE.init(tinyMCEPreInit.mceInit);
    
    -->
</script>
<?php
}
}

// get the date/time of the post
if (!function_exists('jr_ad_posted')) {
function jr_ad_posted($m_time) {
    //$t_time = get_the_time(__('Y/m/d g:i:s A'));
    $time = get_post_time('G', true);
    $time_diff = time() - $time;

    if ( $time_diff > 0 && $time_diff < 24*60*60 )
            $h_time = sprintf( __('%s ago', 'appthemes'), human_time_diff( $time ) );
    else
            $h_time = mysql2date(get_option('date_format'), $m_time);
    echo $h_time;
}
}

// Filters
function custom_excerpt($text) {
	global $post;
	return str_replace(' [...]', '&hellip; <a href="'. get_permalink($post->ID) . '" class="more">' . __('read more','appthemes') . '</a>', $text);
}
add_filter('the_excerpt', 'custom_excerpt');

// search on custom fields
function custom_search_join($join) {
    if ( is_search() && isset($_GET['s'])) {
        global $wpdb;
       $join = " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
    }
    return($join);
}
// search on custom fields
function custom_search_groupby($groupby) {
    if ( is_search() && isset($_GET['s'])) {
        global $wpdb;
        $groupby = " $wpdb->posts.ID ";
    }
    return($groupby);
}
// search on custom fields
function custom_search_where($where) {
    global $wpdb;
    $old_where = $where;
    if (is_search() && isset($_GET['s']) && !isset($_GET['resume_search'])) {
		// add additional custom fields here to include them in search results
        $customs = array('_Company', 'geo_address', '_CompanyURL', 'geo_short_address', 'geo_country', 'geo_short_address_country');
        $query = '';
        $var_q = stripslashes($_GET['s']);
        preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
        $search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
        
        $n = '%';
        $searchand = '';
        foreach((array)$search_terms as $term) {
            $term = addslashes_gpc($term);
            $query .= "{$searchand}(";
            $query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
            foreach($customs as $custom) {
                $query .= " OR (";
                $query .= "($wpdb->postmeta.meta_key = '$custom')";
                $query .= " AND ($wpdb->postmeta.meta_value  LIKE '{$n}{$term}{$n}')";
                $query .= ")";
            }
            $query .= ")";
            $searchand = ' AND ';
        }
        $term = $wpdb->escape($var_q);
        $where .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $where .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";

        if (!empty($query)) {
            $where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->posts.post_type = 'job_listing')";
        }
    } else if (is_search() && isset($_GET['s'])) {
    	// add additional custom fields here to include them in search results
        $customs = array(
        	'_desired_position', 
        	'_resume_websites', 
        	'_experience', 
        	'_education', 
        	'_skills',
        	'_desired_salary',
        	'_email_address',
        	'geo_address',
        	'geo_country'
        );
        $query = '';
        $var_q = stripslashes($_GET['s']);
        preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
        $search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
        
        $n = '%';
        $searchand = '';
        foreach((array)$search_terms as $term) {
            $term = addslashes_gpc($term);
            $query .= "{$searchand}(";
            $query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
            foreach($customs as $custom) {
                $query .= " OR (";
                $query .= "($wpdb->postmeta.meta_key = '$custom')";
                $query .= " AND ($wpdb->postmeta.meta_value  LIKE '{$n}{$term}{$n}')";
                $query .= ")";
            }
            $query .= ")";
            $searchand = ' AND ';
        }
        $term = $wpdb->escape($var_q);
        $where .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $where .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";

        if (!empty($query)) {
            $where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish') AND ($wpdb->posts.post_type = 'resume')";
        }
    }
    return($where);
}
if (!is_admin()) :
	// search on custom fields
	add_filter('posts_join', 'custom_search_join');
	add_filter('posts_where', 'custom_search_where');
	add_filter('posts_groupby', 'custom_search_groupby');
endif;

// redirects a user to my jobs
if (!function_exists('redirect_myjobs')) {
function redirect_myjobs( $query_string = '' ) {
	$url = get_permalink(get_option('jr_dashboard_page_id'));
	if (is_array($query_string)) $url = add_query_arg( $query_string, $url );
    wp_redirect($url);
    exit();
}
}

// redirects a user to my profile
if (!function_exists('redirect_profile')) {
function redirect_profile( $query_string = '' ) {
	$url = get_permalink(get_option('jr_user_profile_page_id'));
	if (is_array($query_string)) $url = add_query_arg( $query_string, $url );
    wp_redirect($url);
    exit();
}
}

// Output errors
if (!function_exists('jr_show_errors')) {
function jr_show_errors( $errors, $id = '' ) {
	if ($errors && sizeof($errors)>0 && $errors->get_error_code()) :
		echo '<ul class="errors" id="'.$id.'">';
		foreach ($errors->errors as $error) {
			echo '<li>'.$error[0].'</li>';
		}
		echo '</ul>';
	endif;
}
}

if (!function_exists('let_to_num')) {
	function let_to_num($v){ 
		$l = substr($v, -1);
	    $ret = substr($v, 0, -1);
	    switch(strtoupper($l)){
	    case 'P':
	        $ret *= 1024;
	    case 'T':
	        $ret *= 1024;
	    case 'G':
	        $ret *= 1024;
	    case 'M':
	        $ret *= 1024;
	    case 'K':
	        $ret *= 1024;
	        break;
	    }
	    return $ret;
	}
}

// Get job packs
if (!function_exists('jr_get_job_packs')) {
function jr_get_job_packs() {
	global $wpdb;
	
	return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."jr_job_packs ");
}
}

if (!function_exists('jr_get_user_job_packs')) {
function jr_get_user_job_packs( $user_id = 0 ) {
	global $wpdb;
	
	if (!$user_id)
		$user_id = get_current_user_id();

	if ($user_id>0)
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."jr_customer_packs WHERE user_id = %d AND (jobs_count < jobs_limit OR jobs_limit = 0) AND (pack_expires > NOW() OR pack_expires = NULL OR pack_expires = '0000-00-00 00:00:00')", $user_id ) );
}
}

// Radial location search
function jr_radial_search($location, $radius, $address_array = '') {
	global $wpdb, $app_abbr;
	if (function_exists('json_decode') && isset($location)) :
	
		if (!$radius) $radius = 50;
	
		// KM/Miles
		if (get_option($app_abbr.'_distance_unit')=='km') $radius = $radius / 1.609344;
		
		$jr_gmaps_lang = get_option('jr_gmaps_lang');
		$jr_gmaps_region = get_option('jr_gmaps_region');
		
		$location = strtolower(trim($location));
		
		// If address is not given, find it via Google Maps API or Cache
		if (!is_array($address_array)) {
		
			$address = "http://maps.google.com/maps/geo?q=".urlencode($location)."&output=json&language=".$jr_gmaps_lang."&sensor=false&gl=".$jr_gmaps_region."&region=".$jr_gmaps_region."";
			
			$cached = get_transient( 'jr_geo_'.sanitize_title($location) );
			
			if ($cached) :
				$address = $cached;
			else :
				$address = json_decode((file_get_contents($address)), true);
				if (is_array($address)) :
					set_transient( 'jr_geo_'.sanitize_title($location), $address, 60*60*24*7 ); // Cache for a week
				endif;
			endif;
			
			if (isset($address['Placemark'])) :
				// Put address info into a nice array format			
				$address_array = array(
					'north' 	=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['north'],
					'south' 	=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['south'],
					'east' 		=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['east'],
					'west' 		=> $address['Placemark'][0]['ExtendedData']['LatLonBox']['west'],
					'longitude' => $address['Placemark'][0]['Point']['coordinates'][0],
					'latitude' 	=> $address['Placemark'][0]['Point']['coordinates'][1]
				);

				$address_array['full_address'] = $address['Placemark'][0]['address'];
			
			endif;
		
		}
		
	   	if (is_array($address_array)) :
	   	
	   		if (isset($address_array['longitude']) && isset($address_array['latitude'])) :
	   			
	   			$lng_min = 0;
	   			$lng_max = 0;
	   			$lat_min = 0;
	   			$lat_max = 0;
	   			
	   			if (isset($address_array['north'])) {
	   				// Box
	   				$lng_max = $address_array['east'] + ($radius / 69);
					$lng_min = $address_array['west'] - ($radius / 69);
					$lat_min = $address_array['south'] - ($radius / 69);
					$lat_max = $address_array['north'] + ($radius / 69);
				} elseif ($address_array['north_east_lng']) {
					// Box
					$lng_max = $address_array['north_east_lng'] + ($radius / 69);
					$lng_min = $address_array['south_west_lng'] - ($radius / 69);
					$lat_min = $address_array['south_west_lat'] - ($radius / 69);
					$lat_max = $address_array['north_east_lat'] + ($radius / 69);
	   			} else {
	   				// Point (fallback)
	   				$lng_min = $address_array['longitude'] - $radius / abs(cos(deg2rad($address_array['latitude'])) * 69);
					$lng_max = $address_array['longitude'] + $radius / abs(cos(deg2rad($address_array['latitude'])) * 69);
					$lat_min = $address_array['latitude'] - ($radius / 69);
					$lat_max = $address_array['latitude'] + ($radius / 69);
	   			}

	   			$results1 = $wpdb->get_col("
	   				SELECT ID
	   				FROM $wpdb->posts
	   				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
	   				WHERE meta_key = '_jr_geo_latitude'
	   				AND (meta_value+0) between ('$lat_min') AND ('$lat_max') AND $wpdb->posts.post_status = 'publish';
	   			");
	   			$results2 = $wpdb->get_col("
	   				SELECT ID
	   				FROM $wpdb->posts
	   				LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
	   				WHERE meta_key = '_jr_geo_longitude'
	   				AND (meta_value+0)  between ('$lng_min') AND ('$lng_max') AND $wpdb->posts.post_status = 'publish';
	   			");
	   			// Anywhere
	   			$anywhere = $wpdb->get_col("
	   				SELECT ID FROM $wpdb->posts 
	   				WHERE ID NOT IN (
	   					SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta WHERE meta_key = 'geo_short_address'
	   				) AND $wpdb->posts.post_status = 'publish';
	   			");
	   			
	   			$posts = array_merge($anywhere, array_intersect($results1, $results2));
				
				return array('address' => $address_array['full_address'], 'posts' => $posts);
	   		endif;
	   	endif;
		
	endif;
	return false;
}

// Shows the map on single job listings
function jr_job_map() {
	global $post;
	
	$title = str_replace('"', '&quot;', wptexturize($post->post_title));
	$long 	= get_post_meta($post->ID, '_jr_geo_longitude', true);
	$lat 	= get_post_meta($post->ID, '_jr_geo_latitude', true);		
	
	if (!$long || !$lat) return;		
	?>
	
	<div id="job_map" style="height: 300px; display:none;"></div>
	<script type="text/javascript">
	/* <![CDATA[ */
		jQuery.noConflict();
		(function($) { 
			$(function() {
				
				// Define Map center
			    var center = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $long; ?>);
			    
				// Define Map options
				var myOptions = {
			      'zoom': 10,
			      'center': center,
			      'mapTypeId': google.maps.MapTypeId.ROADMAP
			    };
				
				// Load Map
			    var map = new google.maps.Map(document.getElementById('job_map'), myOptions);
				 
			    // Marker
				var marker = new google.maps.Marker({ position: center, map: map, title: "<?php echo $title; ?>" });
				
				// Slide Toggle
				jQuery('a.toggle_map').click(function(){
			    	jQuery('#share_form').slideUp();
			        jQuery('#apply_form').slideUp();
			        jQuery('#job_map').slideToggle(function(){
						google.maps.event.trigger(map, 'resize'); 
						map.setCenter(center); 
			        });
			        jQuery('a.apply_online').removeClass('active');
			        jQuery(this).toggleClass('active');
			        return false;
			    });

			});
		})(jQuery);
	/* ]]> */
	</script>
	<?php

}

// creates the charts on the dashboard
function jr_dashboard_charts() {
	global $wpdb;

	$sql = "SELECT COUNT(post_title) as total, post_date FROM ". $wpdb->posts ." WHERE post_type = 'job_listing' AND post_date > '" . date('Y-m-d', strtotime('-30 days')) . "' GROUP BY DATE(post_date) DESC";
	$results = $wpdb->get_results($sql);

	$listings = array();

	// put the days and total posts into an array
	foreach ($results as $result) {
		$the_day = date('Y-m-d', strtotime($result->post_date));
		$listings[$the_day] = $result->total;
	}

	// setup the last 30 days
	for($i = 0; $i < 30; $i++) {
		$each_day = date('Y-m-d', strtotime('-'. $i .' days'));

		// if there's no day with posts, insert a goose egg
		if (!in_array($each_day, array_keys($listings))) $listings[$each_day] = 0;
	}

	// sort the values by date
	ksort($listings);

	// print_r($listings);

	// Get sales - completed orders with a cost
	$sql = "SELECT SUM(cost) as total, order_date FROM ".$wpdb->prefix."jr_orders WHERE status = 'completed' AND order_date > '" . date('Y-m-d', strtotime('-30 days')) . "' GROUP BY DATE(order_date) DESC";
	$results = $wpdb->get_results($sql);

	$sales = array();

	// put the days and total posts into an array
	foreach ($results as $result) {
		$the_day = date('Y-m-d', strtotime($result->order_date));
		$sales[$the_day] = $result->total;
	}

	// setup the last 30 days
	for($i = 0; $i < 30; $i++) {
		$each_day = date('Y-m-d', strtotime('-'. $i .' days'));

		// if there's no day with posts, insert a goose egg
		if (!in_array($each_day, array_keys($sales))) $sales[$each_day] = 0;
	}

	// sort the values by date
	ksort($sales);
?>

<div id="placeholder"></div>

<script language="javascript" type="text/javascript">
// <![CDATA[
jQuery(function () {

    var posts = [
		<?php
		foreach ($listings as $day => $value) {
			$sdate = strtotime($day);
			$sdate = $sdate * 1000; // js timestamps measure milliseconds vs seconds
			$newoutput = "[$sdate, $value],\n";
			//$theoutput[] = $newoutput;
			echo $newoutput;
		}
		?>
	];

	var sales = [
		<?php
		foreach ($sales as $day => $value) {
			$sdate = strtotime($day);
			$sdate = $sdate * 1000; // js timestamps measure milliseconds vs seconds
			$newoutput = "[$sdate, $value],\n";
			//$theoutput[] = $newoutput;
			echo $newoutput;
		}
		?>
	];


	var placeholder = jQuery("#placeholder");

	var output = [
		{
			data: posts,
			label: "<?php _e('New Job Listings', 'appthemes') ?>",
			symbol: ''
		},
		{
			data: sales,
			label: "<?php _e('Total Sales', 'appthemes') ?>",
			symbol: '<?php echo jr_get_currency(); ?>',
			yaxis: 2
		}
	];

	var options = {
       series: {
		   lines: { show: true },
		   points: { show: true }
	   },
	   grid: {
		   tickColor:'#f4f4f4',
		   hoverable: true,
		   clickable: true,
		   borderColor: '#f4f4f4',
		   backgroundColor:'#FFFFFF'
	   },
       xaxis: { mode: 'time',
				timeformat: "%m/%d"
	   },
	   yaxis: { min: 0 },
	   y2axis: { min: 0, tickFormatter: function (v, axis) { return "<?php echo jr_get_currency_in_position('left'); ?>" + v.toFixed(axis.tickDecimals) + "<?php echo jr_get_currency_in_position('right'); ?>" }},
	   legend: { position: 'nw' }
    };

	jQuery.plot(placeholder, output, options);

	// reload the plot when browser window gets resized
	jQuery(window).resize(function() {
		jQuery.plot(placeholder, output, options);
	});

	function showChartTooltip(x, y, contents) {
		jQuery('<div id="charttooltip">' + contents + '</div>').css( {
		position: 'absolute',
		display: 'none',
		top: y + 5,
		left: x + 5,
		opacity: 1
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	jQuery("#placeholder").bind("plothover", function (event, pos, item) {
		jQuery("#x").text(pos.x.toFixed(2));
		jQuery("#y").text(pos.y.toFixed(2));
		if (item) {
			if (previousPoint != item.datapoint) {
                previousPoint = item.datapoint;

				jQuery("#charttooltip").remove();
				var x = new Date(item.datapoint[0]), y = item.datapoint[1];
				var xday = x.getDate(), xmonth = x.getMonth()+1; // jan = 0 so we need to offset month
				showChartTooltip(item.pageX, item.pageY, xmonth + "/" + xday + " - <b>" + item.series.symbol + y + "</b> " + item.series.label);
			}
		} else {
			jQuery("#charttooltip").remove();
			previousPoint = null;
		}
	});
});
// ]]>
</script>

<?php
}

function jr_radius_dropdown() {
	global $app_abbr;

?>
			<div class="radius">
				<label for="radius"><?php _e('Radius:', 'appthemes'); ?></label>
				<select name="radius" class="radius">
<?php
				$selected_radius = isset( $_GET['radius'] ) ? absint( $_GET['radius'] ) : 0;

				if ( !$selected_radius )
					$selected_radius = 50;

				foreach ( array( 1, 5, 10, 50, 100, 1000, 5000 ) as $radius ) {
?>
					<option value="<?php echo $radius; ?>" <?php selected( $selected_radius, $radius ); ?>><?php echo number_format_i18n( $radius ) . ' ' . get_option( $app_abbr.'_distance_unit' ) ?></option>
<?php
				}
?>
				</select>
			</div><!-- end radius -->
<?php
}

function jr_job_author() {
	global $post;

	$company_name = wptexturize(strip_tags(get_post_meta($post->ID, '_Company', true)));

	if ( $company_name ) {
		if ( $company_url = esc_url( get_post_meta( $post->ID, '_CompanyURL', true ) ) ) {
?>
			<a href="<?php echo $company_url; ?>" rel="nofollow"><?php echo $company_name; ?></a>
<?php
		} else {
			echo $company_name;
		}

		$format = __(' &ndash; Posted by <a href="%s">%s</a>', 'appthemes');
	} else {
		$format = '<a href="%s">%s</a>';
	}

	$author = get_user_by('id', $post->post_author);
	if ( $author && $link = get_author_posts_url( $author->ID, $author->user_nicename ) )
		echo sprintf( $format, $link, $author->display_name );
}

function jr_location( $with_comma = false ) {
	global $post;

	$address = get_post_meta($post->ID, 'geo_short_address', true);
	if ( !$address )
		$adress = __( 'Anywhere', 'appthemes' );

	echo "<strong>$address</strong>";

	$country = strip_tags(get_post_meta($post->ID, 'geo_short_address_country', true));

	if ( $country ) {
		if ( $with_comma )
			echo ', ';

		echo $country;
	}
}

