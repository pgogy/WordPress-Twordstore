<?PHP

	add_action('wp_ajax_twordstore_trigger', 'twordstore_trigger');
	add_action('wp_ajax_twordstore_trigger_fake_cron', 'twordstore_trigger_faked_cron_job');
	add_action('wp_ajax_nopriv_twordstore_trigger_fake_cron', 'twordstore_trigger_faked_cron_job');

	function twordstore_trigger() {
		
		if(isset($_POST['type_search'])){
		
			if($_POST['type_search']==0){
		
				require_once "twordstore_twitter_harvest.php";
		
				tweet_harvest_timeline_user($_POST['twordstore_post'],$_POST['term_search']);
			
			}else{
			
				require_once "twordstore_twitter_harvest.php";
		
				tweet_harvest_timeline_search($_POST['twordstore_post'],$_POST['term_search']);
			
			}
			
			global $wpdb;		
				  
			$sql = "update " . $wpdb->prefix . "twordstore_posts 
				set last_recorded = " . time() . ",
				twordstore_variable ='" . $_POST['term_search'] . "',
				twordstore_type ='" . $_POST['type_search'] ."'				
				where post_id = " . $_POST['twordstore_post'];
				   
			$wpdb->query($sql);
				
			echo "This TwordStore post was last harvested on " . date("G:i:s jS F Y",time()) .  " : <a id='twordstore_update' onclick=\"javascript:twordstore_ajax_search('" . $_POST['twordstore_post'] . "','" . $_POST['term_search'] . "'," . $_POST['type_search'] . "," . time() . ",'replace')\">Search now</a>.";
			
			
			
		}
		
		die(); // this is required to return a proper result
		
	}
	
	function twordstore_trigger_faked_cron_job(){
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . "twordstore_posts";
	
		$data = $wpdb->get_results("select * from " . $table_name . " where recording='1' order by last_recorded ASC limit 1 ", OBJECT);
		
		$twordstore_post_data = $data[0];
		
		if($twordstore_post_data->twordstore_type==1){
		
			$last = time() - $twordstore_post_data->last_recorded;
		
			if($last > 120){
			
				require_once "twordstore_twitter_harvest.php";
		
				tweet_harvest_timeline_search($twordstore_post_data->post_id,$twordstore_post_data->twordstore_variable);				
				  
				$sql = "update " . $wpdb->prefix . "twordstore_posts 
					set last_recorded = " . time() . ",
					twordstore_variable ='" . $twordstore_post_data->twordstore_variable . "',
					twordstore_type ='" . $twordstore_post_data->twordstore_type ."'				
					where post_id = " . $twordstore_post_data->post_id;
					   
				$wpdb->query($sql);
			
			
			}
		
		}else{
		
			$last = time() - $twordstore_post_data->last_recorded;
		
			if($last > 120){
			
				require_once "twordstore_twitter_harvest.php";
		
				tweet_harvest_timeline_user($twordstore_post_data->post_id,$twordstore_post_data->twordstore_variable);				
				  
				$sql = "update " . $wpdb->prefix . "twordstore_posts 
					set last_recorded = " . time() . ",
					twordstore_variable ='" . $twordstore_post_data->twordstore_variable . "',
					twordstore_type ='" . $twordstore_post_data->twordstore_type ."'				
					where post_id = " . $twordstore_post_data->post_id;
					   
				$wpdb->query($sql);
			
			
			}
		
		}
		
		die();
	
	}
	
