<?php
/**
 * AppThemes framework functions
 * This file is the backbone and includes all the core functions
 * Modifying this will void your warranty and could cause
 * problems with your instance. Proceed at your own risk!
 *
 * @link http://codex.appthemes.com
 *
 * @version 1.0
 * @author AppThemes
 *
  * DO NOT UPDATE WITHOUT UPDATING ALL OTHER THEMES!
 * This is a shared file so changes need to be propagated to insure sync 
 *
 */



// get the custom taxonomy array and return whatever arg is requested
// $tax_arg can pass in any arg such as name, slug, term_id
// complete list here: http://codex.wordpress.org/Function_Reference/get_terms
function appthemes_get_custom_taxonomy($post_id, $tax_name, $tax_arg) {
    $tax_array = get_terms( $tax_name, array( 'hide_empty' => '0' ) );
    if ($tax_array && sizeof($tax_array) > 0):
        foreach ($tax_array as $tax_val) {
            if ( is_object_in_term( $post_id, $tax_name, array( $tax_val->term_id ) ) ) {
                switch($tax_arg) {
                    case 'slug':
                        $link = get_term_link($tax_val, $tax_name);
                        if (is_wp_error($link))
                            return $link;
                        return $link;
                        break;
                    case 'name':
                        return $tax_val->name;
                        break;
                    case 'term_id':
                        return $tax_val->term_id;
                        break;
                } // end switch
            }
        }
    endif;
}


// return taxonomy name and url randomly
function appthemes_get_rand_taxonomy($tax_name, $the_limit){
    global $wpdb;

    $sql = "SELECT t.name, t.slug FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
    WHERE tt.taxonomy IN (%s) AND tt.count > 0 ORDER BY RAND() LIMIT $the_limit";
    $tax_array = $wpdb->get_results($wpdb->prepare($sql, $tax_name));

    if ($tax_array && sizeof($tax_array) > 0):
        foreach ( $tax_array as $tax_val ) {
            $link = get_term_link($tax_val->name, $tax_name);
            echo '<a class="tax-link" href="'.$link.'">'.$tax_val->name.'</a>';
        }
    endif;
}

// return taxonomy name and url by most popular
function appthemes_get_pop_taxonomy($tax_name, $the_limit){
    global $wpdb;

    $sql = "SELECT t.name, t.slug, tt.count FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
    WHERE tt.taxonomy IN (%s) AND tt.count > 0 GROUP BY tt.count DESC ORDER BY RAND() LIMIT $the_limit";
    $tax_array = $wpdb->get_results($wpdb->prepare($sql, $tax_name));

    if ($tax_array && sizeof($tax_array) > 0):
        foreach ( $tax_array as $tax_val ) {
            $link = get_term_link($tax_val->name, $tax_name);
            echo '<a class="tax-link" href="'.$link.'">'.$tax_val->name.'</a>';
        }
    endif;
}


// gets all the taxonomy values which can be returned with before, after, and separator values
function appthemes_get_all_taxonomy( $id = 0, $taxonomy, $before = '', $sep = '', $after = '' ) {
    $terms = get_the_terms( $id, $taxonomy );

    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    foreach ($terms as $term) {
        $link = get_term_link($term, $taxonomy);
        if (is_wp_error($link))
            return $link;
        $term_links[] = $term->name;
    }

    $term_links = apply_filters( "term_links-$taxonomy", $term_links );

    return $before . join( $sep, $term_links ) . $after;
}


// contains the reCaptcha anti-spam system. Called on reg pages
function appthemes_recaptcha() {
    global $app_abbr;

    // process the reCaptcha request if it's been enabled
    if (get_option($app_abbr.'_captcha_enable') == 'yes' && get_option($app_abbr.'_captcha_theme') && get_option($app_abbr.'_captcha_public_key')) :
?>
        <script type="text/javascript">
        // <![CDATA[
         var RecaptchaOptions = {
            custom_translations : {
                instructions_visual : "<?php _e('Type the two words:','appthemes') ?>",
                instructions_audio : "<?php _e('Type what you hear:','appthemes') ?>",
                play_again : "<?php _e('Play sound again','appthemes') ?>",
                cant_hear_this : "<?php _e('Download sound as MP3','appthemes') ?>",
                visual_challenge : "<?php _e('Visual challenge','appthemes') ?>",
                audio_challenge : "<?php _e('Audio challenge','appthemes') ?>",
                refresh_btn : "<?php _e('Get two new words','appthemes') ?>",
                help_btn : "<?php _e('Help','appthemes') ?>",
                incorrect_try_again : "<?php _e('Incorrect. Try again.','appthemes') ?>",
            },
            theme: "<?php echo get_option($app_abbr.'_captcha_theme') ?>",
            lang: "en",
            tabindex: 5
         };
        // ]]>
        </script>

        <p>
        <?php
        // let's call in the big boys. It's captcha time.
        require_once (TEMPLATEPATH . '/includes/lib/recaptchalib.php');
        echo recaptcha_get_html(get_option($app_abbr.'_captcha_public_key'));
        ?>
        </p>

<?php
    endif;  // end reCaptcha

}


// checks if a user is logged in, if not redirect them to the login page
function appthemes_auth_redirect_login() {
    $user = wp_get_current_user();
    if ( $user->id == 0 ) {
        nocache_headers();
        wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}


// get the actual time post was made
function appthemes_date_posted($m_time) {
    $time = get_post_time('G', true);
    $time_diff = time() - $time;

    if ($time_diff > 0 && $time_diff < 24*60*60)
        $h_time = sprintf(__('%s ago', 'appthemes'), human_time_diff($time));
    else
        $h_time = mysql2date(get_option('date_format'), $m_time);
    echo $h_time;
}


// returns the time as a unix timestamp. If $gmt=1, the time returned is GMT;
// if $gmt=0, the time returned is determined by WordPress option gmt_offset
// set as Timezone on General Settings page
function appthemes_current_time($type, $gmt = 0) {
	$t = ($gmt) ? gmdate('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s', (time() + (get_option('gmt_offset') * 3600)));
	switch ($type) {
		case 'mysql':
			return $t;
			break;
		case 'timestamp':
			return strtotime($t);
			break;
	}
}


// 336 x 280 ad box on single page
function appthemes_single_ad_336x280 () {
    global $app_abbr;

    if (get_option($app_abbr.'_adcode_336x280') <> '') {
        echo stripslashes(get_option($app_abbr.'_adcode_336x280'));
    } else {
        if (get_option($app_abbr.'_adcode_336x280_url') || !get_option($app_abbr.'_adcode_336x280_dest')) {
    ?>
        <a href="<?php echo get_option($app_abbr.'_adcode_336x280_dest') ?>" target="_blank"><img src="<?php echo get_option($app_abbr.'_adcode_336x280_url') ?>" alt="" border="0" /></a>
    <?php
        }
    }
}

// 468 x 60 ad box in header
function appthemes_header_ad_468x60 () {
	global $app_abbr;

	if (get_option($app_abbr.'_adcode_468x60') <> '') {
        echo stripslashes(get_option($app_abbr.'_adcode_468x60'));
    } else {
        if (!get_option($app_abbr.'_adcode_468x60_url') || !get_option($app_abbr.'_adcode_468x60_dest')) {
        ?>
            <a href="http://appthemes.com" target="_blank"><img class="" src="<?php echo bloginfo("template_directory") ?>/images/468x60-banner.jpg" border="0" width="468" height="60" alt="Premium WordPress Themes - AppThemes" /></a>
       <?php } else { ?>
            <a href="<?php echo get_option($app_abbr.'_adcode_468x60_dest') ?>" target="_blank"><img src="<?php echo get_option($app_abbr.'_adcode_468x60_url') ?>" alt="" border="0" /></a>
        <?php
        }
    }
}


// get the page view counters and display on the page
function appthemes_get_stats($post_id) {
	global $posts, $app_abbr;

	$daily_views = get_post_meta($post_id, $app_abbr.'_daily_count', true);
	$total_views = get_post_meta($post_id, $app_abbr.'_total_count', true);

	if(!empty($total_views) && (!empty($daily_views)))
		echo number_format($total_views) . '&nbsp;' . __('total views', 'appthemes'). ',&nbsp;' . number_format($daily_views) . '&nbsp;' . __('today', 'appthemes');
	else
		echo __('no views yet', 'appthemes');
}


// get the visitors IP for security tracking
function appthemes_get_ip() {
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


// tinyMCE text editor
function appthemes_tinymce($width=540, $height=400) {
?>
<script type="text/javascript">
    <!--
    tinyMCE.init({
        mode : "exact",
        theme : "advanced",
        skin : "default",
        plugins : "media",
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
        // update validation status on change hack to work with jquery validation
        onchange_callback: function(editor) {
          tinyMCE.triggerSave();
          $("#" + editor.id).valid();
        }
    });
    -->
</script>

<?php
}


// give us either the uploaded profile pic, a gravatar, or a placeholder
function appthemes_get_profile_pic($author_id, $author_email, $avatar_size) {
//    if(function_exists('userphoto_exists')) {
//        if(userphoto_exists($author_id))
//			//if the size of userphoto called is less then 32px, it must be looking for the thumbnail
//			if($avatar_size <= 32)
//            	userphoto_thumbnail($author_id);
//			else
//				userphoto($author_id);
//        else
//            echo get_avatar($author_email, $avatar_size);
//      } else {
         echo get_avatar($author_email, $avatar_size);
//     }
}


// change the author url base permalink
// not using quite yet. need to
function appthemes_author_permalink() {
    global $wp_rewrite, $app_abbr;

	$author_base = trim(get_option($app_abbr.'_author_url'));

	// don't waste resources if the author base hasn't been customized
	// MAKE SURE TO CHECK IF VAR IS EMPTY OTHERWISE THINGS WILL BREAK
	if($author_base <> 'author') {
		$wp_rewrite->author_base = $author_base;
		$wp_rewrite->flush_rules();
	}
}

// don't load on admin pages
// if(!is_admin())
	// add_action('init', 'appthemes_author_permalink');

// returns a total count of all posts based on status and post type
// leave post type empty for blog posts count otherwise pass in custom post type 
function appthemes_count_posts($post_type, $status_type='publish') {
	$count_posts = wp_count_posts($post_type);
	$count_total = $count_posts->$status_type;
	number_format($count_total);
	return $count_total;
}	
	


/**
 *
 * Helper functions
 *
 */

// round to the nearest value used in pagination
function appthemes_round($num, $tonearest) {
   return floor($num/$tonearest)*$tonearest;
}


// checks whether string begins with given string.
// i.e. if (string_starts_with($host, 'http://localhost/')) { //do stuff }
function appthemes_str_starts_with($string, $search) {
    return (strncmp($string, $search, strlen($search)) == 0);
}


// strip out everything except for numbers
function appthemes_numbers_only($string) {
    $string = preg_replace('/[^0-9]/', '', $string);
    return $string;
}


// strip out everything except for numbers
function appthemes_letters_only($string) {
    $string = preg_replace('/[^a-z]/i', '', $string);
    return $string;
}


// strip out everything except numbers and letters
function appthemes_numbers_letters_only($string) {
    $string = preg_replace('/[^a-z0-9]/i', '', $string);
    return $string;
}


// for the price field to make only numbers, periods, and commas
function appthemes_clean_price($string) {
    $string = preg_replace('/[^0-9.,]/', '', $string);
    return $string;
}


// for the tags field to remove any invalid characters
function appthemes_clean_tags($string) {
    $string = preg_replace('/\s*,\s*/', ',', rtrim(trim($string), ' ,'));
    return $string;
}


// pass strings in to clean
function appthemes_clean($string) {
    $string = stripslashes($string);
    $string = trim($string);
    return $string;
}


// strip tags and limit characters to 5,000
function appthemes_filter($text) {
    $text = strip_tags($text);
    $text = trim($text);
    $char_limit = 5000;
    if( strlen($text) > $char_limit ) {
        $text = substr($text, 0, $char_limit);
    }
    return $text;
}


//This function separates the extension from the rest of the file name and returns it
function appthemes_find_ext ($filename) {
    $filename = strtolower($filename);
    $exts = split("[/\\.]", $filename);
    $n = count($exts)-1;
    $exts = $exts[$n];
    return $exts;
}


// error message output function
function appthemes_error_msg($error_msg) {
    $msg_string = '';
    foreach ($error_msg as $value) {
        if(!empty($value))
            $msg_string = $msg_string . '<div class="error">' . $msg_string = $value.'</div><div class="pad5"></div>';
    }
    return $msg_string;
}


// replace all \n with just <br />
function appthemes_nl2br($text) {
   return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
}


// check to see if the blog is using WPMU
function appthemes_is_wpmu() {
    if (strpos(get_option('upload_path'), 'blogs.dir') !== false)
    return true;
}


// RSS blog feed for the dashboard page
function appthemes_dashboard_appthemes() {
    global $app_rss_feed;
    wp_widget_rss_output($app_rss_feed, array('items' => 10, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1));
}


// RSS twitter feed for the dashboard page
function appthemes_dashboard_twitter() {
    global $app_twitter_rss_feed;
    wp_widget_rss_output($app_twitter_rss_feed, array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 0));
}


// RSS forum feed for the dashboard page
function appthemes_dashboard_forum() {
    global $app_forum_rss_feed;
    wp_widget_rss_output($app_forum_rss_feed, array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1));
}


// just places the search term into a js variable for use with jquery
// not being used as of 3.0.5 b/c of js conflict with search results
function appthemes_highlight_search_term($query) {
	if(is_search() && strlen($query) > 0){
    echo '
      <script type="text/javascript">
        var search_query  = "'.$query.'";
      </script>
    ';
  }

}


// insert the first login date once the user has been created
function appthemes_first_login($user_id) {
    update_usermeta($user_id, 'last_login', gmdate('Y-m-d H:i:s'));
}


// insert the last login date for each user
function appthemes_last_login($login) {
    global $user_ID;
    $user = get_userdatabylogin($login);
    update_usermeta($user->ID, 'last_login', gmdate('Y-m-d H:i:s'));
}
add_action('wp_login','appthemes_last_login');


// get the last login date for a user
function appthemes_get_last_login($user_id) {
    $last_login = get_user_meta($user_id, 'last_login', true);
    $date_format = get_option('date_format') . ' ' . get_option('time_format');
    $the_last_login = mysql2date($date_format, $last_login, false);
    echo $the_last_login;
}


// format the user registration date used in the sidebar-user.php template
function appthemes_get_reg_date($reg_date) {
    $date_format = get_option('date_format') . ' ' . get_option('time_format');
    $the_reg_date = mysql2date($date_format, $reg_date, false);
    echo $the_reg_date;
}


// helper function used by appthemes_make_clickable to make email string a link
function appthemes_make_email_clickable($matches) {
    $email = $matches[2] . '@' . $matches[3];
    return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
}


// helper function used by appthemes_make_clickable to make http string a link
function appthemes_make_url_clickable($matches) {
    $url = $matches[2];
    $url = esc_url($url);
    if (empty($url))
		return $matches[0];
    return $matches[1] . "<a target=\"_blank\" href=\"$url\" rel=\"nofollow\">$url</a>";
}


// looks for any http or email address and automatically hyperlinks it
function appthemes_make_clickable($ret) {
    $ret = ' ' . $ret;
	// first match on the url
    $ret = preg_replace_callback('#(?<=[\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#$%&~/=?@\[\](+-]|[.,;:](?![\s<]|(\))?([\s]|$))|(?(1)\)(?![\s<.,;:]|$)|\)))+)#is', 'appthemes_make_url_clickable', $ret);
    // next match on the email address
	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', 'appthemes_make_email_clickable', $ret);
    // this one is not in an array because we need it to run last, for cleanup of accidental links within links
    $ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    $ret = trim($ret);
    return $ret;
}


// add or remove upload file types
function appthemes_custom_upload_mimes ($existing_mimes=array()) {

// add your ext =&gt; mime to the array
    //$existing_mimes['extension'] = 'mime/type';

    //unset( $existing_mimes['exe'] );

    return $existing_mimes;
}
// add_filter('upload_mimes', 'appthemes_custom_upload_mimes');


// suggest terms on search results
// based off the Search Suggest plugin by Joost de Valk
function appthemes_search_suggest($full = true) {
    global $yahooappid, $s;

    require_once(ABSPATH . 'wp-includes/class-snoopy.php');
    $yahooappid = '3uiRXEzV34EzyTK7mz8RgdQABoMFswanQj_7q15.wFx_N4fv8_RPdxkD5cn89qc-';
    $query 	= "http://search.yahooapis.com/WebSearchService/V1/spellingSuggestion?appid=$yahooappid&query=".$s."&output=php";
    $wpurl 	= get_bloginfo('wpurl');
    $snoopy = new Snoopy;

    $snoopy->fetch($query);
    $resultset = unserialize($snoopy->results);
    if (isset($resultset['ResultSet']['Result'])) {
        if (is_string($resultset['ResultSet']['Result'])) {
            $output = '<a href="'.$wpurl.'?s='.urlencode($resultset['ResultSet']['Result']).'" rel="nofollow">'.$resultset['ResultSet']['Result'].'</a>';
        } else {
            foreach ($resultset['ResultSet']['Result'] as $result) {
                $output .= '<a href="'.$wpurl.'?s='.urlencode($result).'" rel="nofollow">'.$result.'</a>, ';
            }
        }
        if ($full) {
            echo __('Perhaps you meant', 'appthemes').'<strong> '.$output.'</strong>?';
        } else {
            return __('Perhaps you meant', 'appthemes').'<strong> '.$output.'</strong>?';
        }
    } else {
        return false;
    }
}


// slimmed down non-plugin version of WP-PageNavi (2.50) by Lester 'GaMerZ' Chan http://lesterchan.net
function appthemes_pagination($before = '', $after = '') {
    global $wpdb, $wp_query;

    if (!is_single()) :

        $pagenavi_options = array(
                'pages_text' => __('Page %CURRENT_PAGE% of %TOTAL_PAGES%','appthemes'),
                'current_text' => '%PAGE_NUMBER%',
                'page_text' => '%PAGE_NUMBER%',
                'first_text' => __('&lsaquo;&lsaquo; First','appthemes'),
                'last_text' => __('Last &rsaquo;&rsaquo;','appthemes'),
                'next_text' => '&rsaquo;&rsaquo;',
                'prev_text' => '&lsaquo;&lsaquo;',
                'dotright_text' => '',
                'dotleft_text' => '',
                'style' => 1,
                'num_pages' => 15,
                'always_show' => 0,
                'num_larger_page_numbers' => 3,
                'larger_page_numbers_multiple' => 10,
        );

        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;

        if(empty($paged) || $paged == 0) $paged = 1;

        $pages_to_show = intval($pagenavi_options['num_pages']);
        $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
        $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1/2);
        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;

        if($start_page <= 0) $start_page = 1;

        $end_page = $paged + $half_page_end;

        if(($end_page - $start_page) != $pages_to_show_minus_1) $end_page = $start_page + $pages_to_show_minus_1;

        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }

        if($start_page <= 0) $start_page = 1;

        $larger_per_page = $larger_page_to_show*$larger_page_multiple;
        $larger_start_page_start = (appthemes_round($start_page, 10) + $larger_page_multiple) - $larger_per_page;
        $larger_start_page_end = appthemes_round($start_page, 10) + $larger_page_multiple;
        $larger_end_page_start = appthemes_round($end_page, 10) + $larger_page_multiple;
        $larger_end_page_end = appthemes_round($end_page, 10) + ($larger_per_page);

        if($larger_start_page_end - $larger_page_multiple == $start_page) {
            $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
            $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
        }

        if($larger_start_page_start <= 0) $larger_start_page_start = $larger_page_multiple;
        if($larger_start_page_end > $max_page) $larger_start_page_end = $max_page;
        if($larger_end_page_end > $max_page) $larger_end_page_end = $max_page;

        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) :
            $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
            $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);

            echo $before.'<div class="paging">'."\n";

			if(!empty($pages_text)) echo '<div class="pages"><span class="total">'.$pages_text.'</span>';

			if ($start_page >= 2 && $pages_to_show < $max_page) :
				$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
				echo '<a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'">'.$first_page_text.'</a>';

				if(!empty($pagenavi_options['dotleft_text']))
					echo '<span class="extend">'.$pagenavi_options['dotleft_text'].'</span>';
			endif;

			if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) :
				for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
				}
			endif;

			echo '<span class="prev">';
			// give us the previous post link
			previous_posts_link($pagenavi_options['prev_text']);
			echo '</span>';

			for($i = $start_page; $i  <= $end_page; $i++) :
				if($i == $paged) {
					$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
					echo '<span class="current">'.$current_page_text.'</span>';
				} else {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
				}
			endfor;

			echo '<span class="next">';
			// give us the next post link
			next_posts_link($pagenavi_options['next_text'], $max_page);
			echo '</span>';

			if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) :
				for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
					$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
					echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="page" title="'.$page_text.'">'.$page_text.'</a>';
				}
			endif;

			if ($end_page < $max_page) :
				if(!empty($pagenavi_options['dotright_text']))
					echo '<span class="extend">'.$pagenavi_options['dotright_text'].'</span>';

				$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
				echo '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$last_page_text.'</a>';
			endif;

            echo '</div><div class="clr"></div></div>'.$after."\n";

        endif;

    endif;
}


// deletes all the theme database tables
function appthemes_delete_db_tables() {
    global $wpdb, $app_db_tables;

	echo '<div class="update-msg">';
    foreach ($app_db_tables as $key => $value) :

        $sql = "DROP TABLE IF EXISTS ". $wpdb->prefix . $value;
        $wpdb->query($sql);

        printf('<div class="delete-item">'.__("Table '%s' has been deleted.", 'appthemes'). '</div>', $value);

    endforeach;
	echo '</div';
	
}

// deletes all the theme database options
function appthemes_delete_all_options() {
    global $wpdb, $app_abbr;

    $sql = "DELETE FROM ". $wpdb->options
         ." WHERE option_name LIKE '".$app_abbr."_%'";
	$wpdb->query($sql);

	echo '<div class="update-msg">';
    echo '<div class="delete-item">'. __('All theme options have been deleted.', 'appthemes'). '</div>';
	echo '</div';
}


// check for the latest version number from appthemes.com
function appthemes_get_latest_version() {
    global $app_version, $app_theme, $current_tag, $xml_title_key, $xml_version_key, $counter, $story_array;

    $file  = 'http://www.appthemes.com/xml/versions.xml';
    $xml_title_key = '*THEMES*THEME*TITLE';
    $xml_version_key = '*THEMES*THEME*VERSION';
    $story_array  = array();
    $counter = 0;

    class xml_story {
        var $title, $version;
    }

    function xml_contents($parser, $data){
        global $current_tag, $xml_title_key, $xml_version_key, $counter, $story_array;

        switch($current_tag){
            case $xml_title_key:
                $story_array[$counter] = new xml_story();
                $story_array[$counter]->title = $data;
                break;
            case $xml_version_key:
                $story_array[$counter]->version = $data;
                $counter++;
                break;
        }
    }

    function startTag($parser, $data){
        global $current_tag;
        $current_tag .= "*$data";
    }

    function endTag($parser, $data){
        global $current_tag;
        $tag_key = strrpos($current_tag, '*');
        $current_tag = substr($current_tag, 0, $tag_key);
    }

    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, 'startTag', 'endTag');
    xml_set_character_data_handler($xml_parser, 'xml_contents');

    // Open the XML file for reading
    $fp = fopen($file, 'r')
            or die('Error reading AppThemes XML versions file.');

    // Read the XML file 4KB at a time
    $data = fread($fp, 4096);

    if(!(xml_parse($xml_parser, $data, feof($fp)))){
        die(sprintf("AppThemes XML version file fetch error: %s at line %d",
           xml_error_string(xml_get_error_code($xml_parser)),
           xml_get_current_line_number($xml_parser)));
    }

    xml_parser_free($xml_parser);
    fclose($fp);

    // print_r($story_array);

    for($x=0;$x<count($story_array);$x++){
        if ($story_array[$x]->title == strtolower($app_theme))
            $latest_version = trim($story_array[$x]->version);
    }

    if (strcmp($app_version, $latest_version) == 0)
        return '0'; // no update
    else
        return '1'; // new version available


}



// add a weekly option for the cron scheduler since it doesn't come included by default
function appthemes_add_weekly_cron( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 604800, //that's how many seconds in a week, for the unix timestamp
		'display' => __('Once Weekly')
	);
	return $schedules;
}
add_filter('cron_schedules', 'appthemes_add_weekly_cron');




function appthemes_update_checks() {
	global $app_abbr, $app_version, $app_theme, $wp_version, $wpdb, $wp_local_package;
	
	$theme_name = trim(strtolower($app_theme));	
	$php_version = phpversion();

	$current = get_site_transient( $theme_name.'_update_theme' );
	
	if ( ! is_object($current) ) {
		$current = new stdClass;
		$current->updates = array();
		$current->version_checked = $app_version;
	}

	$locale = apply_filters( 'core_version_check_locale', get_locale() );

	// Update last_checked for current to prevent multiple blocking requests if request hangs
	$current->last_checked = time();
	set_site_transient( $theme_name.'_update_theme', $current );

	if ( method_exists( $wpdb, 'db_version' ) )
		$mysql_version = preg_replace('/[^0-9.].*/', '', $wpdb->db_version());
	else
		$mysql_version = 'N/A';

	if ( is_multisite( ) ) {
		$user_count = get_user_count( );
		$num_blogs = get_blog_count( );
		$wp_install = network_site_url( );
		$multisite_enabled = 1;
	} else {
		$user_count = count_users( );
		$multisite_enabled = 0;
		$num_blogs = 1;
		$wp_install = home_url( '/' );
	}

	$local_package = isset( $wp_local_package )? $wp_local_package : '';
	$url = "http://api.appthemes.com/vcheck/?theme_name=$theme_name&theme_version=$app_version&wp_version=$wp_version&php=$php_version&locale=$locale&mysql=$mysql_version&local_package=$local_package&blogs=$num_blogs&users={$user_count['total_users']}&multisite_enabled=$multisite_enabled";

	$options = array(
		'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
		'user-agent' => $app_theme . '/' . $app_version . '; ' . home_url( '/' ),
		'headers' => array(
			'wp_install' => $wp_install,
			'wp_blog' => home_url( '/' )
		)
	);

	$response = wp_remote_get($url, $options);

	if ( is_wp_error( $response ) )
		return false;

	if ( 200 != $response['response']['code'] )
		return false;

	$body = trim( $response['body'] );
	$body = str_replace(array("\r\n", "\r"), "\n", $body);
	$returns = array();
	foreach ( explode( "\n\n", $body ) as $entry ) {
		$returns = explode("\n", $entry);
		$new_option = new stdClass();
		$new_option->latest_version = esc_attr( $returns[0] );
	}

	$updates = new stdClass();
	//$updates->updates = $new_options;
	$updates->last_checked = time();
	$updates->version_checked = $app_version;
	$updates->latest_version = $new_option->latest_version;
	set_site_transient( $theme_name.'_update_theme',  $updates);
}
add_action('appthemes_update_check', 'appthemes_update_checks');



function appthemes_update_nag() {
	global $app_version, $app_theme;
	
	if ( !current_user_can('update_core') )
		return false;
		
	$theme_name = trim(strtolower($app_theme));

	$cur = get_site_transient( $theme_name.'_update_theme' );
	
	if ( empty($cur) )
		return false;
	
	//echo 'Installed: '. $app_theme .' v'.$cur->version_checked .'. Latest version: ' . $cur->latest_version . "\n";	

	if ( version_compare($cur->version_checked, $cur->latest_version, '>=' ) )	// 1.0 to 2.0		
		return false;	

	if ( current_user_can('update_core') ) {
		$msg = sprintf( __('%1$s version %2$s is now available! Customers with a current subscription may <a href="%3$s" target="_blank">download it now</a>.'), $app_theme, $cur->latest_version, 'http://www.appthemes.com/cp/member.php' );
	} else {
		$msg = sprintf( __('%1$s version %2$s is now available! Please notify the site administrator.'), $app_theme, $cur->latest_version );
	}
	echo "<div class='update-nag'>$msg</div>";
}
add_action( 'admin_notices', 'appthemes_update_nag', 4 );


function appthemes_schedule_update_checks() {
	if (!wp_next_scheduled('appthemes_update_check'))
		wp_schedule_event(time(), 'weekly', 'appthemes_update_check');
}

// uncomment for this check to start working
add_action('init', 'appthemes_schedule_update_checks');

// uncomment to delete any scheduled version check tasks
//wp_clear_scheduled_hook('appthemes_update_check');


?>