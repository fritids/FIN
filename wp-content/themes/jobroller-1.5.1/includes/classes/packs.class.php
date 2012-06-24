<?php
class jr_pack {

	var $id;	 	 	 	 	 	 	
	var $pack_name;		 	 	 	 	 	 	
	var $pack_description;	 	 	 	 	 	 	 
	var $job_count;			 	 	 	 	 	 	 
	var $pack_duration;	 	 	 	 	 	 	
	var $job_duration; 	 	 	 	 	 	
	var $pack_cost;	 	 	 	 	 	 	 	 	
	var $pack_created;	 	 	 	
	
	function jr_pack( $id='', $pack_name='', $pack_description='', $job_count='', $pack_duration='', $job_duration = '', $pack_cost='' ) {
		if ($id>0) :
			$this->id = (int) $id;
			$this->get_pack();
		elseif ($pack_name) :
			$this->pack_name = $pack_name; 	 	 	 	 	 	
			$this->pack_description = $pack_description;	 	 	 	 
			$this->job_count = $job_count; 	 	 	 
			$this->pack_duration = $pack_duration; 	
			$this->job_duration = $job_duration;	
			$this->pack_cost = $pack_cost;
		endif;
	}
	
	function get_pack() {
		global $wpdb;
		$result = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."jr_job_packs WHERE id = ".$this->id.';');
		if ($result) : 	 	 	 	 	
			$this->pack_name = $result->pack_name; 	 	 	 	 	 	
			$this->pack_description = $result->pack_description;	 	 	 	 
			$this->job_count = $result->job_count; 	 	 	 
			$this->pack_duration = $result->pack_duration; 	
			$this->job_duration = $result->job_duration;	
			$this->pack_cost = $result->pack_cost;
			$this->pack_created = $result->pack_created;	
		endif;
	}
	
	function insert_pack() {
		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'jr_job_packs', array( 
			'pack_name' 		=> $this->pack_name,
			'pack_description' 	=> $this->pack_description,
			'job_count' 		=> $this->job_count,
			'pack_duration' 	=> $this->pack_duration,
			'job_duration' 		=> $this->job_duration,
			'pack_cost'			=> $this->pack_cost
		), array( '%s','%s','%s','%s','%s','%s' ) );
		
		$this->id = $wpdb->insert_id;
	}
	
	function give_to_user( $user ) {
		global $wpdb;
		if ($this->id > 0) :
				
			if ($this->pack_duration > 0) :
				$expires = date("Y-m-d H:i:s", strtotime("+".$this->pack_duration." day"));
			else :
				$expires = '';
			endif;
			
			$wpdb->insert( $wpdb->prefix."jr_customer_packs", array( 
				'pack_name' 	=> $this->pack_name, 
				'user_id' 		=> $user,
				'job_duration' 	=> $this->job_duration,
				'pack_expires' 	=> $expires,
				'jobs_count' 	=> 1,
				'jobs_limit' 	=> $this->job_count
			), array( '%s', '%s', '%s', '%s', '%d', '%d' ) );
			
		endif;
	}
}	

class jr_user_pack {

	var $id;
	var $user_id;		 	 	 	 	 	
	var $pack_name;		
	var $job_duration;
	var $pack_expires;	 	 	 	 	 	 	 	 	 	 
	var $job_count;		
	var $job_limit;		 	 	 	 	 	 	 
	var $pack_purchased;	 	 	 	 	 	 	 	 	 	
	
	function jr_user_pack( $id='' ) {
		$this->id = (int) $id;
	}
	
	function get_valid_pack() {
		global $wpdb;
		$result = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."jr_customer_packs WHERE id = ".$this->id." AND ".get_current_user_id()." AND (jobs_count < jobs_limit OR jobs_limit = 0) AND (pack_expires > NOW() OR pack_expires = NULL OR pack_expires = '0000-00-00 00:00:00')");
		if ($result) : 	 	 	 	 	
			$this->user_id 		= $result->user_id; 	 	 	 	 	 	
			$this->pack_name 	= $result->pack_name;	 	 	 	 
			$this->job_duration = $result->job_duration; 	 	 	 
			$this->pack_expires = $result->pack_expires; 	
			$this->job_count 	= $result->jobs_count;	
			$this->job_limit 	= $result->jobs_limit;
			$this->pack_purchased = $result->pack_purchased;
			return true;	
		else :
			return false;
		endif;
	}
	
	function inc_job_count() {
		global $wpdb;
		$wpdb->update( $wpdb->prefix . "jr_customer_packs", array( 'jobs_count' => ($this->job_count+1) ), array( 'id' => $this->id ), array( '%d' ), array( '%d' ) );
	}
}