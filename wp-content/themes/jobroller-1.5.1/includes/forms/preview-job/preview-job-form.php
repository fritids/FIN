<?php
/**
 * JobRoller Preview Job form
 * Function outputs the job preview form
 *
 *
 * @version 1.0
 * @author AppThemes
 * @package JobRoller
 * @copyright 2010 all rights reserved
 *
 */

function jr_preview_job_form() {
	
	global $post, $posted;
	?>
	<form action="<?php echo get_permalink( $post->ID ); ?>" method="post" enctype="multipart/form-data" id="submit_form" class="submit_form main_form">			
		<p><?php _e('Below is a preview of what your job listing will look like when published:', 'appthemes'); ?></p>				
		
		<ol class="jobs">
			<li class="job <?php if ($alt==1) echo 'job-alt'; ?>" style="padding-left:0; padding-right:0;"><dl>
				<dt><?php _e('Type','appthemes'); ?></dt>
				<dd class="type"><?php
					$job_type = get_term_by( 'slug', sanitize_title($posted['job_term_type']), 'job_type');		
					echo '<span class="'.$job_type->slug.'">'.wptexturize($job_type->name).'</span>';			
				?>&nbsp;</dd>
				<dt><?php _e('Job', 'appthemes'); ?></dt>
				<dd class="title"><strong><?php echo $posted['job_title']; ?> </strong><?php
					
					$author = get_user_by('id', get_current_user_id());
					
					if ($posted['your_name']) :
						echo $posted['your_name'];
						if ($author && $link = get_author_posts_url( $author->ID, $author->user_nicename )) :
							echo sprintf( __(' &ndash; Posted by <a href="%s">%s</a>', 'appthemes'), $link, $author->display_name );
						endif;
					else :
						if ($author && $link = get_author_posts_url( $author->ID, $author->user_nicename )) :
							echo sprintf( __('<a href="%s">%s</a>', 'appthemes'), $link, $author->display_name );
						endif;
					endif;
					
					?>
				</dd>
				<dt><?php _e('_Location', 'appthemes'); ?></dt>
				<dd class="location"><?php
				
					$latitude = jr_clean_coordinate($posted['jr_geo_latitude']);
					$longitude = jr_clean_coordinate($posted['jr_geo_longitude']);
					
					if ($latitude && $longitude) :
						$address = jr_reverse_geocode($latitude, $longitude);
						echo '<strong>'.wptexturize($address['short_address']).'</strong> '.wptexturize($address['short_address_country']).'';
					else :
						echo '<strong>Anywhere</strong>';
					endif;
				?></dd>
				<dt><?php _e('Date Posted', 'appthemes'); ?></dt>
				<dd class="date"><strong><?php echo date_i18n(__('j M','appthemes')); ?></strong> <span class="year"><?php echo date_i18n(__('Y','appthemes')); ?></span></dd>
			</dl></li>
		</ol>
		
		<p><?php _e('The job listing&rsquo;s page will contain the following information:', 'appthemes'); ?></p>
		
		<blockquote>
			<h2><?php _e('Job description','appthemes'); ?></h2>
			<?php echo wpautop(wptexturize($posted['details'])); ?>
			<?php if (get_option('jr_submit_how_to_apply_display')=='yes') : ?>
				<h2><?php _e('How to apply','appthemes'); ?></h2>
				<?php echo wpautop(wptexturize($posted['apply'])); ?>
			<?php endif; ?>
		</blockquote>
		
		<?php
			$packs = jr_get_job_packs();
			$user_packs = jr_get_user_job_packs();
			if (sizeof($packs) > 0 || sizeof($user_packs)>0) :
			
				echo '<h2>'.__('Select a Job Pack:', 'appthemes').'</h2>';
				
				echo '<ul class="packs">';
				
				$checked = 'checked="checked"';
				
				if (sizeof($user_packs)>0) foreach ($user_packs as $pack) :
				
					$choose_or_use = __('Choose this pack', 'appthemes');
					
					if (!$pack->jobs_limit) :
						$pack->jobs_count = __('Unlimited', 'appthemes').' '.__('Jobs remaining', 'appthemes');
					else :
						$pack->jobs_count = $pack->jobs_limit - $pack->jobs_count;
						if ($pack->jobs_count==1) $pack->jobs_count = $pack->jobs_count.' '.__('Job remaining', 'appthemes');
						else $pack->jobs_count = $pack->jobs_count.' '.__('Jobs remaining', 'appthemes');
					endif;
					
					if ($pack->pack_expires) $pack->pack_expires = __('Usable before ', 'appthemes').mysql2date(get_option('date_format'), $pack->pack_expires);
					
					if ($pack->job_duration) $pack->job_duration = __(' lasting ', 'appthemes').$pack->job_duration.__(' days' ,'appthemes');
					
					echo '<li><span class="cost">'.__('Purchased', 'appthemes').'</span><h3>'.$pack->pack_name.'</h3>
						<p>'.$pack->jobs_count.''.$pack->job_duration.'. '.$pack->pack_expires.'.</p>
						<div><label>'.$choose_or_use.': <input type="radio" name="job_pack" value="user_'.$pack->id.'" '.$checked.' /></label></div>
					</li>';
					
					$checked = '';
				endforeach;
				
				if (sizeof($packs)>0) foreach ($packs as $pack) :
				
					$choose_or_use = '';
					
					if (!$pack->job_count) $pack->job_count = __('Unlimited', 'appthemes');
					
					if ($pack->pack_duration) $pack->pack_duration = __(' usable within ', 'appthemes').$pack->pack_duration.__(' days', 'appthemes');
					
					if ($pack->job_duration) $pack->job_duration = __(' lasting ', 'appthemes').$pack->job_duration.__(' days' ,'appthemes');
					
					if ($pack->pack_cost) :
						$pack->pack_cost = jr_get_currency($pack->pack_cost).'';
						$choose_or_use = __('Buy this pack', 'appthemes');
					else :
						$pack->pack_cost = __('Free','appthemes');
						$choose_or_use = __('Choose this pack', 'appthemes');
					endif; 
					
					echo '<li><span class="cost">'.$pack->pack_cost.'</span><h3>'.$pack->pack_name.' &ndash; <small>'.$pack->pack_description.'</small></h3>
						<p>'.$pack->job_count.' '.__('Jobs', 'appthemes').''.$pack->job_duration.$pack->pack_duration.'.</p>
						<div><label>'.$choose_or_use.': <input type="radio" name="job_pack" value="'.$pack->id.'" '.$checked.' /></label></div>
					</li>';
					
					$checked = '';
				endforeach;
				
				echo '</ul>';
				
			endif;
		?>

		<?php
			$featured_cost = get_option('jr_cost_to_feature');
			if ($featured_cost && is_numeric($featured_cost) && $featured_cost > 0) :
				
				// Featuring is an option
				echo '<h2>'.__('Feature your listing for ', 'appthemes').jr_get_currency($featured_cost).__('?', 'appthemes').'</h2>';
				
				echo '<p>'.__('Featured listings are displayed on the homepage and are also highlighted in all other listing pages.', 'appthemes').'</p>';
				
				echo '<p><input type="checkbox" name="featureit" id="featureit" /> <label for="featureit" style="float:none">'.__('Yes please, feature my listing.', 'appthemes').'</label></p>';
				
			endif;
		?>
		
		<p>
            <input type="submit" name="goback" class="goback" value="<?php _e('Go Back','appthemes') ?>"  /> 
            <input type="submit" class="submit" name="preview_submit" value="<?php _e('Next &rarr;', 'appthemes'); ?>" />
            <input type="hidden" value='<?php echo htmlentities(json_encode($posted), ENT_QUOTES); ?>' name="posted" />
        </p>
		
		<div class="clear"></div>
	</form>
	<?php

}