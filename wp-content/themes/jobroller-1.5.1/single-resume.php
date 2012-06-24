<?php 
	jr_resumes_are_disabled(); 
	
	global $post;
	
	### Publish
	
	if (isset($_GET['publish']) && $_GET['publish'] && $post->post_author==get_current_user_id()) :
		
		$post_id = $post->ID;
		$post_to_edit = get_post($post_id);

		global $user_ID;

		if ($post_to_edit->ID==$post_id && $post_to_edit->post_author==$user_ID) :
			$update_resume = array();
			$update_resume['ID'] = $post_to_edit->ID;
			if ($post_to_edit->post_status=='private') :
				$update_resume['post_status'] = 'publish';
			else :
				$update_resume['post_status'] = 'private';
			endif;
			wp_update_post( $update_resume );
			wp_safe_redirect(get_permalink($post_to_edit->ID));
		endif;
		
	endif;
?>
<?php get_header(); ?>

	<div class="section single">
	
	<?php appthemes_before_loop(); ?>
		
		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>
			
				<?php appthemes_before_post(); ?>
				
				<?php jr_resume_header($post); ?>

				<?php appthemes_stats_update($post->ID); //records the page hit ?>				

				<div class="section_header resume_header">

					<?php appthemes_before_post_title(); ?>
					
					<?php if (has_post_thumbnail()) the_post_thumbnail('blog-thumbnail'); ?>
					
					<h1 class="title resume-title"><span><?php the_title(); ?></span></h1>
					
					<?php 
						global $message;
						
						// TODO: don't pass the actual message in the URL; pass a key and look it up
						if (isset($_GET['message'])) $message = strip_tags(stripslashes(urldecode($_GET['message'])));

						if ( !empty($message) ) {
							echo '<p class="success">'.$message.'</p>';
						}
					?>

					<div class="user_prefs_wrap" style="display: none"><?php echo jr_seeker_prefs( get_the_author_meta('ID') ); ?></div>
						
					<?php
						### Visibility check
					if (!jr_resume_is_visible() && $post->post_author!=get_current_user_id()) :
						
						echo '<ul class="errors"><li>'.__('Sorry, you do not have permission to view individual resumes.', 'appthemes').'</li></ul>';
						
						if ( jr_viewing_resumes_require_subscription() && jr_current_user_can_subscribe_for_resumes() ) :
		        			
		        			if ($notice = get_option('jr_resume_subscription_notice')) echo '<p>'.wptexturize($notice).'</p>';
		        		
		        			jr_resume_subscribe_button();
		        			
		        			echo '<div class="clear"></div>';

		        		endif;
						
					else :
						
						if ($post->post_status=='private' && $post->post_author==get_current_user_id()) echo '<p class="success">'.sprintf(__('Your resume is currently hidden &mdash; <a href="%s">click here to publish it</a>.', 'appthemes'), add_query_arg('publish', 'true')).'</p>';
					?>
					

						<p class="meta"><?php 
							
							echo __('Resume posted by ','appthemes') . '<strong>' .wptexturize(get_the_author_meta('display_name')) . '</strong>';
							
							$terms = wp_get_post_terms($post->ID, 'resume_category');
							if ($terms) :
								_e(' in ','appthemes');
								echo '<strong>'.$terms[0]->name.'</strong>. ';
							endif;
							
							if ($desired_salary = get_post_meta($post->ID, '_desired_salary', true)) :
								echo sprintf( __('<br/>Desired salary: <strong>%s</strong>. ', 'appthemes'), $desired_salary );
							endif;
							
							$desired_position = wp_get_post_terms($post->ID, 'resume_job_type');
							if ($desired_position) :
								$desired_position = current($desired_position);
								echo '<br/>'.sprintf( __('Desired position type: <strong>%s</strong>. ', 'appthemes'), $desired_position->name );
							else :
								echo '<br/>'.__('Desired position type: <strong>Any</strong>. ', 'appthemes');
							endif;
							
							if ($address = get_post_meta($post->ID, 'geo_short_address', true)) :
								echo '<br/>'.__('Location: ', 'appthemes');
								echo wptexturize($address). ' ';
								echo wptexturize(get_post_meta($post->ID, 'geo_short_address_country', true));
							endif;
						?></p>
						
						<?php
							$contact_details = array();
							$contact_details['mobile'] = get_post_meta($post->ID, '_mobile', true);
							$contact_details['tel'] = get_post_meta($post->ID, '_tel', true);
							$contact_details['email_address'] = get_post_meta($post->ID, '_email_address', true);
							
							if ($contact_details && is_array($contact_details) && sizeof($contact_details)>0) :
								echo '<dl>';
								if ($contact_details['email_address']) echo '<dt class="email">'.__('Email','appthemes').':</dt><dd><a href="mailto:'.$contact_details['email_address'].'?subject='.__('Your Resume on','appthemes').' '.get_bloginfo('name').'">'.$contact_details['email_address'].'</a></dd>';
								if ($contact_details['tel']) echo '<dt class="tel">'.__('Tel','appthemes').':</dt><dd>'.$contact_details['tel'].'</dd>';
								if ($contact_details['mobile']) echo '<dt class="mobile">'.__('Mobile','appthemes').':</dt><dd>'.$contact_details['mobile'].'</dd>';
								echo '</dl>';
							endif;	
							
							$websites = get_post_meta($post->ID, '_resume_websites', true);
							
							if ($websites && is_array($websites)) :
								$loop = 0;
								echo '<dl>';
								foreach ($websites as $website) :
								echo '<dt class="email">'.strip_tags($website['name']).':</dt><dd><a href="'.esc_url($website['url']).'" target="_blank" rel="nofollow">'.strip_tags($website['url']).'</a>';
								if (get_the_author_meta('ID')==get_current_user_id()) echo ' <a class="delete" href="?delete_website='.$loop.'">[&times;]</a>';
								echo '</dd>';
								$loop++;
								endforeach;
								echo '</dl>';
							endif;
							if (get_the_author_meta('ID')==get_current_user_id()) echo '<p class="edit_button button"><a class="inline noscroll" href="#websites">'.__('+ Add Website', 'appthemes').'</a></p>';
						?>

						<?php appthemes_after_post_title(); ?>

					</div><!-- end section_header -->
	
					<div class="section_content">
	
						<?php do_action('resume_main_section', $post); ?>
	
						<?php appthemes_before_post_content(); ?>
						
						<h2 class="resume_section_heading"><span><?php _e('Summary', 'appthemes'); ?></span></h2>
						<div class="resume_section summary">
							<?php the_content(); ?>
						</div>
						<div class="clear"></div>
						
						<?php appthemes_after_post_content(); ?>
						
						<?php
							
							$display_sections = array(
								'resume_specialities' => __('Specialities', 'appthemes'),
								'skills' => __('Skills', 'appthemes'),
								'resume_languages' => __('Spoken Languages', 'appthemes'),
								'education' => __('Education', 'appthemes'),
								'experience' => __('Experience', 'appthemes'),
								'resume_groups' => __('Groups &amp; Associations', 'appthemes')
							);
							
							foreach ($display_sections as $term => $section) :
							
								switch ($term) :
									
									case "experience" : 
										?>
										<h2 class="resume_section_heading"><span><?php echo $section; ?></span></h2>
										<div class="resume_section">
											<?php echo wpautop(wptexturize(get_post_meta($post->ID, '_experience', true))); ?>
										</div>
										<div class="clear"></div>
										<?php
									break;
									case "education" : 
										?>
										<h2 class="resume_section_heading"><span><?php echo $section; ?></span></h2>
										<div class="resume_section">
											<?php echo wpautop(wptexturize(get_post_meta($post->ID, '_education', true))); ?>
										</div>
										<div class="clear"></div>
										<?php
									break;
									case "skills" :
										$skills = array_map('trim', explode("\n", get_post_meta($post->ID, '_skills', true)));
										if ($skills) :
											?>
											<h2 class="resume_section_heading"><span><?php echo $section; ?></span></h2>
											<div class="resume_section">
												<?php 
												echo '<ul>';
													foreach ($skills as $skill) :
														if ($skill) echo '<li>'.wptexturize($skill).'</li>';
													endforeach;
												echo '</ul>';
												?>
											</div>
											<div class="clear"></div>
											<?php
										endif;
									break;
									default :
										$terms = wp_get_post_terms($post->ID, $term);
										if ($terms) :
											?>
											<h2 class="resume_section_heading"><span><?php echo $section; ?></span></h2>
											<div class="resume_section">
												<?php 
												$terms_array = array();
												foreach ($terms as $t) :
													if (sizeof($terms_array) != (sizeof($terms) -1)) :
														$terms_array[] = $t->name . ', ';
													else :
														$terms_array[] = $t->name;
													endif;
												endforeach;
												echo '<ul class="terms"><li>'.implode('</li><li>', $terms_array).'</li></ul>'; 
												?>
											</div>
											<div class="clear"></div>
											<?php
										endif;
									break;
									
								endswitch;
							
							endforeach;
						?>
						
						<?php if (get_the_author_meta('ID')==get_current_user_id()) : ?>
							<p class="button edit_resume"><a href="<?php echo add_query_arg('edit', $post->ID, get_permalink(get_option('jr_job_seeker_resume_page_id'))); ?>"><?php _e('Edit Resume&nbsp;&rarr;','appthemes'); ?></a></p>
						<?php endif; ?>
						
						<?php if (get_option('jr_ad_stats_all') == 'yes') { ?><p class="stats"><?php appthemes_stats_counter($post->ID); ?></p> <?php } ?>
	
						<div class="clear"></div>
						
					<?php endif; ?>

				</div><!-- end section_content -->
				
				<?php appthemes_after_post(); ?>
				
				<?php jr_resume_footer($post); ?>

			<?php endwhile; ?>

				<?php appthemes_after_endwhile(); ?>

		<?php else: ?>

			<?php appthemes_loop_else(); ?>

		<?php endif; ?>	

		<?php appthemes_after_loop(); ?>

	</div><!-- end section -->	

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_the_author_meta('ID')==get_current_user_id()) : ?>
	<script type="text/javascript">
	/* <![CDATA[ */
		
		jQuery('p.edit_button a, a.edit_button').fancybox({
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'overlayShow'	:	true,
			'centerOnScroll':	true,
			'overlayColor'	:	'#555',
			'hideOnOverlayClick' : false
		});
		
		jQuery('a.delete').click(function(){
    		var answer = confirm ("<?php _e('Are you sure you want to delete this? This action cannot be undone...', 'appthemes'); ?>")
			if (answer)
				return true;
			return false;
    	});
		
	/* ]]> */
	</script>
	<?php 
	if (get_option('jr_show_sidebar')!=='no') : get_sidebar('user'); endif; 
else :
	if (get_option('jr_show_sidebar')!=='no') : get_sidebar('resume'); endif; 
endif; 
?>

<?php get_footer(); ?>
