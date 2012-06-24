<?php
/*
Template Name: My Jobs Template
*/
?>

<?php
### Prevent Caching
nocache_headers();

appthemes_auth_redirect_login();
if (!current_user_can('can_submit_job')) redirect_profile();

$message = '';
$myjobsID = $post->ID;

global $userdata;
?>

<?php get_header(); ?>

	<div class="section myjobs">
		
		<div class="section_content">
		
		<?php if (isset($_GET['remove_listing']) && is_numeric($_GET['remove_listing'])) : ?>
			
			<?php
			
				if (isset($_GET['confirm'])) :

					$post_id = $_GET['remove_listing'];
					$post_to_remove = get_post($post_id);

					global $user_ID;

					if ($post_to_remove->ID==$post_id && $post_to_remove->post_author==$user_ID) :
					//wp_delete_post($post_to_remove->ID);

					$job_post = array();
					$job_post['ID'] = $post_id;
					$job_post['post_status'] = 'private';
					wp_update_post( $job_post );
						$message = __('Job listing was ended early.','appthemes');
					else :
						header('Location: '.get_permalink($myjobsID));
						exit;
					endif;

				else :

					$post_id = $_GET['remove_listing'];
					$post_to_remove = get_post($post_id);

					global $user_ID;

					if ($post_to_remove->ID==$post_id && $post_to_remove->post_author==$user_ID && $post_to_remove->post_status=='publish') :
							$message = __('Are you sure you want to end ','appthemes');
							$message .= '&ldquo;'.$post_to_remove->post_title.'&rdquo; [<a href="'.trailingslashit(get_permalink($myjobsID)).'?remove_listing='.$post_to_remove->ID.'&amp;confirm=true">'.__('Yes','appthemes').'</a>] [<a href="'.trailingslashit(get_permalink($myjobsID)).'">'.__('No','appthemes').'</a>]';
					else :
							header('Location: '.trailingslashit(get_permalink($myjobsID)));
							exit;
					endif;

				endif;
			?>
			
		<?php elseif(isset($_GET['pay_for_listing']) && is_numeric($_GET['pay_for_listing'])) : 
			
			global $user_ID;
			
			$post_id = $_GET['pay_for_listing'];
			
			$jr_order = new jr_order();
			
			if ($jr_order->find_order_for_job($post_id)) :
				
				if ($jr_order->status=='pending_payment' && $jr_order->user_id==$user_ID) :
					
					$job_post = get_post($jr_order->job_id); 
					header('Location: '.$jr_order->generate_paypal_link());
					exit;
				
				endif;
				
			endif;
			
		endif; ?>
		
		<h1><?php printf(__("%s's Dashboard", 'appthemes'), ucwords($userdata->user_login)); ?></h1>
		
		<?php 
			if (isset($_GET['message'])) $message = stripslashes(urldecode($_GET['message']));
		
			if (isset($message) && !empty($message)) {
				echo '<p class="success">'.$message.'</p>';
			}
		?>
		
		<ul class="display_section">
			<li><a href="#live" class="noscroll"><?php _e('Live', 'appthemes'); ?></a></li>
			<li><a href="#pending" class="noscroll"><?php _e('Pending', 'appthemes'); ?></a></li>
			<li><a href="#ended" class="noscroll"><?php _e('Ended/Expired', 'appthemes'); ?></a></li>
			<?php if (sizeof(jr_get_job_packs())>0) : ?><li><a href="#packs" class="noscroll"><?php _e('Job Packs', 'appthemes'); ?></a></li><?php endif; ?>
		</ul>
		
		<div id="live" class="myjobs_section">
		
			<h2><?php _e('Live Jobs', 'appthemes'); ?></h2>
				
				<p><?php _e('Below you will find a list of jobs you have previously posted which are visible on the site.', 'appthemes'); ?></p>
		
				<table cellpadding="0" cellspacing="0" class="data_list">
					<thead>
						<tr>
							<th><?php _e('Job Title','appthemes'); ?></th>
							<th class="center"><?php _e('Date Posted','appthemes'); ?></th>
							<th class="center"><?php _e('Days Remaining','appthemes'); ?></th>
							<th class="center"><?php _e('Views','appthemes'); ?></th>
							<th class="right"><?php _e('Actions','appthemes'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							global $user_ID;
							$args = array(
									'ignore_sticky_posts'	=> 1,
									'posts_per_page' => -1,
									'author' => $user_ID,
									'post_type' => 'job_listing',
									'post_status' => 'publish'
							);
							$my_query = new WP_Query($args);
							$count = 0;
						?>
						<?php if ($my_query->have_posts()) : ?>
						
							<?php while ($my_query->have_posts()) : ?>
							
								<?php $my_query->the_post(); ?>

								<?php if (get_post_meta($my_query->post->ID, 'jr_total_count', true)) $job_views = number_format(get_post_meta($my_query->post->ID, 'jr_total_count', true)); else $job_views = '-'; ?>
								
						
								<?php if (jr_check_expired($post)) continue; ?>

								<tr>
									<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></td>
									<td class="date"><strong><?php the_time(__('j M','appthemes')); ?></strong> <span class="year"><?php the_time(__('Y','appthemes')); ?></span></td>
									<td class="center days"><?php echo jr_remaining_days($my_query->post); ?></td>
									<td class="center"><?php echo $job_views; ?></td>
									<td class="actions"><?php if (get_option('jr_allow_editing')=='yes') : ?><a href="<?php echo add_query_arg('edit', $my_query->post->ID, get_permalink(get_option('jr_edit_job_page_id'))); ?>"><?php _e('Edit&nbsp;&rarr;','appthemes'); ?></a>&nbsp;<?php endif; ?><a href="<?php echo add_query_arg('remove_listing', $my_query->post->ID, get_permalink($myjobsID)); ?>" class="delete"><?php _e('End','appthemes'); ?></a></td>
								</tr>
								<?php 
								$count++; 
							endwhile;
							if ($count==0) : ?>
								<tr>
									<td colspan="4"><?php _e('No live jobs found.','appthemes'); ?></td>
								</tr>
							<?php endif;
						endif; 
						
						?>
					</tbody>
				</table>
				
			</div>
			
			<?php if (sizeof(jr_get_job_packs())>0) : ?>
			<div id="packs" class="myjobs_section">
			
				<h2><?php _e('My Packs', 'appthemes'); ?></h2>
				<?php
					$user_packs = jr_get_user_job_packs();
					if (sizeof($user_packs)>0) :
					
						?><p><?php _e('Below you will find a list of active packs you have purchased.', 'appthemes'); ?></p><?php
						
						echo '<ul class="packs">';
						
						if (sizeof($user_packs)>0) foreach ($user_packs as $pack) :
						
							if (!$pack->jobs_limit) :
								$pack->jobs_count = __('Unlimited', 'appthemes').' '.__('Jobs remaining', 'appthemes');
							else :
								$pack->jobs_count = $pack->jobs_limit - $pack->jobs_count;
								if ($pack->jobs_count==1) $pack->jobs_count = $pack->jobs_count.' '.__('Job remaining', 'appthemes');
								else $pack->jobs_count = $pack->jobs_count.' '.__('Jobs remaining', 'appthemes');
							endif;
							
							if ($pack->pack_expires>0) $pack->pack_expires = __('Usable before ', 'appthemes').mysql2date(get_option('date_format'), $pack->pack_expires).'.'; else $pack->pack_expires = '';
							
							if ($pack->job_duration) $pack->job_duration = __(' lasting ', 'appthemes').$pack->job_duration.__(' days' ,'appthemes');
							
							echo '<li><span class="cost">'.__('Purchased', 'appthemes').'</span><h3>'.$pack->pack_name.'</h3>
								<p>'.$pack->jobs_count.''.$pack->job_duration.'. '.$pack->pack_expires.'</p>
							</li>';
						endforeach;
						
						echo '</ul>';
					
					else :
						?><p><?php _e('No active packs found.', 'appthemes'); ?></p><?php
					endif;
				?>
			
			</div>
			<?php endif; ?>
			
			<div id="pending" class="myjobs_section">
			
				<h2><?php _e('Pending Jobs', 'appthemes'); ?></h2>
				
				<?php
					global $user_ID;
					$args = array(
						'ignore_sticky_posts'	=> 1,
						'posts_per_page' => -1,
						'author' => $user_ID,
						'post_type' => 'job_listing',
						'post_status' => 'pending'
					);
					$my_query = new WP_Query($args);
				?>
				<?php if ($my_query->have_posts()) : ?>

				<p><?php _e('The following jobs are pending and are not visible to users.', 'appthemes'); ?></p>
				
				<table cellpadding="0" cellspacing="0" class="data_list">
					<thead>
						<tr>
							<th><?php _e('Job Title','appthemes'); ?></th>
							<th class="center"><?php _e('Date Posted','appthemes'); ?></th>
							<th class="center"><?php _e('Status','appthemes'); ?></th>
							<th class="right"><?php _e('Actions','appthemes'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
							<tr>
								<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></td>
								<td class="date"><strong><?php the_time(__('j M','appthemes')); ?></strong> <span class="year"><?php the_time(__('Y','appthemes')); ?></span></td>
								<td class="center"><?php 
									$jr_order = new jr_order();
			
									if ($jr_order->find_order_for_job($my_query->post->ID)) :
										if ($jr_order->status!='completed') :
											echo __('Pending Payment', 'appthemes');
										else :
											echo __('Pending Approval', 'appthemes');
										endif;
									else :
										echo __('Pending', 'appthemes');
									endif;				
								?></td>
								<td class="actions">
									<?php 
										if ($jr_order->status && $jr_order->status!='completed') : ?><a href="<?php echo trailingslashit(get_permalink($myjobsID)); ?>?pay_for_listing=<?php echo $my_query->post->ID; ?>"><?php _e('Pay&nbsp;&rarr;','appthemes'); ?></a>&nbsp;<?php 
										endif; 
										if (get_option('jr_allow_editing')=='yes') :
											?><a href="<?php echo trailingslashit(get_permalink(get_option('jr_edit_job_page_id'))); ?>?edit=<?php echo $my_query->post->ID; ?>"><?php _e('Edit&nbsp;&rarr;','appthemes'); ?></a>&nbsp;<?php
										endif;
										?><a href="<?php echo trailingslashit(get_permalink($myjobsID)); ?>?remove_listing=<?php echo $my_query->post->ID; ?>" class="delete"><?php _e('Cancel','appthemes'); ?></a>
								</td>
							</tr>
						<?php endwhile; ?>				
					</tbody>
				</table>
				<?php else : ?>
					<p><?php _e('No pending jobs found.', 'appthemes'); ?></p>
				<?php endif; ?>
			</div>
			
			<div id="ended" class="myjobs_section">
			
				<?php
					global $user_ID;
					$args = array(
						'ignore_sticky_posts'	=> 1,
						'posts_per_page' => -1,
						'author' => $user_ID,
						'post_type' => 'job_listing',
						'post_status' => 'private'
					);
					$my_query = new WP_Query($args);
					$args = array(
							'ignore_sticky_posts'	=> 1,
							'posts_per_page' => -1,
							'author' => $user_ID,
							'post_type' => 'job_listing',
							'post_status' => 'publish'
					);
					$my_query2 = new WP_Query($args);
					$count = 0;
				?>
				<?php if ($my_query->have_posts() || $my_query2->have_posts()) : ?>
				<h2><?php _e('Ended/Expired Jobs', 'appthemes'); ?></h2>
				
				<p><?php _e('The following jobs have expired or have been ended and are not visible to users.', 'appthemes'); ?></p>
				
				<table cellpadding="0" cellspacing="0" class="data_list">
					<thead>
						<tr>
							<th><?php _e('Job Title','appthemes'); ?></th>
							<th class="center"><?php _e('Date Posted','appthemes'); ?></th>
							<th class="center"><?php _e('Status','appthemes'); ?></th>
							<th class="center"><?php _e('Views','appthemes'); ?></th>
							<th class="right"><?php _e('Actions','appthemes'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($my_query->have_posts()) while ($my_query->have_posts()) : $my_query->the_post(); ?>

						<?php if (get_post_meta($my_query->post->ID, 'jr_total_count', true)) $job_views = number_format(get_post_meta($my_query->post->ID, 'jr_total_count', true)); else $job_views = '-'; ?>

							<tr>
								<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></td>
								<td class="date"><strong><?php the_time(__('j M','appthemes')); ?></strong> <span class="year"><?php the_time(__('Y','appthemes')); ?></span></td>
								<td class="center"><?php 
									$jr_order = new jr_order();
			
									if ($jr_order->find_order_for_job($my_query->post->ID)) :
										if ($jr_order->status!='completed') :
											echo __('Ended (order incomplete)', 'appthemes');
										else :
											echo __('Ended', 'appthemes');
										endif;
									else :
										echo __('Ended', 'appthemes');
									endif;				
								?></td>
								<td class="center"><?php echo $job_views; ?></td>
								<td class="actions">
									<?php if (get_option('jr_allow_relist')=='yes') : ?><a href="<?php echo trailingslashit(get_permalink(get_option('jr_edit_job_page_id'))); ?>?edit=<?php echo $my_query->post->ID; ?>&amp;relist=true"><?php _e('Relist&nbsp;&rarr;','appthemes'); ?></a><?php endif; ?>
								</td>
							</tr>
						<?php $count++; endwhile; ?>
						<?php if ($my_query2->have_posts()) while ($my_query2->have_posts()) : $my_query2->the_post(); if (!jr_check_expired($post)) continue; ?>
							<tr>
								<td><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></td>
								<td class="date"><strong><?php the_time(__('j M','appthemes')); ?></strong> <span class="year"><?php the_time(__('Y','appthemes')); ?></span></td>
								<td class="center"><?php _e('Expired', 'appthemes'); ?></td>
								<td class="center"><?php echo $job_views; ?></td>
								<td class="actions">
									<?php if (get_option('jr_allow_relist')=='yes') : ?><a href="<?php echo trailingslashit(get_permalink(get_option('jr_edit_job_page_id'))); ?>?edit=<?php echo $my_query2->post->ID; ?>&amp;relist=true"><?php _e('Relist&nbsp;&rarr;','appthemes'); ?></a><?php endif; ?>
								</td>
							</tr>
						<?php $count++; endwhile;
						
						if ($count==0) : ?>
								<tr>
									<td colspan="4"><?php _e('No jobs found.','appthemes'); ?></td>
								</tr>
						<?php endif; ?>			
					</tbody>
				</table>
				<?php endif; ?>
			</div>
			
			<script type="text/javascript">
				/* <![CDATA[ */
					jQuery(function() {
						jQuery('a.delete').click(function(){
							var answer = confirm("<?php _e('Are you sure you want to end this job listing? This action cannot be undone.',"appthemes"); ?>")
							if (answer){
								jQuery(this).attr('href', jQuery(this).attr('href') + '&confirm=true');
								return true;
							}
							else{
								return false;
							}					
						});	
						
						jQuery('.myjobs ul.display_section li a').click(function(){
							
							jQuery('.myjobs div.myjobs_section').hide();
							
							jQuery(jQuery(this).attr('href')).show();
							
							jQuery('.myjobs ul.display_section li').removeClass('active');
							
							jQuery(this).parent().addClass('active');
							
							return false;
						});
						jQuery('.myjobs ul.display_section li a:eq(0)').click();
					});
				/* ]]> */
			</script>

		</div><!-- end section_content -->

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar('user'); ?>

<?php get_footer(); ?>