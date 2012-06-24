<?php

/**
 * Add footer elements via the wp_footer hook
 *
 * Anything you add to this file will be dynamically
 * inserted in the footer of your theme
 *
 * @since 1.0
 * @uses jr_footer_actions
 *
 */
 
 
// add the footer contents to the bottom of the page 
function jr_do_footer() {
?> 
	<div class="inner">

		<p><?php _e('Copyright &copy;','appthemes'); ?> <?php echo date_i18n('Y'); ?> <?php bloginfo('name'); ?>. <a href="http://www.appthemes.com/themes/jobroller/" target="_blank">Job Board Software</a> | <?php _e('Powered by','appthemes'); ?> <a href="http://wordpress.org" target="_blank">WordPress</a></p>

	</div><!-- end inner -->
<?php
}
// hook into the correct action
add_action('appthemes_footer', 'jr_do_footer');


// insert the google analytics tracking code in the footer
function jr_google_analytics_code() {

    echo "\n\n" . '<!-- start wp_footer -->' . "\n\n";

    if (get_option('jr_google_analytics') <> '')
        echo stripslashes(get_option('jr_google_analytics'));

    echo "\n\n" . '<!-- end wp_footer -->' . "\n\n";

}

add_action('wp_footer', 'jr_google_analytics_code');


?>