<?PHP

add_action("the_content", "twordstore_post_display");

function twordstore_post_display($content){

	global $wpdb,$post_id;
	
	$post_data = get_post($post_id);

	if($post_data->post_type=="twordstore"){
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $post_data->ID . " order by twitter_date_timestamp ASC ", OBJECT);
		
		$output = "";
		
		while($tweet = array_shift($data)){
		
			if($tweet->display!='false'){
		
				$output .= '<div class="twordstore_tweet_public">
					<div class="twordstore_tweet_profile_public">
							<img src="' . $tweet->twitter_profile_image_url_https . '" />
							<p><a href="http://www.twitter.com/' . $tweet->twitter_from_user . '">' . $tweet->twitter_from_user_name . '</a></p>
					</div>
					<div class="twordstore_tweet_text_public">
						<p>' . $tweet->twitter_tweet_text . '</p>
						<p>' . date("G:i:s jS F Y",$tweet->twitter_date_timestamp) . '</p>
						<p><a href="http://www.twitter.com/' . $tweet->twitter_from_user . '/' . $tweet->tweet_id . '">See actual tweet</a></p>
					</div>
				</div>';
			
			}
		
		}
		
		return $output;
		
	}else{
	
		return $content;
	
	}

}

?>