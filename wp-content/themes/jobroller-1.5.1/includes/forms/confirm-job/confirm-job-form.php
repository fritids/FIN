<?php
/**
 * JobRoller Confirm Job form
 * Function outputs the job confirmation form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_confirm_job_form() {
	
	global $post, $posted, $wpdb;

	$posted = json_decode(stripslashes($_POST['posted']), true);
	$cost = 0;
	$payment_required = false;
	
	// Get Pack from previous step
	if (isset($_POST['job_pack']) && !empty($_POST['job_pack'])) : 
		$posted['job_pack'] = stripslashes(trim($_POST['job_pack']));
		if (strstr($posted['job_pack'], 'user_')) :
			// This is a user's pack and has already been purchased
		else :
			// Get pack price
			$cost += $wpdb->get_var("SELECT pack_cost FROM ".$wpdb->prefix."jr_job_packs WHERE id = ".$wpdb->escape($posted['job_pack'])." LIMIT 1;");
		endif;
	else :
		// No Packs
		$posted['job_pack'] = '';
		$listing_cost = get_option('jr_jobs_listing_cost');
		$cost += $listing_cost;
	endif;
	
	// Get Featured from previous step
	if (isset($_POST['featureit']) && $_POST['featureit']) :
		$posted['featureit'] = 'yes';
		$featured_cost = get_option('jr_cost_to_feature');
		$cost += $featured_cost;
	else :
		$posted['featureit'] = '';
	endif;
	
	if ($cost > 0) :
		$payment_required = true;
	endif;
	?>
	<form action="<?php echo get_permalink( $post->ID ); ?>" method="post" enctype="multipart/form-data" id="submit_form" class="submit_form main_form">			
		<p><?php _e('Your job is ready to be submitted, check the details are correct and then click &ldquo;confirm&rdquo; to submit your listing', 'appthemes'); ?><?php 
			if (get_option('jr_jobs_require_moderation')=='yes') _e(' for approval', 'appthemes');
		?>.</p>
		
		<blockquote>
			<h2><?php 
				echo wptexturize($posted['job_term_type']).' &ndash; '; 
				echo wptexturize($posted['job_title']); 
			?></h2>
			<?php if ($posted['your_name']) : ?>
			<h3><?php _e('Company/Poster','appthemes'); ?></h3>
			<p><?php
				if ($posted['website'])
					echo '<a href="'. strip_tags($posted['website']).'">';
				echo strip_tags($posted['your_name']);
				if ($posted['website'])
					echo '</a>';
			?></p>
			<?php endif; ?>
			<h3><?php _e('Job description','appthemes'); ?></h3>
			<?php echo wpautop(wptexturize($posted['details'])); ?>
			<?php if (get_option('jr_submit_how_to_apply_display')=='yes') : ?>
				<h3><?php _e('How to apply','appthemes'); ?></h3>
				<?php echo wpautop(wptexturize($posted['apply'])); ?>
			<?php endif; ?>
		</blockquote>
		
		<?php
		if ($payment_required) :
			?>
			<p><?php 
				_e('After confirming your submission you will be taken to the payment page and charged ', 'appthemes');
				echo '<strong>'.jr_get_currency($cost).'</strong>';
				_e(' &mdash; as soon as your payment clears your listing will become active.','appthemes'); ?></p>
			<?php
		endif;	
		?>
		
		<p>
            <input type="submit" name="goback" class="goback" value="<?php _e('Go Back','appthemes') ?>"  />
            <input type="submit" class="submit" name="confirm" value="<?php _e('Confirm &amp; Submit', 'appthemes'); ?>" />
            <input type="hidden" value='<?php echo htmlentities(json_encode($posted), ENT_QUOTES); ?>' name="posted" />
        </p>
		
		<div class="clear"></div>
	</form>	
	<?php

}