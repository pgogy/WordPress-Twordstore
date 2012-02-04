<?PHP

	function twordstore_post_latest_tweet( $atts ) {	
	
		global $wpdb, $post_id;	
		
		$post_data = get_post($atts['id']);
	
		$table_name = $wpdb->prefix . "twordstore_tweets";
		
		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $atts['id'] . " order by twitter_date_timestamp DESC limit 1", OBJECT);
		
		$output = "";
		
		while($tweet = array_shift($data)){
		
			if($tweet->display!='false'){
		
				$output .= '<div class="twordstore_tweet_public">
					<div class="twordstore_tweet_profile_public">
							<img src="' . $tweet->twitter_profile_image_url_https . '" />
							<p>' . $tweet->twitter_from_user_name . '</p>
					</div>
					<div class="twordstore_tweet_text_public">
						<p>' . $tweet->twitter_tweet_text . '</p>
						<p>' . date("G:i:s jS F Y",$tweet->twitter_date_timestamp) . '</p>
					</div>
				</div>';
			
			}
		
		}

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $atts['id'], OBJECT);
		
		$output .= "<p><a href=\"" . $post_data->guid . "\">" . $post_data->post_title . "</a> has " . count($data) . " tweets</p>";
		
		return $output;
		
	}
	
	add_shortcode('twordstore_post_tweet', 'twordstore_post_latest_tweet' );
	
	function twordstore_post_total( $atts ) {	
	
		global $wpdb, $post_id;	
		
		$post_data = get_post($atts['id']);
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $atts['id'], OBJECT);
		
		$output = "<p><a href=\"" . $post_data->guid . "\">" . $post_data->post_title . "</a> has " . count($data) . " tweets</p>";
		
		return $output;
		
	}
	
	add_shortcode('twordstore_post', 'twordstore_post_total' );
	
	function twordstore_shortcode_single( $atts ) {	
	
		global $wpdb;	
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where tweet_id = " . $atts['id'], OBJECT);
		
		$output = "";
		
		while($tweet = array_shift($data)){
		
			if($tweet->display!='false'){
		
				$output .= '<div class="twordstore_tweet_public">
					<div class="twordstore_tweet_profile_public">
							<img src="' . $tweet->twitter_profile_image_url_https . '" />
							<p><a href="' . $tweet->twitter_from_user . '">' . $tweet->twitter_from_user_name . '</a></p>
					</div>
					<div class="twordstore_tweet_text_public">
						<p>' . $tweet->twitter_tweet_text . '</p>
						<p>' . date("G:i:s jS F Y",$tweet->twitter_date_timestamp) . '</p>
						<p><a href="' . $tweet->twitter_from_user . '/' . $tweet->tweet_id . '">See actual tweet</a></p>
					</div>
				</div>';
			
			}
		
		}
		
		return $output;
		
	}
	
	add_shortcode('twordstore_single_tweet', 'twordstore_shortcode_single' );
	
	function twordstore_shortcode_single_full( $atts ) {	
	
		global $wpdb;	
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where tweet_id = " . $atts['id'], OBJECT);
		
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
				</div>
				<div class="twordstore_tweet_stats">
					<ul>';
						if($tweet->twitter_to_user_name!=""){
						
							$output .= "<li>Tweeted at <a href='http://www.twitter.com/" . $tweet->twitter_to_user . "'>@" . $tweet->twitter_to_user_name . "</a>";
						
							if($tweet->twitter_tweet_in_reply_to_status!=""){
						
								$output .= " and their <a href='http://www.twitter.com/" . $tweet->twitter_to_user . "/status/" . $tweet->twitter_tweet_in_reply_to_status . "'>tweet</a>";
							
							}
							
							$output .= "</li>";
							
						}
						if($tweet->twitter_tweet_favourited!="false"){
						
							$output .= "<li>Has been 'favourited'</li>";
						
						}
						if($tweet->twitter_tweet_retweeted!=0){
						
							$output .= "<li>Has been 'retweeted'  " . $tweet->twitter_tweet_retweeted_count . " times</li>";
						
						}
				$output .=	'</ul>
				</div>';				
			
			}
		
		}
		
		return $output;
		
	}
	
	add_shortcode('twordstore_single_tweet_full', 'twordstore_shortcode_single_full' );

?>