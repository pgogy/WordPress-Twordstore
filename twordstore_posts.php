<?PHP

	add_action("save_post", "twordstore_create");
	add_action("trash_post", "twordstore_move_trash");
	add_action("before_delete_post", "twordstore_delete"); 

	function twordstore_create($post_id)
	{
		
		global $wpdb;
		
		if ( !empty($_POST) || wp_verify_nonce($_POST['post_or_edit_twordstore'],'twordstore_post_edit') )
		{
		
			$data = get_post($post_id);		
			
			if($data->post_type=="twordstore"){
			
				if(count($_POST)!=0){

					if(isset($_POST['twordstore_recording'])){
					
						if($_POST['twordstore_recording']=="on"){
						
							$recording = 1;
						
						}else{
						
							$recording = 0;
							
						}
					
					}else{
					
						$recording = 0;
					
					}
					
					if($_POST['twordstore_new_post']==1){
				  
					   $sql = "insert into " . $wpdb->prefix . "twordstore_posts(
						  post_id,
						  twordstore_type,
						  twordstore_variable,
						  recording,
						  last_recorded
						)VALUES(
						  '" . $post_id . "',
						  '" . $_POST['twordstore_type'] . "',
						  '" . $_POST['twordstore_variable'] . "',
						  '" . $recording . "',
						  '0'
						)";
						
					}else{
					
						$sql = "update " . $wpdb->prefix . "twordstore_posts 
								set twordstore_type = '" . $_POST['twordstore_type'] . "',
								twordstore_variable = '" . $_POST['twordstore_variable'] . "',
								recording = '" . $recording . "'
								where post_id = " . $post_id;
					
					}
					   
				   $wpdb->query($sql);
			   
			   }
			
			}	

		}
		
		if ( !empty($_POST) || wp_verify_nonce($_POST['delete_from_twordstore'],'twordstore_twit_delete') )
		{
		
			foreach($_POST as $key => $value){
			
				if(strpos($key,"tweet")!==FALSE){
				
					if($value=="on"){
					
						$values = explode("_",$key);
						
						$sql = "update " . $wpdb->prefix . "twordstore_tweets 
								set display = 'false'								
								where post_id = " . $values[1] . " and id = " . $values[2];
						
						$wpdb->query($sql);
					
					}
									
				}
			
			}
		
		}

	}

	function twordstore_delete($post_id){
	
		global $wpdb;
		
		$data = get_post($post_id);	
			
		if($data->post_type=="twordstore"){
			
			$sql = "update " . $wpdb->prefix . "twordstore_posts 
				set recording = '0'
				where post_id = " . $post_id;
					   
			$wpdb->query($sql);
		
		}
		

	}

?>