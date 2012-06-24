<?php

// job pack options admin page
function jr_job_packs_admin() {
    ?>
    <div class="wrap jobroller">
        <div class="icon32" id="icon-options-general"><br/></div>
        <h2><?php _e('Job Packs','appthemes') ?></h2>

        <?php 
        	global $wpdb;
        	
        	if ( isset($_GET['edit']) ) :
				jr_edit_job_pack();
			else :
				if ( isset($_GET['delete']) ) :
					$deletepack = (int) $_GET['delete'];
					if ($deletepack > 0) :
						$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."jr_job_packs WHERE id = %d", $deletepack));
						echo '<p class="success">'.__('Pack Deleted','appthemes').'</p>';
					endif;
				endif;
				jr_job_packs();
			endif;
		?>	
    </div>
	<?php
}

function jr_job_packs() {
	
	global $message, $errors, $posted;
	$errors = new WP_Error();
	$message = '';
	jr_add_job_pack(); 

	$packs = jr_get_job_packs();
	if (sizeof($packs)>0) :
		echo '<ul class="packs">';
		foreach ($packs as $pack) :
			
			if (!$pack->job_count) $pack->job_count = __('Unlimited', 'appthemes');
			
			if ($pack->pack_duration) $pack->pack_duration = __(' usable within ', 'appthemes').$pack->pack_duration.__(' days', 'appthemes');
			
			if ($pack->job_duration) $pack->job_duration = __(' lasting ', 'appthemes').$pack->job_duration.__(' days' ,'appthemes');
			
			if ($pack->pack_cost) :
				$pack->pack_cost = jr_get_currency($pack->pack_cost).'';
			else :
				$pack->pack_cost = __('Free','appthemes');
			endif; 
			
			echo '<li><span class="cost">'.$pack->pack_cost.'</span><h3>'.$pack->pack_name.' &ndash; <small>'.$pack->pack_description.'</small></h3>
				<p>'.$pack->job_count.' '.__('Jobs', 'appthemes').''.$pack->job_duration.$pack->pack_duration.'.</p>
				<p><a href="admin.php?page=jobpacks&amp;edit='.$pack->id.'">Edit Pack</a></p>
				<p><a href="admin.php?page=jobpacks&amp;delete='.$pack->id.'" class="deletepack">Delete this pack</a></p>
			</li>';
		endforeach;
		echo '</ul>';
	endif;
    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    	jQuery('a.deletepack').click(function(){
    		var answer = confirm ("<?php _e('Are you sure you want to delete this pack? This action cannot be undone...', 'appthemes'); ?>")
			if (answer)
				return true;
			return false;
    	});
    /* ]]> */
    </script>
	
	<h3><?php _e('Create a New Job Pack','appthemes') ?></h3>
	<p><?php _e('Job Packs let you define packages that customers can purchase in order to post multiple/single jobs for varying durations. Once you add a pack the values on the "pricing" page will no longer be used.', 'appthemes'); ?></p>
	
	<?php
		jr_show_errors( $errors );
		
		if (isset($message) && !empty($message)) {
			echo '<p class="success">'.$message.'</p>';
		}
	?>
    <form method="post" id="mainform" action="">
		
		<table class="widefat fixed" id="tblspacer" style="width:850px;">

                <thead>
                    <tr>
                        <th scope="col" width="200px"><?php _e('Job Pack Details','appthemes')?></th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                </thead>

		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a name for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Name','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_name" id="pack_name" type="text" value="<?php if (isset($posted['pack_name'])) echo $posted['pack_name']; ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a description for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Description','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_description" id="pack_description" type="text" value="<?php if (isset($posted['pack_description'])) echo $posted['pack_description']; ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter the number of days','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_duration" id="pack_duration" type="text" value="<?php if (isset($posted['pack_duration'])) echo $posted['pack_duration']; ?>" /><br/><small><?php _e('Days this pack remains valid to use. Leave blank if it never expires.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value, do not include currency values.','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Cost','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_cost" id="pack_cost" type="text" value="<?php if (isset($posted['pack_cost'])) echo $posted['pack_cost']; ?>"  /><br/><small><?php _e('Pack cost. Leave blank if free.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Count','appthemes') ?>:</td>
                <td class="forminp"><input name="job_count" id="job_count" type="text" value="<?php if (isset($posted['job_count'])) echo $posted['job_count']; ?>"  /><br/><small><?php _e('How many jobs can the user list with this pack? Leave blank for an <em>unlimited</em> amount.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="job_duration" id="job_duration" type="text" value="<?php if (isset($posted['job_duration'])) echo $posted['job_duration']; ?>" class="required" /><br/><small><?php _e('How long do jobs last? e.g. <code>30</code> for 30 days. Leave blank for endless jobs.','appthemes') ?></small></td>
		    </tr>
		
	    </table>

        <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Create Job Pack','appthemes') ?>" />
        <input name="submitted" type="hidden" value="yes" /></p>
    </form>
    <?php
}

function jr_edit_job_pack() {

	global $wpdb, $message, $errors;
	$errors = new WP_Error();
	$message = '';
	
	$edited_pack = (int) $_GET['edit'];
	
	if (!$edited_pack) :
		_e('Pack not found!', 'appthemes');
		exit;
	endif;
	
	// Get Job details
	$job_pack = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."jr_job_packs WHERE id = %d", $edited_pack));
	
	if (!$job_pack) :
		_e('Pack not found!', 'appthemes');
		exit;
	endif;
	
	if ($_POST) :
		
		$posted = array();
		
		$fields = array(
			'pack_name',
			'pack_description',
			'pack_duration',
			'pack_cost',
			'job_count',
			'job_duration'
		);
		
		foreach ($fields as $field) :
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
			else $posted[$field] = '';
		endforeach;
		
		$required = array(
			'pack_name' => __('Pack name', 'appthemes'),
			'pack_description' => __('Pack Description', 'appthemes'),
		);
		
		foreach ($required as $field=>$name) {
			if (empty($posted[$field])) {
				$errors->add('submit_error', __('<strong>ERROR</strong>: &ldquo;', 'appthemes').$name.__('&rdquo; is a required field.', 'appthemes'));
			}
		}
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			$wpdb->update( $wpdb->prefix . 'jr_job_packs', array( 
				'pack_name' 		=> $posted['pack_name'],
				'pack_description' 	=> $posted['pack_description'],
				'pack_duration' 	=> $posted['pack_duration'],
				'pack_cost' 		=> $posted['pack_cost'],
				'job_count' 		=> $posted['job_count'],
				'job_duration'		=> $posted['job_duration'],
			), array( 'id' => $edited_pack ), array( '%s','%s','%s','%s','%s','%s' ) );
			
			$message = __('Pack updated successfully', 'appthemes');
			
			$job_pack = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."jr_job_packs WHERE id = %d", $edited_pack));
		
		}
		
	endif;
    ?>
	
	<h3><?php _e('Edit Job Pack','appthemes') ?></h3>
	
	<?php
		jr_show_errors( $errors );
		
		if (isset($message) && !empty($message)) {
			echo '<p class="success">'.$message.'</p>';
		}
	?>
	
    <form method="post" id="mainform" action="">
		
		<table class="widefat fixed" id="tblspacer" style="width:850px;">

                <thead>
                    <tr>
                        <th scope="col" width="200px"><?php _e('Job Pack Details','appthemes')?></th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                </thead>

		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a name for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Name','appthemes') ?>:</td>
                <td class="forminp"><input style="width:300px;" name="pack_name" id="pack_name" type="text" value="<?php echo $job_pack->pack_name; ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a description for this pack. This will be visible on the website','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Description','appthemes') ?>:</td>
                <td class="forminp"><input style="width:500px;" name="pack_description" id="pack_description" type="text" value="<?php echo $job_pack->pack_description; ?>" class="required" /></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter the number of days','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_duration" id="pack_duration" type="text" value="<?php echo $job_pack->pack_duration; ?>" /><br/><small><?php _e('Days this pack remains valid to use. Leave blank if it never expires.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value, do not include currency values.','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Pack Cost','appthemes') ?>:</td>
                <td class="forminp"><input name="pack_cost" id="pack_cost" type="text" value="<?php echo $job_pack->pack_cost; ?>" /><br/><small><?php _e('Pack cost. Leave blank if free.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Count','appthemes') ?>:</td>
                <td class="forminp"><input name="job_count" id="job_count" type="text" value="<?php echo $job_pack->job_count; ?>" /><br/><small><?php _e('How many jobs can the user list with this pack? Leave blank for an <em>unlimited</em> amount.','appthemes') ?></small></td>
		    </tr>
		    <tr>
                <td class="titledesc"><a href="#" tip="<?php _e('Enter a numeric value or leave blank','appthemes') ?>" tabindex="99"><div class="helpico"></div></a> <?php _e('Job Duration','appthemes') ?>:</td>
                <td class="forminp"><input name="job_duration" id="job_duration" type="text" value="<?php echo $job_pack->job_duration; ?>" class="required" /><br/><small><?php _e('How long do jobs last? e.g. <code>30</code> for 30 days.','appthemes') ?></small></td>
		    </tr>
		
	    </table>

        <p class="submit bbot"><input name="save" type="submit" value="<?php _e('Save Pack','appthemes') ?>" />
        <input name="submitted" type="hidden" value="yes" /></p>
    </form>

	<?php
}

function jr_add_job_pack() {
	
	global $wpdb, $errors, $message, $posted;
	
	if ($_POST) :
		
		$posted = array();
		
		$fields = array(
			'pack_name',
			'pack_description',
			'pack_duration',
			'pack_cost',
			'job_count',
			'job_duration'
		);
		
		foreach ($fields as $field) :
			if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field]));
			else $posted[$field] = '';
		endforeach;
		
		$required = array(
			'pack_name' => __('Pack name', 'appthemes'),
			'pack_description' => __('Pack Description', 'appthemes'),
		);
		
		foreach ($required as $field=>$name) {
			if (empty($posted[$field])) {
				$errors->add('submit_error', __('<strong>ERROR</strong>: &ldquo;', 'appthemes').$name.__('&rdquo; is a required field.', 'appthemes'));
			}
		}
		
		if ($errors && sizeof($errors)>0 && $errors->get_error_code()) {} else {
			
			$wpdb->insert( $wpdb->prefix . 'jr_job_packs', array( 
				'pack_name' 		=> $posted['pack_name'],
				'pack_description' 	=> $posted['pack_description'],
				'pack_duration' 	=> $posted['pack_duration'],
				'pack_cost' 		=> $posted['pack_cost'],
				'job_count' 		=> $posted['job_count'],
				'job_duration'		=> $posted['job_duration'],
			), array( '%s','%s','%s','%s','%s','%s' ) );
			
			$message = __('Pack added successfully', 'appthemes');
		
		}
		
	endif;
}
?>
