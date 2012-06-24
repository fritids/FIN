<?php
/*
   Plugin Name: Auto Delete Posts
   Plugin URI: http://ashwinbihari.com/plugins
   Description: Auto delete or move posts after a pre-determined time.
   Author: Ashwin Bihari
   Author URI: http://ashwinbihari.com
   Version: 1.2.2
  */

/*
   * Copyright (c) 2007-2009 Ashwin Bihari
   * http://www.ashwinbihari.com/
   *
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the "Software"), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   *
   * The above copyright notice and this permission notice shall be included in
   * all copies or substantial portions of the Software.
   *
   * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   */

define("PREVIEW", '1');
define("DELETE_POSTS", '2');
define("MOVE_POSTS", '3');
define("ADD_CAT", '4');
define("SITE_WIDE", '5');
define("PER_POST", '6');
define("DEL_PUB_POSTS", '7');
define("DEL_DRAFT_POSTS", '8');

// For compatibility with WP 2.0

if (!function_exists('wp_die')) {
	/**
	 *
	 *
	 * @param unknown $msg
	 */


	function wp_die($msg) {
		die($msg);
	}


}

class ab_AutoDeletePosts {
	var $settings;

	/* Constructor */


	/**
	 *
	 */
	function ab_AutoDeletePosts() {
		$this->get_settings();

		// Config options
		add_action('admin_menu', array(&$this, 'wpadp_menu'));
		// Post-level settings
		if (PER_POST == $this->settings['PerPostOrSite']) {
			if ( function_exists('add_meta_box') )
				add_meta_box('adp-post-age', 'Per post expiration', 'wpadp_post_options', 'post', 'normal', 'high');
			else
				add_action('dbx_post_sidebar', array(&$this, 'wpadp_post_options'));
		}
		add_action('save_post', array(&$this, 'save_post'));

		if (DELETE_POSTS == $this->settings['PostAction']) {
			// Delete  hooks
			add_action('edit_post', array(&$this, 'wpadp_check_and_delete_posts'));
			add_action('publish_post',
				array(&$this, 'wpadp_check_and_delete_posts'));
		} else if (MOVE_POSTS == $this->settings['PostAction']) {
				// Move hooks
				add_action('edit_post', array(&$this, 'wpadp_check_and_move_posts'));
				add_action('publish_post', array(&$this, 'wpadp_check_and_move_posts'));
		} else if (ADD_CAT == $this->settings['PostAction']) {
				// Add Category hooks
				add_action('edit_post', array(&$this, 'wpadp_check_and_add_cats'));
				add_action('publish_post', array(&$this, 'wpadp_check_and_add_cats'));
		}
	}


	/* get_settings */


	/**
	 *
	 *
	 * @return unknown
	 */
	function get_settings() {
		$this->defaultSettings = array(
			// Number of days for post to expire
			'PostAge' => 14,
			// Per post or Site Wide operation
			'PerPostOrSite' => SITE_WIDE,
			// Delete drafts or published posts
			'DelPostType' => DEL_PUB_POSTS,
			// Delete, Move or Preview
			'PostAction' => PREVIEW,
			// Delete category
			'DeleteCategory' => array(0 => 1),
			// Move category
			'MoveCategory' => 1,
			// Add category
			'AddCategory' => 1
		);

		if (!isset($this->settings)) {
			$this->settings = get_option('wpadp_options');
			if (FALSE == $this->settings) {
				$this->settings = $this->defaultSettings;
			} else {
				$this->settings = array_merge($this->defaultSettings, $this->settings);
			}
			$this->sanitize_settings();
		}

		return $this->settings;
	}


	/* save_settings */


	/**
	 *
	 */
	function save_settings() {
		$this->get_settings();

		foreach ($this->defaultSettings as $k=>$v) {
			if ($_POST[$k] != 0) {
				$this->settings[$k] = $_POST[$k];
			}
		}

		$this->sanitize_settings();
		update_option('wpadp_options', $this->settings);
	}


	/* sanitize_settings */


	/**
	 *
	 */
	function sanitize_settings() {
		foreach (array_keys($this->settings) as $k) {
			$v = $this->settings[$k];
			switch ($k) {
			case 'PostAge':
			case 'PostAction':
			case 'PerPostOrSite':
			case 'MoveCategory':
			case 'AddCategory':
			case 'DelPostType':
				$this->settings[$k] = (int) $v;
				break;
			case 'DeleteCategory':
				// This is an array,
				break;
			default:
				unset($this->settings[$k]);
			}
		}
	}


	/* Add menu page */


	/**
	 *
	 */
	function wpadp_menu() {
		add_options_page('Auto Delete Posts Options', 'Auto Delete Posts', 9,
			basename(__FILE__), array(&$this, 'wpadp_manage'));
	}


	/* Manage options in admin menu */


	/**
	 *
	 */
	function wpadp_manage() {
		global $wpdb;

		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			$this->save_settings();
		} else {
			$this->get_settings();
		}

		require_once dirname(__FILE__) . '/auto-delete-posts.config.php';
	}





	/**
	 *
	 *
	 * @param unknown $postID
	 */
	function save_post($postID) {
		$this->get_settings();
		if (PER_POST == $this->settings['PerPostOrSite']) {
			$postAge = (int)$_POST['adpPostAge'];
			if ($postAge == 0) {
				$postAge = $this->settings['PostAge'];
			}

			if (!update_post_meta($postID, '_auto_delete_posts', $postAge)) {
				add_post_meta($postID, '_auto_delete_posts', $postAge);
			}
		}
	}


	/* Check posts that need to be deleted. */


	/**
	 *
	 */
	function wpadp_check_and_delete_posts() {
		global $wpdb;
		global $wp_version;

		$this->get_settings();
		
		$ids = $wpdb->get_results($this->gen_query());

		if ($ids) {
			foreach ($ids as $id) {
				if ('' == $id->ID) {
					continue;
				}
				if (version_compare($wp_version, '2.9', ">=")) {
					// Starting with WP 2.9.0, a force_delete option was added to wp_delete_post to allow
					// posts to be sent to the trash for recovery as opposed to deleted entirely. Since
					// we don't want to clutter up the database with posts that we are automatically
					// asked to delete, let's go ahead and bypass the trash.
					wp_delete_post($id->ID, true);
				} else {
					wp_delete_post($id->ID);	
				}
				
			}
		}
	}


	/* Check posts that need to be moved. */


	/**
	 *
	 */
	function wpadp_check_and_move_posts() {
		global $wpdb;

		$this->get_settings();

		$expiration = $this->settings['PostAge'];
		$category = array($this->settings['MoveCategory']);

		/*
     * Create a query that will give us all the post ID's that predate the
     * interval we are given, that is, NOW - expiration date.
     */
		$date = date('Y-m-d');
		$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND
    post_date < '$date' - INTERVAL ". $expiration." DAY";

		$ids = $wpdb->get_results($query);
		foreach ($ids as $id) {
			if ('' == $id->ID) {
				continue;
			}
			wp_set_post_cats('', $id->ID, $category);
		}
	}





	/**
	 *
	 */
	function wpadp_check_and_add_cats() {
		global $wpdb;

		$this->get_settings();

		$expiration = $this->settings['PostAge'];
		$newCat = array($this->settings['AddCategory']);

		/*
     * Create a query that will give us all the post ID's that predate the
     * interval we are given, that is, NOW - expiration date.
     */
		$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND
    post_date < '$date' - INTERVAL ". $expiration." DAY";

		$ids = $wpdb->get_results($query);
		foreach ($ids as $id) {
			if ('' == $id->ID) {
				continue;
			}
			$curCat = wp_get_post_cats('', $id->ID);
			// Merge the contents of the newCat array with the curCat array, each of these can contain
			// multiple values
			$curCat = array_merge($curCat, $newCat);
			wp_set_post_cats('', $id->ID, $curCat);
		}
	}





	/**
	 *
	 */
	function wpadp_post_options() {
		global $post_ID;
		$this->get_settings();
		$postAge = get_post_meta($post_ID, '_auto_delete_posts', true);
?>
      <div id="auto-delete-posts-div" class="postbox">
	 <div class="handlediv" title="Click to toggle"><br/></div>
	 <h3 class="hndle"><span>Auto Delete Posts</span></h3>
	 <div class="inside">
	 <label class="selectit" for="adpPostAge">
	 <input id="adpPostAge" type="text" size="3" name="adpPostAge" value="<?php echo @$postAge; ?>" />
	    Expiration in days
	    </label>
	    </div>
	    </div>
	    <?php
	}
	
	/**
	 *
	 */
	function do_cron() {
		global $wpdb;
		
		switch($this->settings['PostAction'])
		{
			case DELETE_POSTS:
				$this->wpadp_check_and_delete_posts();
				break;
			case MOVE_POSTS:
				$this->wpadp_check_and_move_posts();
				break;
			case ADD_CATS:
				$this->wpdp_check_and_add_cats();
				break;
			default:
				break;
		}
	}
	
	/**
	 * Generate a query that brings in all published or draft posts.
	 */
	function gen_query() {
		global $wpdb;
	
		if (SITE_WIDE == $this->settings['PerPostOrSite']) {
			$expiration = $this->settings['PostAge'];
	
			if (DEL_PUB_POSTS == $this->settings['DelPostType']) {
				$post_status = 'publish';
				if ((count($this->settings['DeleteCategory']) == 0) &&
		   			($this->settings['MoveCategory'] == '0') &&
		    		($this->settings['AddCategory'] == '0')) {
		  				echo "No category selected for Delete, Move or Add actions. Unable to preview any posts.";
		 		} else {
		  			$catArray = $this->settings['DeleteCategory'];
				
		  			$getpost_args = 'numberposts=-1&category=';
		  			for ($i = 0; $i < count($catArray); $i++) {
		    			$getpost_args .= $catArray[$i];
		    				if ($i+1 != count($catArray))
					      		$getpost_args .= ',';
					}
				}
			} else {
				$post_status = 'draft';
				$getpost_args = "numberposts=-1&post_status='$post_status'";
			}
			
		  	$posts = get_posts($getpost_args);
		  	if ($posts) {
    			$date = date('Y-m-d');
		    	$query = "SELECT ID FROM $wpdb->posts WHERE post_status = '$post_status' AND post_type = 'post' AND 
			    post_date < '$date' - INTERVAL ". $expiration." DAY ";
		    	$start = 0;
			    foreach($posts as $post) {
			      if (!$start) {
					$query .= "AND ( ID='";
					$start = 1;
			      } else {
					$query .= "OR ID='";
			      }
			      $query .= $post->ID;
			      $query .= "' ";
			    }
			  $query .= ") ORDER BY ID ASC";
			}
		} else {
			// Per Post
			
			// Determine the ID's of the posts that contain our custom field.
			$query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_auto_delete_posts'";
			$ids = $wpdb->get_results($query);
			
			// If we found some valid ID's, pull the post information and determine the expiration
			// date for those posts. Then compare it to today's date and determine if it should be
			// deleted, if so, add it to a list
			if ($ids) {
				foreach($ids as $id) {
					$post_cust_info = get_post_custom_values('_auto_delete_posts', $id->post_id);
					$expiration = $post_cust_info[0];
					
					$post_info = get_post($id->post_id);
					$date = strtotime('now');
					$exp_date = strtotime("-$expiration days");
					
					$post_date = strtotime($post_info->post_date);
							
					if ($post_date < $exp_date) {
						$idArray[] = $id->post_id;
					}
				}
				$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' ";
		    	$start = 0;
		    	if (!$idArray)
		    		return;
				foreach($idArray as $id) {
			      if (!$start) {
					$query .= "AND ( ID='";
					$start = 1;
			      } else {
					$query .= "OR ID='";
			      }
			      $query .= $id;
			      $query .= "' ";
			    }
			  $query .= ") ORDER BY ID ASC";
			}
		}
	
		return $query;
	}
}

// Instantiate the object to make the magic occur
$myAutoDeletePosts = new ab_AutoDeletePosts();

add_action('wpadp_daily_cron', 'wpadp_do_this_daily');

/**
 *
 */

/**
 *
 */
function wpadp_activate() {
	// Schedule a daily cron event at midnight.
	//wp_schedule_event(mktime(0, date('i') + 5, 0), 'daily', 'wpadp_daily_cron');
}


/**
 *
 */
function wpadp_deactivate() {
	// Let's remove our options when we are deactivated. This will allow us to not have to worry
	// about stale options from previous versions
	if (TRUE == get_option('wpadp_options')) {
		delete_option('wpadp_options');
	}
	
	// Remove our cron job.
	//wp_clear_scheduled_hook('wpadp_daily_cron');

}

// Register the activate and deactivate hooks
register_activation_hook( __FILE__, 'wpadp_activate' );
register_deactivation_hook( __FILE__, 'wpadp_deactivate' );

function wpadp_do_this_daily() {
	//$myAutoDeletePosts->do_cron();
}

/* Look for immediate action hooks and perform the actions */
if (isset($_POST['performdelete'])) {
	$myAutoDeletePosts->wpadp_check_and_delete_posts();
}

if (isset($_POST['performmove'])) {
	$myAutoDeletePosts->wpadp_check_and_move_posts();
}

if (isset($_POST['performaddcat'])) {
	$myAutoDeletePosts->wpadp_check_and_add_cats();
}
?>