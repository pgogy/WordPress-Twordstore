<?PHP

session_start();

require_once('twitteroauth/OAuth.php');

require_once('twitteroauth/twitteroauth.php');

require_once('config.php');

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
  return $connection;
}
 
$connection = getConnectionWithAccessToken("484240316-ppDWbqUIARhS3AsjQ2thy1VYn51hRkeFfzkhnYLO", "HLBamNuuCBtOacCSzY0WP76sY4sb3P55eqpw6MVwzJk");

mysql_connect("localhost","web2-new-john","n3wj0hn!£");
mysql_select_db("web2-new-john");

$twitter_data = mysql_query("SELECT twitter_id FROM john_twitter_user_list_of_mps ORDER BY `john_twitter_user_list_of_mps`.`last_obtained` ASC limit 1");

$id_to_get = mysql_fetch_object($twitter_data);

$twitter_data = mysql_query("SELECT MAX( twitter_tweet_id ) as max_tweet FROM john_list_of_mp_tweets WHERE twitter_tweet_tweeted = " . $id_to_get->twitter_id );

$url = "statuses/user_timeline.json?user_id=***&since_id=!!!&count=200";

if(mysql_num_rows($twitter_data)!=0){

	echo "HERE";

	$user_data = mysql_fetch_object($twitter_data);

	$new_url = str_replace("***",$id_to_get->twitter_id,$url);

	$new_url = str_replace("!!!",$user_data->max_tweet,$new_url);

}else{
		
	$twitter_data = mysql_query("SELECT twitter_id FROM  `john_twitter_user_list_of_mps` ORDER BY  `john_twitter_user_list_of_mps`.`last_obtained` ASC limit 1 ");

	$user_data = mysql_fetch_object($twitter_data);

	$new_url = str_replace("&since_id=!!!","",$url);

	$new_url = str_replace("***",$id_to_get->twitter_id,$new_url);

}

echo $new_url . "<br />";

$content = $connection->get($new_url);

echo "<pre>";

if(mysql_query("update john_twitter_user_list_of_mps set last_obtained = " . time() . " where twitter_id = " . $id_to_get->twitter_id)){

	echo "WORKED";

}else{

	echo "FAILED";

}

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

	if(!mysql_query("insert into john_list_of_mp_tweets(twitter_tweet_tweeted,twitter_date,twitter_date_timestamp,twitter_tweet_id,twitter_tweet_text,twitter_tweet_source,twitter_tweet_favourited,twitter_tweet_retweet_count,twitter_tweet_retweeted,twitter_tweet_in_reply_to_status,twitter_tweet_in_reply_to_id,twitter_retweeted_id,twitter_retweeted_text,twitter_retweeted_source,twitter_retweeted_count,twitter_retweeted_retweet,twitter_retweeted_screenname)VALUES('" . $id_to_get->twitter_id . "','" . $tweet->created_at . "','" . $timestamp . "','" . $tweet->id	. "','" . addslashes($tweet->text) . "','" . addslashes($tweet->source) . "','" . $tweet->favorited . "','" . $tweet->retweet_count . "','" . $tweet->retweeted . "','" . $tweet->in_reply_to_status_id . "','" . $tweet->in_reply_to_user_id . "','" . $tweet->retweeted_status->id . "','" . addslashes($tweet->retweeted_status->text) . "','" . addslashes($tweet->retweeted_status->source) . "','" . $tweet->retweeted_status->retweet_count . "','" . $tweet->retweeted_status->retweeted . "','" . addslashes($tweet->retweeted_status->user->screen_name) . "')")){ $insert_ok = false; }

	if(!mysql_query("insert into john_twitter_user_list_of_mps_stats(date_obtained,twitter_id,user_listed_count,user_followers_count,user_friends_count,statuses_count)VALUES('" . time()	. "','" . $id_to_get->twitter_id . "','" . $tweet->user->listed_count . "','" . $tweet->user->followers_count . "','" . $tweet->user->friends_count . "','" . $tweet->user->statuses_count . "')")){ $insert_ok = false; }

	if(!mysql_query("update john_twitter_user_list_of_mps set last_obtained = '" . time() . "', twitter_name = '" . addslashes($tweet->user->name) . "', twitter_screenname = '" . addslashes($tweet->user->screen_name) . "', twitter_location = '" . addslashes($tweet->user->location) . "', twitter_description = '" . addslashes($tweet->user->description) . "', twitter_profile_image_url = '" . $tweet->user->profile_image_url . "', twitter_url = '" . $tweet->user->url . "' where twitter_id = '" . $id_to_get->twitter_id . "'")){ $insert_ok = false; }

	if($insert_ok!=true){}

}

echo $counter . "*******";

?>