<?PHP

	add_action('wp_ajax_twordstore_edit_get_store', 'twordstore_get_post_tweets');

	function twordstore_get_post_tweets() {
		
		global $wpdb;
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $_POST['post_id'] . " order by twitter_date_timestamp DESC ", OBJECT);
			
		$output = "";
			
		while($tweet = array_shift($data)){
			
			if($tweet->display!='false'){
			
				$output .= '<div class="twordstore_tweet_editor">
					<div class="twordstore_tweet_profile_editor">
							<img height=48 width=48 src="' . $tweet->twitter_profile_image_url_https . '" />
							<p><a href="http://www.twitter.com/' . $tweet->twitter_from_user . '">' . $tweet->twitter_from_user_name . '</a></p>
					</div>
					<div class="twordstore_tweet_text_editor">
						<p>' . $tweet->twitter_tweet_text . '</p>
						<p>' . date("G:i:s jS F Y",$tweet->twitter_date_timestamp) . '</p>
						<p><a href="http://www.twitter.com/' . $tweet->twitter_from_user . '/' . $tweet->tweet_id . '">See actual tweet</a></p>						
						<p><strong><a onclick="javascript:add_shortcode(\'' . str_replace("0","*",substr($tweet->tweet_id,0,10)) .  '\',\'' . str_replace("0","*",substr($tweet->tweet_id,10)) .'\')">Add this tweet to the current post </a></strong></p>
					</div>
				</div>';
			
			}
			
		}
		
		echo $output;
		
		die(); // this is required to return a proper result
		
	}
	