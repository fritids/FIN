<?php get_header('search'); ?>

    <div class="section" id="profile">    

		<div class="section_content">

	        <?php echo get_avatar( $wp_query->get_queried_object()->ID, '96' ); ?>
			
			<h1><?php echo wptexturize( $wp_query->get_queried_object()->display_name ); ?> <?php if ($url = $wp_query->get_queried_object()->user_url) echo ' &ndash; <a href="'.esc_url($url).'">'. strip_tags($url) .'</a>'; ?></a></h1>
			
			
			
			<?php 
			if (isset($wp_query->get_queried_object()->description) && !empty($wp_query->get_queried_object()->description)) 
				echo wpautop( wptexturize( $wp_query->get_queried_object()->description )); 
			?>
			
			<?php
				$social = array();
				if ($twitter = get_user_meta( $wp_query->get_queried_object()->ID, 'twitter_id', true)) :
					$social[] = '<li class="twitter"><a href="http://twitter.com/'.urlencode( $twitter ).'">'. esc_html( sprintf( __('Follow %s on Twitter', 'appthemes'), $wp_query->get_queried_object()->display_name ) ).'</a></li>';
				endif;
				
				if ($facebook = get_user_meta( $wp_query->get_queried_object()->ID, 'facebook_id', true)) :
					$social[] = '<li class="facebook"><a href="http://facebook.com/'.urlencode( $facebook ).'">'. esc_html( sprintf( __('Add %s on Facebook', 'appthemes'), $wp_query->get_queried_object()->display_name ) ).'</a></li>';
				endif;
				
				if ($linkedin = get_user_meta( $wp_query->get_queried_object()->ID, 'linkedin_profile', true)) :
					$social[] = '<li class="linkedin"><a href="'.urlencode( $linkedin ).'">'. esc_html( sprintf( __('View %s on LinkedIn', 'appthemes'), $wp_query->get_queried_object()->display_name ) ).'</a></li>';
				endif;
				
				if (sizeof($social)>0) :
					echo '<ul class="social">'.implode('', $social).'</ul>';
				endif;
			?>
			
			<div class="clear"></div>
		
		</div>
		
		<?php if (user_can($wp_query->get_queried_object()->ID, 'can_submit_job')) : ?>
			<h2 class="pagetitle"><?php echo esc_html( sprintf( __('%s\'s job listings', 'appthemes'), $wp_query->get_queried_object()->display_name) ); ?></h2>
	        <?php
	        	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	       		$args = array(
					'author' => $wp_query->get_queried_object()->ID,
					'post_type' => 'job_listing',
					'post_status' => 'publish',
					'paged' => $paged
				);
				query_posts($args);
	
				// call the main loop-job.php file
				get_template_part( 'loop', 'job' );
			?>
	
			<?php jr_paging(); ?>
		<?php else : ?>
			
			<h2><?php echo esc_html( sprintf( __('%s\'s resumes', 'appthemes'), $wp_query->get_queried_object()->display_name) ); ?></h2>
			
			<?php
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				$args = array(
					'author' => $wp_query->get_queried_object()->ID,
					'post_type' => 'resume',
					'post_status' => 'publish',
					'paged' => $paged
				);
				query_posts($args);
					
				get_template_part( 'loop', 'resume' ); 
			?>

        	<?php jr_paging(); ?>
		
		<?php endif; ?>

		<div class="clear"></div>

    </div><!-- end section -->

    <div class="clear"></div>

</div><!-- end main content -->

<?php if (get_option('jr_show_sidebar')!=='no') get_sidebar(); ?>

<?php get_footer(); ?>
