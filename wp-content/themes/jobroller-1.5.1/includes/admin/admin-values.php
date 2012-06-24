<?php
/**
*
* Here is where all the admin field data is stored
* All the data is stored in arrays and then looped though
* @author AppThemes
* @version 1.2
*
*
*
*/

global $options_settings, $options_pricing, $options_feeds, $options_emails, $options_advertisments, $options_integration, $app_abbr;

$options_settings = array(

	array( 'type' => 'tab', 'tabname' => __('General', 'appthemes') ),

	array( 'name' => __('Site Configuration', 'appthemes'), 'type' => 'title', 'desc' => '', 'id' => ''),

	array(  
		'name' 		=> __('Color Scheme', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Select the color scheme you would like to use.','appthemes'),
		'id' 		=> $app_abbr.'_child_theme',
		'css' 		=> 'min-width:230px;',
		'std' 		=> 'style-default.css',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min'		=> '',
		'type' 		=> 'select',
		'options' 	=> array( 
			'style-default.css'    => __('Default Theme', 'appthemes'),
			'style-pro-blue.css'   => __('Blue Pro Theme', 'appthemes'),
			'style-pro-green.css'  => __('Green Pro Theme', 'appthemes'),
			'style-pro-orange.css' => __('Orange Pro Theme', 'appthemes'),
			'style-pro-gray.css'   => __('Gray Pro Theme', 'appthemes'),
			'style-pro-red.css'    => __('Red Pro Theme', 'appthemes'),
			'style-basic.css'      => __('Basic Plain Theme', 'appthemes')
		)
	),                                            

	array(  
		'name' 		=> __('Enable Logo','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('If you do not have a logo to use, select no and this will display the title and description of your web site instead.','appthemes'),
		'id' 		=> $app_abbr.'_use_logo',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' 		=> __('Web Site Logo','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Paste the URL of your web site logo image here. It will replace the default JobRoller header logo.(i.e. http://www.yoursite.com/logo.jpg)','appthemes'),
		'id' 		=> $app_abbr.'_logo_url',
		'css' 		=> 'min-width:398px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'upload',
		'std' 		=> ''
	),
	

	array(  
		'name' 		=> __('Disable Blog','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turn this on to hide the blog pages.','appthemes'),
		'id' 		=> $app_abbr.'_disable_blog',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' 	=> array( 
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes')
		)
	),

	array(  
		'name' 		=> __('Feedburner URL','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Feedburner account</a>.",'appthemes'), '<div class="feedburnerico"></div>', 'http://feedburner.google.com' ),
		'tip' 		=> __('Paste your Feedburner address here. It will automatically redirect your default RSS feed to Feedburner. You must have a Google Feedburner account setup first.','appthemes'),
		'id' 		=> $app_abbr.'_feedburner_url',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Twitter ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Twitter account</a>.",'appthemes'), '<div class="twitterico"></div>', 'http://twitter.com' ),
		'tip' 		=> __('Paste your Twitter ID here. It will be used in the Twitter sidebar widget. You must have a Twitter account setup first.','appthemes'),
		'id' 		=> $app_abbr.'_twitter_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Facebook Page ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Facebook account</a>.",'appthemes'), '<div class="facebookico"></div>', 'http://www.facebook.com' ),
		'tip' 		=> __('Paste your Facebook Page ID here. It will be used in the Facebook Like Box sidebar widget. You must have a Facebook account and page setup first.','appthemes'),
		'id' 		=> $app_abbr.'_facebook_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req'		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('ShareThis ID','appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>ShareThis account</a>.",'appthemes'), '<div class="sharethisico"></div>', 'http://sharethis.com' ),
		'tip' 		=> __('Paste your ShareThis publisher ID here. It will show the ShareThis buttons on the blog post and job listings. You must have a ShareThis account and page setup first.','appthemes'),
		'id' 		=> $app_abbr.'_sharethis_id',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min'		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Tracking Code','appthemes'),
		'desc' 		=> sprintf('%s' . __("Sign up for a free <a target='_new' href='%s'>Google Analytics account</a>.",'appthemes'), '<div class="googleico"></div>', 'http://www.google.com/analytics/' ),
		'tip' 		=> __('Paste your analytics tracking code here. Google Analytics is free and the most popular but you can use other providers as well.','appthemes'),
		'id' 		=> $app_abbr.'_google_analytics',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),

	array(	'name' => __('Google Maps Settings', 'appthemes'), 'type' => 'title', 'id' => '' ),

	array(  
		'name' 		=> __('Google Maps Language','appthemes'),
		'desc' 		=> sprintf( __("Find the list of supported language codes <a target='_new' href='%s'>here</a>.",'appthemes'), 'http://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1' ),
		'tip' 		=> __('The Google Maps API uses the browsers language setting when displaying textual info on the map. In most cases, this is preferable and you should not need to override this setting. However, if you wish to change the Maps API to ignore the browsers language setting and force it to display info in a particular language, enter your two character region code here (i.e. Japanese is ja).','appthemes'),
		'id' 		=> $app_abbr.'_gmaps_lang',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Google Maps Region Biasing','appthemes'),
		'desc' 		=> sprintf( __("Find your two-letter ccTLD region code <a target='_new' href='%s'>here</a>.",'appthemes'), 'http://en.wikipedia.org/wiki/CcTLD' ),
		'tip' 		=> __("Enter your country's two-letter region code here to properly display map locations. (i.e. Someone enters the location &quot;Toledo&quot;, it's based off the default region (US) and will display &quot;Toledo, Ohio&quot;. With the region code set to &quot;ES&quot; (Spain), the results will show &quot;Toledo, Spain.&quot;)",'appthemes'),
		'id' 		=> $app_abbr.'_gmaps_region',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

        array(  'name'          => __('Distance Unit','appthemes'),
                'desc'          => '',
                'tip'           => __('Defines the radius unit for search.','appthemes'),
                'id'            => $app_abbr.'_distance_unit',
                'css'           => 'width:100px;',
                'std'           => '',
                'vis'           => '',
                'req'           => '',
                'js'            => '',
                'min'           => '',
                'type'          => 'select',
                'options'       => array(  'mi' => 'Miles',
                                           'km'  => 'Kilometers')),

	array( 'name' => __('General Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),
	
	array(  
		'name' => __('Enable password fields on registration form','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turning this off will send the user a password instead of letting them set it.','appthemes'),
		'id' 		=> $app_abbr.'_allow_registration_password',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Show Sidebar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Turning off the sidebar will make the main content area wider and move the submit button for all main pages.','appthemes'),
		'id' 		=> $app_abbr.'_show_sidebar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Show Search Bar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Toggle the search bar on/off with this option.','appthemes'),
		'id' 		=> $app_abbr.'_show_searchbar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Show Filter Bar','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Toggle the filter bar on/off with this option (shows checkboxes with Full-Time, Part-Time, etc.','appthemes'),
		'id' 		=> $app_abbr.'_show_filterbar',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('"Submit" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the Post a Job button. Leave it blank to automatically display pricing (if listings are paid).','appthemes'),
		'id' 		=> $app_abbr.'_jobs_submit_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Jobs', 'appthemes') ),

	array( 'name' => __('Job Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),

	array(
		'name' 		=> __('Default Expiration Days','appthemes'),
		'desc' 		=> __("Default number of days until a job offer expires"),
		'tip' 		=> __("Enter the default number of days until a job offer expires.",'appthemes'),
		'id' 		=> $app_abbr.'_jobs_default_expires',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Moderate Job Listings','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to control if new free job listings should be manually approved before they go live. Note: paid jobs will automatically be published regardless of this setting.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_require_moderation',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Allow Job Editing','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to control if job listings can be edited by the user.','appthemes'),
		'id' 		=> $app_abbr.'_allow_editing',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Edited Job Requires Approval','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This options allows you to define whether or not you want to moderate edited jobs. The job will be marked as \'draft\' and admin will be notified via email.','appthemes'),
		'id' 		=> $app_abbr.'_editing_needs_approval',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Show Page Views Counter','appthemes'),
		'desc'		=> '',
		'tip'		=> __("This will show a 'total views' and 'today's views' at the bottom of each job listing and blog post.",'appthemes'),
		'id'		=> $app_abbr.'_ad_stats_all',
		'css'		=> 'min-width:100px;',
		'std'		=> '',
		'vis'		=> '',
		'req'		=> '',
		'js'		=> '',
		'min'		=> '',
		'type'		=> 'select',
		'options'	=> array(
			'yes' => __('Yes', 'appthemes'),
			 'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Display "How to Apply" Field?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job should the how to apply field be visible?','appthemes'),
		'id' 		=> $app_abbr.'_submit_how_to_apply_display',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Job Category Required','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job, is job category required? Make sure you have at least one job category before enabling this option. (Recommended)','appthemes'),
		'id' 		=> $app_abbr.'_submit_cat_required',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Enable Job Salary Field?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enable or disable the Salary field in the job submission form.','appthemes'),
		'id' 		=> $app_abbr.'_enable_salary_field',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Allow HTML in Job Descriptions?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('When submitting a job, is HTML allowed? Select no to have it automatically stripped out.','appthemes'),
		'id' 		=> $app_abbr.'_html_allowed',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Expired Jobs Action','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Choose what to do with expired jobs. Selecting \'display message\' will keep the job visible and display a \'job expired\' notice on it. Selecting \'hide\' will change the job post to private so only the job poster may view it..','appthemes'),
		'id' 		=> $app_abbr.'_expired_action',
		'css' 		=> 'min-width:150px;',
		'std' 		=> 'display_message',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'display_message' => __('Display Message', 'appthemes'),
			'hide'  => __('Hide', 'appthemes')
		)
	),
	
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Resumes', 'appthemes') ),

	array( 'name' => __('Job Seeker Options', 'appthemes'), 'type' => 'title', 'desc' 		=> '' ),
	
	array(  
		'name' => __('Enable Job Seeker Registration','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Allows Job Seekers to signup. Job Seekers cannot post jobs; they can only find jobs and submit their resume.','appthemes'),
		'id' 		=> $app_abbr.'_allow_job_seekers',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('"My Profile" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the My Profile button.','appthemes'),
		'id' 		=> $app_abbr.'_my_profile_button_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Submit your Resume, update your profile, and allow employers to find <em>you</em>!'
	),
	
	array(  
		'name' => __('"Submit your resume" Button Text','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This text will appear below the Submit your resume button when browsing resumes.','appthemes'),
		'id' 		=> $app_abbr.'_submit_resume_button_text',
		'css' 		=> 'width:500px;height:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Register as a Job Seeker to submit your Resume.'
	),
	
	array( 'name' => __('Resume Options', 'appthemes'), 'type' => 'title', 'desc' 		=> __('Control who can view resumes', 'appthemes') ),
	
	array(  
		'name' => __('Resume Listings Visibility','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Lets you define who can browse through submitted resumes.','appthemes'),
		'id' 		=> $app_abbr.'_resume_listing_visibility',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'listers',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'public' => __('Public', 'appthemes'),
			'members'  => __('Members only', 'appthemes'),
			'listers'  => __('Job listers', 'appthemes'),
			'recruiters'  => __('Recruiters', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Resume Visibility','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Lets you define who can view submitted resumes.','appthemes'),
		'id' 		=> $app_abbr.'_resume_visibility',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'listers',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'public' => __('Public', 'appthemes'),
			'members'  => __('Members only', 'appthemes'),
			'listers'  => __('Job listers', 'appthemes'),
			'recruiters'  => __('Recruiters', 'appthemes')
		)
	),
	
	

	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Pages', 'appthemes') ),

	array( 'name' => __('Page/Category ID Configuration', 'appthemes'), 'type' => 'title', 'desc' => '' ),
	
	array(  
		'name' 		=> __('Featured Job Category ID','appthemes'),
		'desc' 		=> sprintf( __("Visit the <a target='_new' href='%s'>Job Categories</a> page to get the category ID.",'appthemes'), 'edit-tags.php?taxonomy=job_cat&post_type=job_listing' ),
		'tip' 		=> __('By default, your featured category ID is already included. To find the featured category ID in case you need to change it, click on the Job Categories link and then hover over the title of the Featured category. The status bar of your browser will display a URL with a numeric ID at the end. This is the category ID.','appthemes'),
		'id' 		=> $app_abbr.'_featured_category_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Submit Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Submit job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_submit_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Edit Job Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the edit job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_edit_job_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('My Dashboard Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the My Dashboard page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_dashboard_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('User Profile Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the user profile page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_user_profile_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array( 'name' => __('Confirmation Page ID','appthemes'),
		'desc' 		=> 'This is a page for non-IPN paypal transactions to go through.',
		'tip' 		=> __('Enter the page ID for the Confirmation job page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_add_new_confirm_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Blog Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Blog page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_blog_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array( 'name' => __('Jobs by date Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the jobs date archive page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_date_archive_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('Terms Page ID','appthemes'),
		'desc' 		=> __('Create a terms page and enter it\'s ID here; this will enable a checkbox on the registration page to confirm that the user accepts your terms and conditions.', 'appthemes'),
		'tip' 		=> __('Enter the page ID for the terms page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_terms_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' 		=> __('Job Seeker Register Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Job Seeker Registration page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_job_seeker_register_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' 		=> __('Job Seeker Edit Resume Page ID','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the page ID for the Edit Resume page. To find the correct Page ID, go to Pages->Edit and hover over the title of the page. The status bar of your browser will display a URL with a numeric ID at the end. This is the page ID.','appthemes'),
		'id' 		=> $app_abbr.'_job_seeker_resume_page_id',
		'css' 		=> 'min-width:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	
	array( 'type' => 'tabend'),
	
	array( 'type' => 'tab', 'tabname' => __('Security', 'appthemes') ),

	array(	'name' => __('Security Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Back Office Access','appthemes'),
		'desc' 		=> sprintf( __("View the WordPress <a target='_new' href='%s'>Roles and Capabilities</a> for more information.",'appthemes'), 'http://codex.wordpress.org/Roles_and_Capabilities' ),
		'tip' 		=> __('Allows you to restrict access to the WordPress Back Office (wp-admin) by specific role. Keeping this set to admins only is recommended. Select Disable if you have problems with this feature.','appthemes'),
		'id' 		=> $app_abbr.'_admin_security',
		'css' 		=> 'min-width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'manage_options' => __('Admins Only', 'appthemes'),
			'edit_others_posts' => __('Admins, Editors', 'appthemes'),
			'publish_posts' => __('Admins, Editors, Authors', 'appthemes'),
			'edit_posts' => __('Admins, Editors, Authors, Contributors', 'appthemes'),
			'read' => __('All Access', 'appthemes'),
			'disable' => __('Disable', 'appthemes')
		)
	),

	array( 'name' => __('reCaptcha Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Enable reCaptcha', 'appthemes'),
		'desc' 		=> sprintf(__("reCaptcha is a free anti-spam service provided by Google. Learn more about <a target='_new' href='%s'>reCaptcha</a>.", 'appthemes'), 'http://code.google.com/apis/recaptcha/'),
		'tip' 		=> __('Set this option to yes to enable the reCaptcha service that will protect your site against spam registrations. It will show a verification box on your registration page that requires a human to read and enter the words.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_enable',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),
	
	array(  
		'name' => __('reCaptcha Public Key', 'appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Google reCaptcha</a> account.",'appthemes'), '<div class="captchaico"></div>', 'https://www.google.com/recaptcha/admin/create' ),
		'tip' 		=> __('Enter your public key here to enable an anti-spam service on your new user registration page (requires a free Google reCaptcha account). Leave it blank if you do not wish to use this anti-spam feature.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_public_key',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('reCaptcha Private Key', 'appthemes'),
		'desc' 		=> sprintf( '%s' . __("Sign up for a free <a target='_new' href='%s'>Google reCaptcha</a> account.",'appthemes'), '<div class="captchaico"></div>', 'https://www.google.com/recaptcha/admin/create' ),
		'tip' 		=> __('Enter your private key here to enable an anti-spam service on your new user registration page (requires a free Google reCaptcha account). Leave it blank if you do not wish to use this anti-spam feature.','appthemes'),
		'id' 		=> $app_abbr.'_captcha_private_key',
		'css' 		=> 'min-width:500px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Choose Theme', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Select the color scheme you wish to use for reCaptcha.', 'appthemes'),
		'id' 		=> $app_abbr.'_captcha_theme',
		'css' 		=> 'width:100px;',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'red' => __('Red', 'appthemes'),
			'white' => __('White', 'appthemes'),
			'blackglass' => __('Black', 'appthemes'),
			'clean'  => __('Clean', 'appthemes')
		)
	),

	array( 'name' => __('Anti-Spam Settings', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '' ),

	array(  
		'name' => __('Anti-Spam Question', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Question asked before visitor can submit a new job listing.','appthemes'),
		'id' 		=> $app_abbr.'_antispam_question',
		'css' 		=> 'width:500px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> 'Is fire &ldquo;<em>hot</em>&rdquo; or &ldquo;<em>cold</em>&rdquo;?'
	),

	array(  
		'name' => __('Anti-Spam Answer', 'appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enter the correct answer here.','appthemes'),
		'id' 		=> $app_abbr.'_antispam_answer',
		'css' 		=> 'width:50px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> 'hot'
	),

	array( 'type' => 'tabend' ),


	array( 'type' => 'tab', 'tabname' => __('Advertising', 'appthemes') ),

	array(	'name' => __('Header banner (468x60)', 'appthemes'),
		'type' 		=> 'title',
		'desc' 		=> '',
		'id' 		=> ''
	),

	array(  
		'name' => __('Enable header banner spot?', 'appthemes'),
		'desc' 		=> __("Change this option to enable or disable the header banner spot.",'appthemes'),
		'tip' 		=> __('This will replace the header navigation.','appthemes'),
		'id' 		=> $app_abbr.'_enable_header_banner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Banner Code', 'appthemes'),
		'desc' 		=> 'Image/Link HTML or JavaScript for the banner.',
		'tip' 		=> __('This can be what you like; javascript, an image and a link, text.','appthemes'),
		'id' 		=> $app_abbr.'_header_banner',
		'css' 		=> 'width:500px;height:150px;',
		'type' 		=> 'textarea',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),

	array(	'name' => __('Job Listing Banner (468x60)', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> 'If you have the sidebar turned off you may fit in a 728x90 banner instead.' ),

	array(  
		'name' => __('Enable job listing banner spot?', 'appthemes'),
		'desc' 		=> __("Change this option to enable or disable the job listing banner spot.",'appthemes'),
		'tip' 		=> __('This banner appears in a job listing, usually between "Job description" and "How to Apply".','appthemes'),
		'id' 		=> $app_abbr.'_enable_listing_banner',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(  
		'name' => __('Banner Code', 'appthemes'),
		'desc' 		=> 'Image/Link HTML or JavaScript for the banner.',
		'tip' 		=> __('This can be what you like; javascript, an image and a link, text.','appthemes'),
		'id' 		=> $app_abbr.'_listing_banner',
		'css' 		=> 'width:500px;height:150px;',
		'type' 		=> 'textarea',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),

	array( 'type' => 'tabend'),


	array( 'type' => 'tab', 'tabname' => __('Advanced', 'appthemes') ),

		array(	'name' => __('Advanced Options', 'appthemes'),
					'type' => 'title',
					'id' => ''),


				array(  'name' => __('Enable Debug Mode','appthemes'),
                        'desc' => '',
                        'tip' => __('This will print out the $wp_query->query_vars array at the top of your website. This should only be used for debugging.','appthemes'),
                        'id' => $app_abbr.'_debug_mode',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),

				array(  'name' => __('Enable Debug Log','appthemes'),
						'desc' => '',
						'tip' => __('Turn this on to log emails and transactions for debugging. Logs are stored in /themes/jobroller/log/. Delete them when you are finished since they contain info about jobs and transactions.','appthemes'),
						'id' => $app_abbr.'_enable_log',
						'css' => 'min-width:100px;',
						'std' => 'no',
						'vis' => '',
						'req' => '',
						'js' => '',
						'min' => '',
						'type' => 'select',
						'options' => array( 'no'  => __('No', 'appthemes'),
											'yes' => __('Yes', 'appthemes'))),

				array(  'name' => __('Use Google CDN jQuery','appthemes'),
                        'desc' => '',
                        'tip' => __("This will use Google's hosted jQuery files which are served from their global content delivery network. This will help your site load faster and save bandwidth.",'appthemes'),
                        'id' => $app_abbr.'_google_jquery',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),
											 
				array(  'name' => __('Disable WordPress Version Meta Tag','appthemes'),
                        'desc' => '',
                        'tip' => __("This will remove the WordPress generator meta tag in the source code of your site <code>< meta name='generator' content='WordPress 3.1' ></code>. It's an added security measure which prevents anyone from seeing what version of WordPress you are using. It also helps to deter hackers from taking advantage of vulnerabilities sometimes present in WordPress. (Yes is recommended)",'appthemes'),
                        'id' => $app_abbr.'_remove_wp_generator',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),
											 
				array(  'name' => __('Disable WordPress User Toolbar','appthemes'),
                        'desc' => '',
                        'tip' => __("This will remove the WordPress user toolbar at the top of your web site which is displayed for all logged in users. This feature was added in WordPress 3.1.",'appthemes'),
                        'id' => $app_abbr.'_remove_admin_bar',
                        'css' => 'width:100px;',
                        'std' => '',
                        'vis' => '',
                        'req' => '',
                        'js' => '',
                        'min' => '',
                        'type' => 'select',
                        'options' => array(  'no'   => __('No', 'appthemes'),
                                             'yes'  => __('Yes', 'appthemes'))),							 

		array( 'name' => __('Custom Post Type & Taxonomy URLs', 'appthemes'),
                'type' => 'title',
                'id' => ''),

				array(  'name' => __('Job Listing Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job listing urls. The default is jobs and will look like this: http://www.yoursite.com/jobs/ad-title-here/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Category Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job category urls. The default is job-category and will look like this: http://www.yoursite.com/job-category/category-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_cat_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Type Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job type urls. The default is job-type and will look like this: http://www.yoursite.com/job-type/type-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_type_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Tag Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your job tag urls. The default is job-tag and will look like this: http://www.yoursite.com/job-tag/tag-name/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_tag_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

				array(  'name' => __('Job Salary Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your salary urls. The default is salary and will look like this: http://www.yoursite.com/salary/salary-value/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_job_salary_tax_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),
                        
               array(  'name' => __('Resume Base URL', 'appthemes'),
                        'desc'=> sprintf( __("IMPORTANT: You must <a target='_blank' href='%s'>re-save your permalinks</a> for this change to take effect.",'appthemes'), 'options-permalink.php' ),
                        'tip' => __('This controls the base name of your resume urls. The default is resumes and will look like this: http://www.yoursite.com/resumes/resume-title-here/. Do not include any slashes. This should only be alpha and/or numeric values. You should not change this value once you have launched your site otherwise you risk breaking urls of other sites pointing to yours, etc.','appthemes'),
                        'id' => $app_abbr.'_resume_permalink',
                        'css' => 'width:250px;',
                        'type' => 'text',
                        'req' => '',
                        'min' => '',
                        'std' => '',
                        'vis' => '',
                        'visid' => ''),

	array( 'type' => 'tabend'),


);


$options_emails = array (

 	array( 'type' => 'tab', 'tabname' => __('General', 'appthemes') ),

	array(	'name' => __('Email Notifications', 'appthemes'), 'type' 		=> 'title', 'desc' 		=> '', 'id' 		=> '' ),

	array(
		'name' => __('New Job Email','appthemes'),
		'desc' 		=> sprintf(__("Emails will be sent to: %s. (<a target='_new' href='%s'>Change email address</a>)", 'appthemes'), get_option('admin_email'), 'options-general.php'),
		'tip' 		=> __('Send me an email once a new job has been submitted.','appthemes'),
		'id' 		=> $app_abbr.'_new_ad_email',
		'css' 		=> 'width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('Job Approved Email','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Send the job owner an email once their job has been approved either by you manually or after payment has been made (post status changes from pending to published).','appthemes'),
		'id' 		=> $app_abbr.'_new_job_email_owner',
		'css' 		=> 'width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('Enable Reminder Emails','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Send the job owner an email 5/1 days before their job expires, and another once their job has expired (post status changes from published to draft).','appthemes'),
		'id' 		=> $app_abbr.'_expired_job_email_owner',
		'css' 		=> 'width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),

	array(
		'name' => __('BCC on all Apply Emails','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('Enable this option to receive a copy of application emails.','appthemes'),
		'id' 		=> $app_abbr.'_bcc_apply_emails',
		'css' 		=> 'width:100px;',
		'std' 		=> '',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),


	array( 'type' => 'tabend'),


	array( 'type' => 'tab', 'tabname' => __('New User Email', 'appthemes') ),

	array(	'name' => __('New User Registration Email', 'appthemes'), 'type' 		=> 'title' ),
	
		array(  
			'name' => __('Enable Custom Email','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('Sends a custom new user notification email to your customers by using the fields you complete below. If this is set to &quot;No&quot;, the default WordPress new user notification email will be sent. This is useful for debugging if your custom emails are not being sent.','appthemes'),
			'id' 		=> $app_abbr.'_nu_custom_email',
			'css' 		=> 'width:100px;',
			'std' 		=> '',
			'vis' 		=> '',
			'req' 		=> '',
			'js' 		=> '',
			'min' 		=> '',
			'type' 		=> 'select',
			'options' => array(  
				'yes' => __('Yes', 'appthemes'),
				'no'  => __('No', 'appthemes')
			)
		),

		array(  
			'name' => __('From Name','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what your customers will see as the &quot;from&quot; when they receive the new user registration email. Use plain text only','appthemes'),
			'id' 		=> $app_abbr.'_nu_from_name',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('From Email','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is what your customers will see as the &quot;from&quot; email address (also the reply to) when they receive the new user registration email. Use only a valid and existing email address with no html or variables.','appthemes'),
			'id' 		=> $app_abbr.'_nu_from_email',
			'css' 		=> 'width:250px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('Email Subject','appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This is the subject line your customers will see when they receive the new user registration email. Use text and variables only.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_subject',
			'css' 		=> 'width:400px;',
			'vis' 		=> '',
			'type' 		=> 'text',
			'req' 		=> '',
			'min' 		=> '',
			'std' 		=> ''
		),

		array(  
			'name' => __('Allow HTML in Body', 'appthemes'),
			'desc' 		=> '',
			'tip' 		=> __('This option allows you to use html markup in the email body below. It is recommended to keep it set to &quot;No&quot; to avoid problems with delivery. If you turn it on, make sure to test it and make sure the formatting looks ok and gets delivered properly.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_type',
			'css' 		=> 'width:100px;',
			'vis' 		=> '',
			'std' 		=> '',
			'js' 		=> '',
			'type' 		=> 'select',
			'options' => array(  
				'text/html'   => __('Yes', 'appthemes'),
				'text/plain'  => __('No', 'appthemes')
			)
		),

		array(  
			'name' => __('Email Body','appthemes'),
			'desc' 		=> __('You may use the following variables within the email body and/or subject line.<br/><br/><strong>%username%</strong> - prints out the username<br/><strong>%useremail%</strong> - prints out the users email address<br/><strong>%password%</strong> - prints out the users text password<br/><strong>%siteurl%</strong> - prints out your website url<br/><strong>%blogname%</strong> - prints out your site name<br/><strong>%loginurl%</strong> - prints out your sites login url<br/><br/>Each variable MUST have the percentage signs wrapped around it with no spaces.<br/>Always test your new email after making any changes (register) to make sure it is working and formatted correctly. If you do not receive an email, chances are something is wrong with your email body.','appthemes'),
			'tip' 		=> __('Enter the text you would like your customers to see in the new user registration email. Make sure to always at least include the %username% and %password% variables otherwise they might forget later.','appthemes'),
			'id' 		=> $app_abbr.'_nu_email_body',
			'css' 		=> 'width:550px;height:250px;',
			'vis' 		=> '',
			'req' 		=> '',
			'min' 		=> '',
			'type' 		=> 'textarea',
			'std' 		=> ''
		),

	array( 'type' => 'tabend'),


);


// admin options for the pricing page
$options_pricing = array (

	array( 'type' 		=> 'tab', 'tabname' => __('Pricing', 'appthemes') ),
	
	array(	'name' => __('Pricing Options', 'appthemes'), 'type' 		=> 'title','desc' 		=> '', 'id' 		=> '' ),

	array(  
		'name' => __('Job Listing Fee','appthemes'),
		'desc' 		=> 'Default job listing fee. Not used if you define <a href="admin.php?page=jobpacks">job packs</a>',
		'tip' 		=> __('Enter a numeric value, do not include currency symbols. Leave blank to enable free listings.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_listing_cost',
		'css' 		=> 'min-width:75px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),

	array(  
		'name' => __('Allow Job Relisting','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This enables an option for your customers to relist their job posting when it has expired.','appthemes'),
		'id' 		=> $app_abbr.'_allow_relist',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes'),
		)
	),

	array(  
		'name' => __('Re-Listing Fee','appthemes'),
		'desc' 		=> 'Default re-listing fee. Not used if you define <a href="admin.php?page=jobpacks">job packs</a>',
		'tip' 		=> __('Enter a numeric value, do not include currency symbols. Leave blank to enable free re-listings.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_relisting_cost',
		'css' 		=> 'min-width:75px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Featured Job Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the additional amount you will charge visitors to post a featured job on your site. A featured job appears at the top of the category. Leave this blank if you do not want to offer featured ads.','appthemes'),
		'id' 		=> $app_abbr.'_cost_to_feature',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(
		'name' => __('Symbol Position', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies place the symbol on the right side vs the left. Select how you would like your currency symbol to be displayed.','appthemes'),
		'id' => $app_abbr.'_curr_symbol_pos',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => '',
		'type' => 'select',
		'options' => array(  'left'         => __('Left of Currency ($100)', 'appthemes'),
							 'left_space'   => __('Left of Currency with Space ($ 100)', 'appthemes'),
							 'right'        => __('Right of Currency (100$)', 'appthemes'),
							 'right_space'  => __('Right of Currency with Space (100 $)', 'appthemes'))),
							 
	array(
		'name' => __('Thousands separator', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies use a decimal point instead of a comma.','appthemes'),
		'id' => $app_abbr.'_curr_thousands_separator',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => 'comma',
		'type' => 'select',
		'options' => array(  'comma'         => __('Comma', 'appthemes'),
							 'decimal'   => __('Decimal', 'appthemes'),
		)),
	
	array(
		'name' => __('Decimal separator', 'appthemes'),
		'desc' => '',
		'tip' => __('Some currencies use a comma instead of a decimal point.','appthemes'),
		'id' => $app_abbr.'_curr_decimal_separator',
		'css' => 'min-width:200px;',
		'vis' => '',
		'js' => '',
		'std' => 'decimal',
		'type' => 'select',
		'options' => array(  'comma'         => __('Comma', 'appthemes'),
							 'decimal'   => __('Decimal', 'appthemes'),
		)),
		

	array(  
		'name' => __('Collect Payments in', 'appthemes'),
		'desc' 		=> sprintf( __("See the list of supported <a target='_new' href='%s'>PayPal currencies</a>.", 'appthemes'), 'https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_intro-outside' ),
		'tip' 		=> __('This is the currency you want to collect payments in. It applies mainly to PayPal payments since other payment gateways accept more currencies. If your currency is not listed then PayPal currently does not support it.','appthemes'),
		'id' 		=> $app_abbr.'_jobs_paypal_currency',
		'css' 		=> 'min-width:200px;',
		'vis' 		=> '',
		'js' 		=> '',
		'std' 		=> '',
		'type' 		=> 'select',
		'options' => array( 
			'USD' => __('US Dollars (&#36;)', 'appthemes'),
			'EUR' => __('Euros (&euro;)', 'appthemes'),
			'GBP' => __('Pounds Sterling (&pound;)', 'appthemes'),
			'AUD' => __('Australian Dollars (&#36;)', 'appthemes'),
			'BRL' => __('Brazilian Real (&#36;)', 'appthemes'),
			'CAD' => __('Canadian Dollars (&#36;)', 'appthemes'),
			'CZK' => __('Czech Koruna', 'appthemes'),
			'DKK' => __('Danish Krone', 'appthemes'),
			'HKD' => __('Hong Kong Dollar (&#36;)', 'appthemes'),
			'HUF' => __('Hungarian Forint', 'appthemes'),
			'ILS' => __('Israeli Shekel', 'appthemes'),
			'JPY' => __('Japanese Yen (&yen;)', 'appthemes'),
			'MYR' => __('Malaysian Ringgits', 'appthemes'),
			'MXN' => __('Mexican Peso (&#36;)', 'appthemes'),
			'NZD' => __('New Zealand Dollar (&#36;)', 'appthemes'),
			'NOK' => __('Norwegian Krone', 'appthemes'),
			'PHP' => __('Philippine Pesos', 'appthemes'),
			'PLN' => __('Polish Zloty (z&#321;)', 'appthemes'),
			'SGD' => __('Singapore Dollar (&#36;)', 'appthemes'),
			'SEK' => __('Swedish Krona', 'appthemes'),
			'CHF' => __('Swiss Franc', 'appthemes'),
			'TWD' => __('Taiwan New Dollars', 'appthemes'),
			'THB' => __('Thai Baht', 'appthemes')
		)
	),

	array( 'type' => 'tabend'),
	
	array(  'type' 		=> 'tab', 'tabname' => __('Resume Subscriptions', 'appthemes')),
	
	array(	'name' => __('Subscription Options', 'appthemes'), 'type' 		=> 'title','desc' 		=> __('Control subscriptions for resume access', 'appthemes'), 'id' 		=> '' ),
	
	array(  
		'name' => __('Require active subscription to view resumes?','appthemes'),
		'desc' 		=> __('Enabling this option will block access to the resume section if the user does not have a subscription. Access will still be determined by your visibility settings on the settings page, e.g. if set to \'recruiters\', only recruiters will be able to subscribe. To subscribe the user must be logged in.','appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_require_subscription',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes'),
		)
	),
	
	array(  
		'name' => __('Subscription notice', 'appthemes'),
		'desc' 		=> __('Notice to display above the subscription button.','appthemes'),
         'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_subscription_notice',
		'css' 		=> 'width:500px;height:50px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> 'Sorry, you do not have permission to browse and view resumes. To access our resume database please subscribe using the button below.'
	),

	array(  
		'name' => __('Resume Access Subscription Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the amount you want to charge job listers access to the resume database.','appthemes'),
		'id' 		=> $app_abbr.'_resume_access_cost',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Subscription Length', 'appthemes'),
		'desc' 		=> __('Enter an integer. This length is also affected by the unit below.', 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_access_length',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '1'
	),
	
	array(  
		'name' => __('Subscription Unit', 'appthemes'),
		'desc' 		=> __("Select a unit for the subscription period.",'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_access_unit',
		'css' 		=> 'width:100px;',
		'std' 		=> 'M',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'M' => __('Months', 'appthemes'),
			'D'  => __('Days', 'appthemes'),
			'W'  => __('Weeks', 'appthemes'),
			'Y'  => __('Years', 'appthemes')
		)
	),
	
	array(  
		'name' => __('Allow trial?','appthemes'),
		'desc' 		=> __('Enabling a trial lets you charge more or less during the first billing period.','appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_allow_trial',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'no',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'no'  => __('No', 'appthemes'),
			'yes' => __('Yes', 'appthemes'),
		)
	),
	
	array(  
		'name' => __('Resume Access Trial Price', 'appthemes'),
		'desc' 		=> __('Only enter numeric values or decimal points. Do not include a currency symbol or commas.', 'appthemes'),
		'tip' 		=> __('This is the amount you want to charge job listers access to the resume database for their first billing term. Leave blank for free trial.','appthemes'),
		'id' 		=> $app_abbr.'_resume_trial_cost',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Trial Length', 'appthemes'),
		'desc' 		=> __('Enter an integer. This length is also affected by the unit below.', 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_trial_length',
		'css' 		=> 'width:75px;',
		'vis' 		=> '',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '1'
	),
	
	array(  
		'name' => __('Trial Unit', 'appthemes'),
		'desc' 		=> __("Select a unit for the trial period.",'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_resume_trial_unit',
		'css' 		=> 'width:100px;',
		'std' 		=> 'M',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'M' => __('Months', 'appthemes'),
			'D'  => __('Days', 'appthemes'),
			'W'  => __('Weeks', 'appthemes'),
			'Y'  => __('Years', 'appthemes')
		)
	),
	
	array( 'type' => 'tabend')

);


$options_integration = array (

	array(  'type' 		=> 'tab', 'tabname' => __('Indeed.com', 'appthemes')),
	
	array(	'name' => __('Main Options', 'appthemes'), 'type' => 'title', 'desc' => '' ),
	
	array(  'name' => '<img src="'.get_bloginfo('template_directory').'/images/indeed-lg.png" />', 'type' 		=> 'logo' ),
	
	/*array(  
		'name' => __('Enable Indeed.com', 'appthemes'),
		'desc' 		=> __("Change this option to enable or disable Indeed.com automatic XML feed posting on your website.",'appthemes'),
		'tip' 		=> __('Earn money by including Indeed.com job listings automatically on your website. It will add valuable content to your site while generating revenue from user clicks. This feature requires an Indeed.com publisher account.','appthemes'),
		'id' 		=> $app_abbr.'_enable_indeed_feeds',
		'css' 		=> 'width:100px;',
		'std' 		=> 'no',
		'js' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes')
		)
	),*/
	
	array(  
		'name' => __('Publisher ID', 'appthemes'),
                'desc' 		=> sprintf( __("Sign up for a free <a target='_new' href='%s'>Indeed.com account</a> to get a publisher ID.",'appthemes'), 'https://ads.indeed.com/jobroll/' ),
		'tip' 		=> __('Enter your Indeed publisher ID (i.e. 4247835648699281).','appthemes'),
		'id' 		=> $app_abbr.'_indeed_publisher_id',
		'css' 		=> 'min-width:350px;',
		'type' 		=> 'text',
		'req' 		=> '',
		'min' 		=> '',
		'std' 		=> '',
		'vis' 		=> ''
	),
/*
	array(  
		'name' => __('Job Listing Queries', 'appthemes'),
		'desc' 		=> sprintf( __("Setup your queries and category mappings to pull in Indeed.com job listings. Each query must be in the following format: <br/><code>keyword|limit|country|job type|post into job category (slug)|location (optional, post code or city)</code>.<br/>Example (10 Full-Time Web Design Jobs in the UK posted to your existing JobRoller category called design):<br/><code>Web Designer|10|GB|fulltime|design</code><br/>One per line. For available country codes and other parameters, see the <a target='_new' href='%s'>Indeed.com XML Feed Guide</a>.",'appthemes'), 'https://ads.indeed.com/jobroll/xmlfeed' ),
                'tip' 		=> __('Each query will run hourly and pull in all matching jobs. Jobs that have expired from Indeed.com, will also be removed from your website.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_queries',
		'css' 		=> 'width:500px;height:150px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),

	array(  
		'name' => __('Post Status', 'appthemes'),
		'desc' 		=> __("Define the status of all jobs pulled in from Indeed.com's XML feed.", 'appthemes'),
		'tip' 		=> __('Set to pending if you want to manually publish the jobs.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_status',
		'css' 		=> 'min-width:200px;',
		'vis' 		=> '',
		'js' 		=> '',
		'std' 		=> 'publish',
		'type' 		=> 'select',
		'options' => array( 'pending' => __('Pending', 'appthemes'),
		'publish' => __('Published', 'appthemes'))
	),
	
	array(  
		'name' => __('Trash indeed jobs after x days', 'appthemes'),
		'desc' 		=> __("Enter the number of days you want to keep indeed jobs before trashing them.", 'appthemes'),
		'tip' 		=> __('Leave blank to never trash jobs.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_auto_trash',
		'vis' 		=> '',
		'css' 		=> 'width:500px;height:150px;',
		'js' 		=> '',
		'std' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text'
	),*/
	
	array(  
		'name' => __('Show indeed results on the front-page?','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This option will dynamically pull in jobs from indeed on your front page.','appthemes'),
		'id' 		=> $app_abbr.'_indeed_front_page',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('Yes', 'appthemes'),
			'no'  => __('No', 'appthemes'),
		)
	),
	
	array(  
		'name' => __('Show x indeed jobs on the front page', 'appthemes'),
		'desc' 		=> __("Enter the number of indeed jobs you want to show on the front page.", 'appthemes'),
		'tip' 		=> '',
		'id' 		=> $app_abbr.'_indeed_front_page_count',
		'std' 		=> '5',
		'req' 		=> '',
		'vis' 		=> '',
		'min' 		=> '',
		'type' 		=> 'text'
	),
	
	array(  
		'name' => __('Front page job listing queries', 'appthemes'),
		'desc' 		=> sprintf( __("Setup your queries and category mappings to pull in Indeed.com job listings on your front page. Each query must be in the following format: <code>keyword|country|job type|location (optional, post code or city)</code>.<br/><br/>Example (Full-Time Web Design Jobs in the UK): <code>Web Designer|GB|fulltime</code><br/><br/>One per line. By default all full-time and part-time jobs are shown from the US. For available country codes and other parameters, see the <a target='_new' href='%s'>Indeed.com XML Feed Guide</a>.",'appthemes'), 'https://ads.indeed.com/jobroll/xmlfeed' ),
                'tip' 		=> __('Each query will be ran and job listings will be merged together and displayed. Do not add too many queries since this will slow your site down significantly.','appthemes'),
		'id' 		=> $app_abbr.'_front_page_indeed_queries',
		'css' 		=> 'width:500px;height:150px;',
		'vis' 		=> '',
		'req' 		=> '',
		'min' 		=> '',
		'type' 		=> 'textarea',
		'std' 		=> ''
	),
	
	array(  
		'name' => __('Show indeed results when searching','appthemes'),
		'desc' 		=> '',
		'tip' 		=> __('This option will dynamically pull in search results from indeed when your job board has no results.','appthemes'),
		'id' 		=> $app_abbr.'_dynamic_search_results',
		'css' 		=> 'min-width:100px;',
		'std' 		=> 'yes',
		'vis' 		=> '',
		'req' 		=> '',
		'js' 		=> '',
		'min' 		=> '',
		'type' 		=> 'select',
		'options' => array(  
			'yes' => __('All the time', 'appthemes'),
			'noresults'  => __('Only when no local results are found', 'appthemes'),
			'no'  => __('Never', 'appthemes'),
		)
	),

	array( 'type' 		=> 'tabend')

);

// pull in the payment gateway options
// this is included separately so it's easy to drop in new payment
// plugins and add-ons without having to touch the core code
if (file_exists(TEMPLATEPATH . '/includes/gateways/admin-gateway-values.php')) include_once (TEMPLATEPATH . '/includes/gateways/admin-gateway-values.php');