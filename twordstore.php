<?PHP

	/*

		Plugin Name: twordstore
		Plugin URI: http://www.pgogy.com/code/twordstore
		Description: Makes WordPress Pages from Twitter
		Version: 0.4
		Author: Pgogy
		Author URI: http://www.pgogy.com

	*/
	
	require_once "twordstore_custompage.php";
	require_once "twordstore_posts.php";
	require_once "twordstore_editor.php";
	require_once "twordstore_ajax.php";
	require_once "twordstore_harvest_ajax_php.php";
	require_once "twordstore_editor_ajax_php.php";
	require_once "twordstore_post.php";
	require_once "twordstore_shortcodes.php";
	require_once "twordstore_tweet_editor.php";
	require_once "twordstore_management.php";

	global $twordstore_db_version;
	$twordstore_db_version = "1.0";

	function twordstore_install() {
	   global $wpdb;
	   global $twordstore_db_version;

	   $table_name = $wpdb->prefix . "twordstore_tweets";
		  
	   $sql = "CREATE TABLE " . $table_name . " (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
		  post_id  bigint(20),
		  display varchar(10),
		  tweet_id  bigint(20),
	      twitter_tweet_tweeted  varchar(20),
	      twitter_date  varchar(30),
	      twitter_date_timestamp  bigint(20),
	      twitter_tweet_id  bigint(20),
	      twitter_tweet_text  varchar(200),
		  twitter_from_user  varchar(255),
		  twitter_from_user_id  bigint(20),
		  twitter_from_user_id_str  bigint(20),
		  twitter_from_user_name  varchar(200),
		  twitter_profile_image_url  varchar(255),
		  twitter_profile_image_url_https  varchar(255),
		  twitter_to_user  varchar(200),
		  twitter_to_user_id  bigint(20),
		  twitter_to_user_id_str  bigint(20),
		  twitter_to_user_name  varchar(255),
	      twitter_tweet_source  varchar(200),
	      twitter_tweet_favourited  varchar(100),
	      twitter_tweet_retweet_count  bigint(20),
	      twitter_tweet_retweeted  varchar(200),
	      twitter_tweet_in_reply_to_status  varchar(200),
	      twitter_tweet_in_reply_to_id  bigint(20),
	      twitter_retweeted_id  bigint(200),
	      twitter_retweeted_text  varchar(200),
	      twitter_retweeted_source  varchar(200),
	      twitter_retweeted_count  bigint(20),
	      twitter_retweeted_retweet  varchar(200),
	      twitter_retweeted_screenname  varchar(200),
		  UNIQUE KEY  id(id),
		  UNIQUE KEY `tweet_id` (`tweet_id`)
		);";

       require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	   
	   $table_name = $wpdb->prefix . "twordstore_posts";
		  
	   $sql = "CREATE TABLE " . $table_name . " (
		  id bigint(20) NOT NULL AUTO_INCREMENT,
		  post_id  bigint(20),
		  twordstore_type varchar(20),
		  twordstore_variable varchar(50),
		  recording int(1),
		  last_recorded bigint(20),
		  UNIQUE KEY  id(id),
		  UNIQUE KEY  post_id(post_id)
		);";
	   
	   dbDelta($sql);
	 	
	   add_option("twordstore_db_version", $twordstore_db_version);
	   
	   //DO CRON
	   
	   if ( ! wp_next_scheduled( 'twordstore_cron_harvest' ) ) {
		  wp_schedule_event( time(), 'hourly', 'twordstore_cron_harvest' );
		  wp_schedule_event( time()+3000, 'hourly', 'twordstore_cron_harvest' );
	   }

	   add_action( 'twordstore_cron_harvest', 'twordstore_cron_harvest_call' );
	   
	}

    register_activation_hook(__FILE__,'twordstore_install');
	
	function twordstore_cron_harvest_call(){
	
		twordstore_trigger_faked_cron_job();
	
	}

