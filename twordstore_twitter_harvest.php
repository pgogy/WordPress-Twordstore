<?PHP

	function tweet_harvest_timeline_user($post_to_store,$username){
	
		global $wpdb;

		$ch = curl_init();
		
		require_once('twitteroauth/OAuth.php');
		require_once('twitteroauth/twitteroauth.php');

		define('CONSUMER_KEY', get_option("twordstore_consumer_key"));
		define('CONSUMER_SECRET', get_option("twordstore_consumer_secret"));
		define('OAUTH_CALLBACK', '');

		$new_url = "statuses/user_timeline.json?screen_name=" . $username . "&count=200";

		function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {		  
		  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
		  return $connection;
		}
	 
		$connection = getConnectionWithAccessToken(get_option("twordstore_oauth_token"), get_option("twordstore_oauth_token_secret"));
			
		$content = $connection->get($new_url);
	
		$counter = 0;

		foreach($content as $tweet){

			$counter++;

			$insert_ok = true;

			$parts = explode(" ",$tweet->created_at);

			$h_m_s = explode(":",$parts[3]);

			switch($parts[1]){

				case "Jan": $month = 1; break;
				case "Feb": $month = 2; break;
				case "Mar": $month = 3; break;
				case "Apr": $month = 4; break;
				case "May": $month = 5; break;
				case "Jun": $month = 6; break;
				case "Jul": $month = 7; break;
				case "Aug": $month = 8; break;
				case "Sep": $month = 9; break;
				case "Oct": $month = 10; break;
				case "Nov": $month = 11; break;
				case "Dec": $month = 12; break;
				
			}
	
			$timestamp = mktime ($h_m_s[0], $h_m_s[1], $h_m_s[2], $month, $parts[2], $parts[5]);	

			$wpdb->query("replace into " . $wpdb->prefix . "twordstore_tweets
						  (
						  post_id,
						  twitter_tweet_tweeted,
						  tweet_id, 
						  twitter_date,
						  twitter_date_timestamp,
						  twitter_tweet_id,
						  twitter_tweet_text,
						  twitter_tweet_source,
						  twitter_tweet_favourited,
						  twitter_tweet_retweet_count,
						  twitter_tweet_retweeted,
						  twitter_tweet_in_reply_to_status,
						  twitter_tweet_in_reply_to_id,
						  twitter_from_user,
						  twitter_from_user_id,
						  twitter_from_user_id_str,
						  twitter_from_user_name,
						  twitter_to_user_name,
						  twitter_to_user,
						  twitter_to_user_id,
						  twitter_retweeted_id,
						  twitter_retweeted_text,
						  twitter_retweeted_source,
						  twitter_retweeted_count,
						  twitter_retweeted_retweet,
						  twitter_retweeted_screenname,							  
						  twitter_profile_image_url,
						  twitter_profile_image_url_https
						  )VALUES(
						  '" . $post_to_store . "',
						  '" . $tweet->user->id . "',
						  '" . $tweet->id . "',
						  '" . $tweet->created_at . "',
						  '" . $timestamp . "',
						  '" . $tweet->id_str	. "',
						  '" . addslashes($tweet->text) . "',
						  '" . addslashes($tweet->source) . "',
						  '" . $tweet->favorited . "',
						  '" . $tweet->retweet_count . "',
						  '" . $tweet->retweeted . "',
						  '" . $tweet->in_reply_to_status_id_str . "',
						  '" . $tweet->in_reply_to_user_id_str . "',
						  '" . $tweet->user->name . "',
						  '" . (integer)$tweet->user->id_str . "',
						  '" . $tweet->user->id_str . "',
						  '" . $tweet->user->screen_name . "',
						  '" . $tweet->in_reply_to_screen_name . "',
						  '" . $tweet->in_reply_to_screen_name . "',
						  '" . $tweet->in_reply_to_user_id . "',
						  '" . $tweet->retweeted_status->id . "',
						  '" . addslashes($tweet->retweeted_status->text) . "',
						  '" . addslashes($tweet->retweeted_status->source) . "',
						  '" . $tweet->retweeted_status->retweet_count . "',
						  '" . $tweet->retweeted_status->retweeted . "',
						  '" . addslashes($tweet->retweeted_status->user->screen_name) . "',
						  '" . $tweet->user->profile_image_url . "',
						  '" . $tweet->user->profile_image_url_https . "')");
		
		}

	}
	
	function tweet_harvest_timeline_search($post_to_store,$search_term){
	
		global $wpdb;

		$ch = curl_init();
		
		$url = "http://search.twitter.com/search.json?q=" . $search_term . "&rpp=99";
			
		curl_setopt($ch, CURLOPT_URL, $url);
			
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	

		$data = curl_exec($ch);

		$xml = json_decode($data);

		if($xml){
			
			foreach($xml->results as $tweet){
				
				$parts = explode(" ",$tweet->created_at);

				$h_m_s = explode(":",$parts[4]);
				
				switch($parts[2]){
				
					case "Jan": $month = 1; break;
					case "Feb": $month = 2; break;
					case "Mar": $month = 3; break;
					case "Apr": $month = 4; break;
					case "May": $month = 5; break;
					case "Jun": $month = 6; break;
					case "Jul": $month = 7; break;
					case "Aug": $month = 8; break;
					case "Sep": $month = 9; break;
					case "Oct": $month = 10; break;
					case "Nov": $month = 11; break;
					case "Dec": $month = 12; break;
				
				}
				
				$timestamp = mktime ($h_m_s[0], $h_m_s[1], $h_m_s[2], $month, $parts[1], $parts[3]);
				
				if(empty($tweet->to_user)){
				
					$tweet->to_user = " ";

				}
				
				if(empty($tweet->to_user_id)){
				
					$tweet->to_user_id = " ";

				}
				
				if(empty($tweet->to_user_id_str)){
				
					$tweet->to_user_id_str = " ";

				}
				
				if(empty($tweet->to_user_name)){
				
					$tweet->to_user_name = " ";

				}
				
				$wpdb->query("replace into " . $wpdb->prefix . "twordstore_tweets
							  (
								  post_id,
								  twitter_tweet_tweeted,
								  tweet_id, 
								  twitter_date,
								  twitter_date_timestamp,
								  twitter_tweet_id,
								  twitter_tweet_text,
								  twitter_tweet_source,
								  twitter_tweet_in_reply_to_status,
								  twitter_tweet_in_reply_to_id,
								  twitter_from_user,
								  twitter_from_user_id,
								  twitter_from_user_id_str ,
								  twitter_from_user_name,
								  twitter_profile_image_url,
								  twitter_profile_image_url_https,
								  twitter_to_user,
								  twitter_to_user_name
							  )VALUES(
								'" . $post_to_store . "',
								'" . $tweet->from_user_id_str . "',
								'" . $tweet->id_str . "',
								'" . $tweet->created_at . "',
								'" . $timestamp . "',
								'" . $tweet->id_str	. "',
								'" . addslashes($tweet->text) . "',
								'" . addslashes($tweet->source) . "',
								'" . $tweet->in_reply_to_status_id_str . "',
								'" . $tweet->to_user_id_str . "',
								'" . $tweet->from_user . "',
								'" . $tweet->from_user_id . "',
								'" . $tweet->from_user_id_str . "',
								'" . $tweet->from_user_name . "',
								'" . $tweet->profile_image_url . "',
								'" . $tweet->profile_image_url_https . "',
								'" . $tweet->to_user . "',
								'" . $tweet->to_user_name . "'
							  )"
							);
				
			}
			
			curl_close($ch);
		
		}

	}

?>