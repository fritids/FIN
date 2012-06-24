<?php
/**
 *
 * Keeps track of views for daily and total
 * @author AppThemes
 *
 *
 */

global $app_abbr, $nowisnow, $table_name, $table_name_all;

// define the table names used
$table_name = $wpdb->prefix . $app_abbr. '_counter_daily';
$table_name_all = $wpdb->prefix . $app_abbr .'_counter_total';

// get the local time based off WordPress setting
$nowisnow = date('Y-m-d', appthemes_current_time('timestamp',0));



// get the total page views and daily page views for the post
function appthemes_stats_counter($post_id) {
	global $wpdb, $table_name, $table_name_all, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare("SELECT t.postcount AS total, d.postcount AS today FROM $table_name_all AS t
			INNER JOIN $table_name AS d ON t.postnum = d.postnum
			WHERE t.postnum = %d AND d.time = %s", $post_id, $nowisnow);

	$results = $wpdb->get_row($sql);

	// get the stats and
	if($results)
		echo number_format($results->total) . '&nbsp;' .__('total views', 'appthemes') . ', ' . number_format($results->today) . '&nbsp;' .__('today', 'appthemes');
	else
		echo __('No views yet', 'appthemes');
}



// record the page view
function appthemes_stats_update($post_id) {
	global $wpdb, $app_abbr, $table_name, $table_name_all, $nowisnow;
	
	$thepost = get_post($post_id);
	
	if ($thepost->post_author==get_current_user_id()) return;

	// first try and update the existing total post counter
	$results = $wpdb->query( $wpdb->prepare("UPDATE $table_name_all SET postcount = postcount+1 WHERE postnum = %s LIMIT 1", $post_id) );

	// if it doesn't exist, then insert two new records
	// one in the total views, another in today's views
	if ($results == 0) {
		$wpdb->insert($table_name_all, array(
			"postnum" => $post_id,
			"postcount" => 1
		));
		$wpdb->insert($table_name, array(
			"time" => $nowisnow,
			"postnum" => $post_id,
			"postcount" => 1
		));
	// post exists so let's just update the counter
	} else {
		$results2 = $wpdb->query( $wpdb->prepare("UPDATE $table_name SET postcount = postcount+1 WHERE time = %s AND postnum = %s' LIMIT 1", $nowisnow, $post_id) );

		// insert a new record since one hasn't been created for current day
		if ($results2 == 0){
			$wpdb->insert($table_name, array(
				"time" => $nowisnow,
				"postnum" => $post_id,
				"postcount" => 1
			));
		}
	}

	// get all the post view info so we can update meta fields
	$sql = $wpdb->prepare("SELECT t.postcount AS total, d.postcount AS today FROM $table_name_all AS t
			INNER JOIN $table_name AS d ON t.postnum = d.postnum
			WHERE t.postnum = %s AND d.time = %s", $post_id, $nowisnow);

	$row = $wpdb->get_row($sql);

	// add the counters to temp values on the post so it's easy to call from the loop
	update_post_meta($post_id, $app_abbr.'_daily_count', $row->today);
	update_post_meta($post_id, $app_abbr.'_total_count', $row->total);

}



// sidebar widget showing overall popular ads
function jr_todays_overall_count_widget($post_type, $limit) {
    global $wpdb, $table_name_all, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare("SELECT t.postcount, p.ID, p.post_title
				FROM $table_name_all AS t
				INNER JOIN $wpdb->posts AS p ON p.ID = t.postnum
				WHERE t.postcount > 0
				AND p.post_status = 'publish' AND p.post_type = %s
				ORDER BY t.postcount DESC LIMIT %d", $post_type, $limit);

	$results = $wpdb->get_results($sql);

    echo '<ul class="pop">';

	// must be overall views
	if ($results) {

        foreach ($results as $result)
			echo '<li><a href="'.get_permalink($result->ID).'">'.$result->post_title.'</a> ('.number_format($result->postcount).'&nbsp;'.__('views', 'appthemes') .')</li>';

    } else {

		echo '<li>' . __('No jobs viewed yet.', 'appthemes') . '</li>';

	}

	echo '</ul>';

}



// sidebar widget showing today's popular ads
function jr_todays_count_widget($post_type, $limit) {
    global $wpdb, $table_name, $nowisnow;

	// get all the post view info to display
	$sql = $wpdb->prepare("SELECT t.postcount, p.ID, p.post_title
			FROM $table_name AS t
			INNER JOIN $wpdb->posts AS p ON p.ID = t.postnum
			WHERE time = '$nowisnow'
			AND t.postcount > 0 AND p.post_status = 'publish' AND p.post_type = %s
			ORDER BY t.postcount DESC LIMIT %d", $post_type, $limit);

	$results = $wpdb->get_results($sql);

	echo '<ul class="pop">';

	// must be views today
    if ($results) {

        foreach ($results as $result)
			echo '<li><a href="'.get_permalink($result->ID).'">'.$result->post_title.'</a> ('.number_format($result->postcount).'&nbsp;'.__('views', 'appthemes') .')</li>';

    } else {

			echo '<li>' . __('No jobs viewed yet.', 'appthemes') . '</li>';
	}

	echo '</ul>';

}

?>
