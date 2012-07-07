<?PHP

	add_action("add_meta_boxes", "twordstore_add_menu" );

	function twordstore_add_menu($output){
		
		add_meta_box( 'twordstore_id', 'Add Twordstore tweet',"twordstore_create_add_tweet_menu","post","normal","high");
		add_meta_box( 'twordstore_id', 'Add Twordstore tweet',"twordstore_create_add_tweet_menu","post","normal","high");
		
	}
	
	function twordstore_create_add_tweet_menu(){
	
		global $wpdb;
		
		$table_name = $wpdb->prefix . "twordstore_posts";
	
		$data = $wpdb->get_results("select * from " . $table_name . " order by post_id ASC", OBJECT);
		
		echo "<form><select id='twordstore_get_post' onChange='javascript:twordstore_editor_get_posts_tweets();'>";
		
		echo "<option>Select a twordstore</option>";
		
		while($post_data = array_shift($data)){
		
			echo "<option value='" . $post_data->post_id . "'>";
			
			$post_id_data = get_post($post_data->post_id);
			
			echo $post_id_data->post_title . " (Term : " . $post_data->twordstore_variable . ") </option>";
		
		}
		
		echo "</select></form>";	

		echo "<div id='twordstore_returned_tweets' style='height:400px; overflow:scroll; margin:10px 0'>Tweets will appear here</div>";
	
	}

?>