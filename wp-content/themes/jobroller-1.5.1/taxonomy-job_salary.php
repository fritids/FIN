<?php get_header('search'); ?>
	
<?php do_action('jobs_will_display'); ?>

	<div class="section">

		<?php
		$job_salary_slug = get_query_var('job_salary');
		$job_salary = get_term_by( 'slug', $job_salary_slug, 'job_salary');
		?>

		<h1 class="pagetitle"><?php echo '<small class="rss"><a href="'.jr_get_current_url().'rss"><img src="'.get_bloginfo('template_url').'/images/feed.png" title="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" alt="'.single_cat_title("", false).' '.__('Jobs RSS Feed','appthemes').'" /></a></small>'; ?> <?php _e('Jobs with a salary of','appthemes'); ?> <?php echo wptexturize($job_salary->name); ?> <?php
		?></h1>

		<?php get_template_part( 'loop', 'job' ); ?>

		<?php jr_paging(); ?>
		
		<div class="clear"></div>

	</div><!-- end section -->

	<div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>