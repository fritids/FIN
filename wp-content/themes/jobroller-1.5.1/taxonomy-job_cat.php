<?php 
	get_header('search');
	
	do_action('jobs_will_display');
?>
	<div class="section">

		  <?php
		  
		  $job_cat_slug = get_query_var('job_cat');
		  $job_cat = get_term_by( 'slug', $job_cat_slug, 'job_cat');
		  
		  ?>
		  
		<h1 class="pagetitle"><?php echo '<small class="rss"><a href="'.jr_get_current_url().'rss"><img src="'.get_bloginfo('template_url').'/images/feed.png" title="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" alt="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" /></a></small>'; ?> <?php echo wptexturize($job_cat->name); ?> <?php _e('Jobs','appthemes'); ?> <?php
		
			if (isset($_GET['action']) && $_GET['action']=='Filter') echo '<small>&mdash; <a href="'.jr_get_current_url().'">'.__('Remove Filters','appthemes').'</a></small>';
		
		?></h1>
		
		<?php 
			$args = jr_filter_form();
			$args = array_merge(
				array(
					'job_cat' => $job_cat_slug
				),
				$args
			);
			query_posts($args);
		?>
		
		<?php get_template_part( 'loop', 'job' ); ?>
		
		<?php jr_paging(); ?>
		
		<div class="clear"></div>
		
	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>

