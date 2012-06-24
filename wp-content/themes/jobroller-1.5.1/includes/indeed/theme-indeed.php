<?php
/**
 * Indeed Integration
 * Dynamic results from indeed
 *
 *
 * @version 1.2
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */
define('INDEED_VERSION', 2);
define('INDEED_SORT', 'date');

// Map Job Types Taxonomy
define('INDEED_FULLTIME', 'full-time');
define('INDEED_PARTTIME', 'part-time');
define('INDEED_CONTRACT', 'freelance');
define('INDEED_TEMP', 'temporary');
define('INDEED_INTERN', 'internship');

// Get jobs
function jr_get_indeed_jobs( $limit, $start = 0, $keyword = '', $country = 'US', $job_type = 'fulltime', $location_query = '' ) {
	
	$jr_indeed_publisher_id = get_option('jr_indeed_publisher_id');
	
	if(isset($_SERVER['HTTP_X_FORWARD_FOR'])) $ip = $_SERVER['HTTP_X_FORWARD_FOR']; else $ip = $_SERVER['REMOTE_ADDR'];
	$useragent = urlencode($_SERVER['HTTP_USER_AGENT']);
	
	if (!$jr_indeed_publisher_id) return false;
		
	$country = strtolower($country);

	$indeed_result = @wp_remote_get('http://api.indeed.com/ads/apisearch?v='.INDEED_VERSION.'&publisher='.trim($jr_indeed_publisher_id).'&sort='.INDEED_SORT.'&limit='.$limit.'&co='.$country.'&jt='.$job_type.'&q='.$keyword.$location_query.'&userip='.$ip.'&useragent='.$useragent . '&start=' . $start);
	
	if (is_wp_error($indeed_result)) return false;
	
	return $indeed_result['body'];
}

// Query indeed
function jr_query_search_results( $queries, $limit, $start ) {
	
	$xml_limit = ceil($limit / sizeof($queries));
	
	$search_results = array();
	
	foreach ($queries as $query) :	
	
		$indeed_result = jr_get_indeed_jobs( $xml_limit, $start, $query['keyword'], $query['country'], $query['job_type'], $query['location'] );
		
		if (!$indeed_result) continue;
				
		$xml = new SimpleXMLElement($indeed_result);
		
		$xmlresults = $xml->results;
		
		foreach($xmlresults->result as $result) :				
		
			$x = array();
	
			if (isset($result->jobkey)) 	$x['jobkey'] 	= (string) $result->jobkey;
			if (isset($result->jobtitle)) 	$x['jobtitle'] 	= (string) $result->jobtitle;
			if (isset($result->company)) 	$x['company'] 	= (string) $result->company;
			if (isset($result->url)) 		$x['url'] 		= (string) $result->url;
			if (isset($result->formattedLocation)) $x['formattedLocation'] 	= (string) $result->formattedLocation;
			if (isset($result->country)) 	$x['country'] 	= (string) $result->country;
			if (isset($result->onmousedown)) $x['onmousedown'] 	= (string) $result->onmousedown;
			if (isset($result->date)) $x['date'] 	= (string) $result->date;
			
			switch ($query['job_type']) :
				case "fulltime" :
					$x['type'] 	= INDEED_FULLTIME;
				break;
				case "parttime" :
					$x['type'] 	= INDEED_PARTTIME;
				break;
				case "contract" :
					$x['type'] 	= INDEED_CONTRACT;
				break;
				case "temporary" :
					$x['type'] 	= INDEED_TEMP;
				break;
				case "internship" :
					$x['type'] 	= INDEED_INTERN;
				break;
			endswitch;
							
			$x = array_map('trim', $x);
			
			$search_results[] = $x;
							
		endforeach;
	
	endforeach;
						
	if (sizeof($search_results)==0) return;
	
	$search_results = array_slice($search_results, 0, $limit);
	
	shuffle($search_results);
	
	return $search_results;
}

// AJAX get more indeed results
add_action('wp_ajax_get_more_indeed_results', 'jr_get_more_indeed_results');
add_action('wp_ajax_nopriv_get_more_indeed_results', 'jr_get_more_indeed_results');

function jr_get_more_indeed_results() {

	check_ajax_referer( 'get-indeed', 'security' );
	
	$page = (isset($_POST['page'])) ? $_POST['page'] : 1;
	
	if (isset($_POST['load']) && $_POST['load']=='search') {
		
		jr_indeed_search_results( true, $page );
		
	} else {
		
		jr_indeed_results( true, $page );
		
	}

	die();
	
}


// Front page
add_action('before_front_page_jobs', 'jr_indeed_results');

function jr_indeed_results( $is_ajax = false, $page = 1 ) {

	if ( get_query_var('paged') ) $paged = get_query_var('paged');
	elseif ( get_query_var('page') ) $paged = get_query_var('page');
	else $paged = 1;
    
	if (get_option('jr_indeed_front_page')=='no' || $paged>1) return;
	
	wp_reset_query();

	$limit = (get_option('jr_indeed_front_page_count')) ? get_option('jr_indeed_front_page_count') : 5;
	$start = ($page>1) ? $limit * ($page-1) : 0;

	$queries = array();
	$search_results = array();
	
	$jr_indeed_queries = explode("\n", get_option('jr_front_page_indeed_queries'));
	
	if (sizeof($jr_indeed_queries)>0) foreach ($jr_indeed_queries as $query_row) : 

		$query = explode('|', $query_row);
		
		if (sizeof($query)>2) :
			$keyword = urlencode(strtolower(trim($query[0])));
			$country = trim($query[1]);
			$job_type = trim($query[2]);
			if (isset($query[3])) $job_loc = '&l='.urlencode($query[3]); else $job_loc = '';
			
			$queries[] = array(
				'keyword' => $keyword,
				'country' => $country,
				'job_type' => $job_type,
				'location' => $job_loc
			);
		endif;
	
	endforeach;
	
	if (sizeof($queries)==0) :
		// Use default
		$queries[] = array(
			'keyword' => '',
			'country' => 'us',
			'job_type' => 'fulltime',
			'location' => ''
		);
		$queries[] = array(
			'keyword' => '',
			'country' => 'us',
			'job_type' => 'parttime',
			'location' => ''
		);
	endif;
	
	$search_results = jr_query_search_results( $queries, $limit, $start );
	
	if (sizeof($search_results)==0) return;
	
	$alt = 1;
	$first = true;
	
	if (!$is_ajax) :
		echo '<div class="section"><h2 class="pagetitle">' . __('Sponsored Job Listings', 'appthemes') . '</h2>';
		echo '<script type="text/javascript" src="http://www.indeed.com/ads/apiresults.js"></script>';
   		echo '<ol class="jobs indeed_results">';
	endif;

	foreach ($search_results as $x) :
		
		$post_class = array('job');
		if ($alt==1) $post_class[] = 'job-alt';
		
		?>
		<li class="<?php echo implode(' ', $post_class); ?>" <?php if ($is_ajax && $first) echo 'id="more-'.$page.'"'; ?>><dl>
            
            <dt><?php _e('Type','appthemes'); ?></dt>
            <dd class="type"><span class="ftype <?php echo $x['type']; ?>"><?php echo ucwords(str_replace('-', ' ', $x['type'])); ?></span></dd>
            
            <dt><?php _e('Job', 'appthemes'); ?></dt>
            <dd class="title">
				<strong><a href="<?php echo $x['url']; ?>" onmousedown="<?php echo $x['onmousedown']; ?>" target="_blank" rel="nofollow"><?php echo $x['jobtitle']; ?></a></strong>
				<?php echo wptexturize($x['company']); ?>
            </dd>

            <dt><?php _e('Location', 'appthemes'); ?></dt>
            <dd class="location"><strong><?php echo $x['formattedLocation']; ?></strong> <?php echo $x['country']; ?></dd>

            <dt><?php _e('Date Posted', 'appthemes'); ?></dt>
            <dd class="date"><strong><?php echo date_i18n('j M', strtotime($x['date'])); ?></strong> <span class="year"><?php echo date_i18n('Y', strtotime($x['date'])); ?></span></dd>
            
        </dl></li>
		<?php
		
		$first = false;
		
	endforeach;
	
	if (!$is_ajax) :
		
		$link_class = array('more_indeed_results', 'front_page');
		
		echo '</ol>
		<div class="paging indeed_paging">
	        <div style="float:left;"><a href="#more" class="'.implode(' ', $link_class).'" rel="2">Load More &raquo;</a></div>
			<p class="attribution"><a href="http://www.indeed.com/">jobs</a> by <a href="http://www.indeed.com/" title="Job Search"><img src="http://www.indeed.com/p/jobsearch.gif" alt="Indeed job search" /></a></p>
	    </div></div>';
    endif;
	
}

// Search results indeed search
if (get_option('jr_dynamic_search_results')=='yes') :
	add_action('after_search_results', 'jr_indeed_search_results');
elseif (get_option('jr_dynamic_search_results')=='noresults') :
	add_action('appthemes_job_listing_loop_else', 'jr_indeed_search_results');
endif;

function jr_indeed_search_results( $is_ajax = false, $page = 1 ) {
	
	wp_reset_query();
	
	if (!$is_ajax && !is_search()) return; 
	
	$keyword = urlencode(strtolower(trim(get_search_query())));
	$limit = get_option('posts_per_page');

	$start = ($page>1) ? $limit * ($page-1) : 0;

	if(isset($_SERVER['HTTP_X_FORWARD_FOR'])) $ip = $_SERVER['HTTP_X_FORWARD_FOR']; else $ip = $_SERVER['REMOTE_ADDR'];
	
	// Location
	$location_query = '';
	$country = '';
	$jr_gmaps_lang = get_option('jr_gmaps_lang');
	$jr_gmaps_region = get_option('jr_gmaps_region');
	if (isset($_GET['location'])) $location = trim($_GET['location']); else $location = '';
	if (isset($_GET['radius']) && $_GET['radius']>0) $radius = trim($_GET['radius']); else $radius = 50;
	
	if ($location) :
		
		$address = "http://maps.google.com/maps/geo?q=".urlencode($location)."&output=json&language=".$jr_gmaps_lang."&region=".$jr_gmaps_region."";
		
		$cached = wp_cache_get( 'geo_'.urlencode($location) );
		
		if ($cached) :
			$address = $cached;
		else :
			$address = json_decode((file_get_contents($address)), true);
			if (is_array($address)) wp_cache_set( 'geo_'.urlencode($location) , $address ); 
		endif;

	   	if (is_array($address)) :
	   		
	   		if (isset($address['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'])) $country = $address['Placemark'][0]['AddressDetails']['Country']['CountryNameCode'];
	   				   		
	   	endif;
	   	
	   	$location_query = '&l='.urlencode($location);
	
	else :
		// Get user country to search instead
		$country = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$result = wp_remote_get('http://api.hostip.info/country.php?ip='.$ip);				
		if (!is_wp_error($result)) $country = $result['body'];
	endif;
	
	$queries = array();
	
	$queries[] = array(
		'keyword' => $keyword,
		'country' => $country,
		'job_type' => 'fulltime',
		'location' => $location_query
	);
	$queries[] = array(
		'keyword' => $keyword,
		'country' => $country,
		'job_type' => 'parttime',
		'location' => $location_query
	);
	
	$search_results = jr_query_search_results( $queries, $limit, $start );
								
	if (sizeof($search_results)==0) return;

	$alt = 1;
	$first = true;
	
	if (!$is_ajax) :
		echo '<div class="section"><h2 class="pagetitle">' . sprintf( __('Sponsored Search Results for &ldquo;%s&rdquo;', 'appthemes'), get_search_query() ) . '</h2>';
	echo '<script type="text/javascript" src="http://www.indeed.com/ads/apiresults.js"></script>';
    	echo '<ol class="jobs indeed_results">';
	endif;
	
	foreach ($search_results as $x) :
		
		$post_class = array('job');
		if ($alt==1) $post_class[] = 'job-alt';
		
		?>
		<li class="<?php echo implode(' ', $post_class); ?>" <?php if ($is_ajax && $first) echo 'id="more-'.$page.'"'; ?>><dl>
            
            <dt><?php _e('Type','appthemes'); ?></dt>
            <dd class="type"><span class="ftype <?php echo $x['type']; ?>"><?php echo ucwords(str_replace('-', ' ', $x['type'])); ?></span></dd>
            
            <dt><?php _e('Job', 'appthemes'); ?></dt>
            <dd class="title">
				<strong><a href="<?php echo $x['url']; ?>" onmousedown="<?php echo $x['onmousedown']; ?>" target="_blank" rel="nofollow"><?php echo $x['jobtitle']; ?></a></strong>
				<?php echo wptexturize($x['company']); ?>
            </dd>

            <dt><?php _e('Location', 'appthemes'); ?></dt>
            <dd class="location"><strong><?php echo $x['formattedLocation']; ?></strong> <?php echo $x['country']; ?></dd>

            <dt><?php _e('Date Posted', 'appthemes'); ?></dt>
            <dd class="date"><strong><?php echo date_i18n('j M', strtotime($x['date'])); ?></strong> <span class="year"><?php echo date_i18n('Y', strtotime($x['date'])); ?></span></dd>
            
        </dl></li>
		<?php
		
		$first = false;
		
	endforeach;

	if (!$is_ajax) :
		
		$link_class = array('more_indeed_results', 'search_page');
		
		echo '</ol>
		<div class="paging indeed_paging">
	        <div style="float:left;"><a href="#more" class="'.implode(' ', $link_class).'" rel="2">Load More &raquo;</a></div>
			<p class="attribution"><a href="http://www.indeed.com/">jobs</a> by <a href="http://www.indeed.com/" title="Job Search"><img src="http://www.indeed.com/p/jobsearch.gif" alt="Indeed job search" /></a></p>
	    </div></div>';
    endif;
	
}

