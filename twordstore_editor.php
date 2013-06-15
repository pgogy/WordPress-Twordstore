<?PHP

add_action("admin_menu", "twordstore_editor_make");
add_action('save_post','twordstore_post');
add_action('admin_init', 'twordstore_load_styles');

function twordstore_editor_make()
{

	add_meta_box("twordstore_editor", "Edit a twordstore", "twordstore_editor", "twordstore");
	
	if(isset($_GET['post'])){
	
		add_meta_box("twordstore_tweets_list", "Tweets from this TwordStore", "twordstore_tweets_list", "twordstore");
	
	}
	
}

function twordstore_editor(){

	global $wpdb;

	if(isset($_GET['post'])){
		
		$table_name = $wpdb->prefix . "twordstore_posts";

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $_GET['post'] . " limit 1 ", OBJECT);
		
		$twordstore_post_data = $data[0];
		
		$new_post = false;
	
	}else{
	
		$new_post = true;
	
	}

	?><form action="" method="POST"><?PHP

	wp_nonce_field('twordstore_post_edit','post_or_edit_twordstore');

	?>	<p>
		<label>Choose a type of TwordStore</label>
		<input type="hidden" name="twordstore_new_post" value="<?PHP echo $new_post; ?>" />
		<select name="twordstore_type">
			<option <?PHP 

				if(isset($twordstore_post_data->twordstore_type)){
				
					if($twordstore_post_data->twordstore_type==0){
					
						echo " selected ";
					
					}
					
				}

			?> value="0">User's tweets</option>
			<option <?PHP 

				if(isset($twordstore_post_data->twordstore_type)){
				
					if($twordstore_post_data->twordstore_type==1){
					
						echo " selected ";
					
					}
					
				}

			?>  value="1">Search term</option>		
		</select>
		</p>
		<p>
		<label>Enter the relevant term</label>
		<input type="text" name="twordstore_variable" value="<?PHP 

				if(isset($twordstore_post_data->twordstore_variable)){
				
					echo $twordstore_post_data->twordstore_variable;
					
				}else{
				
					echo "Please enter the term here";
				
				}

			?>" size="100" />
		</p>
		<?PHP
		if(isset($_GET['post'])){
		?>
			<p>
			<label>Is this twordstore post recording?</label>
			<input type="checkbox" name="twordstore_recording" <?PHP 

					if(isset($twordstore_post_data->recording)){
					
						if($twordstore_post_data->recording==1){
					
							echo "checked";
						
						}
					
					}

				?> />
			</p>
			<p id="harvest_now">
			<?PHP 
			
				if($twordstore_post_data->last_recorded==0){
					?>
					This TwordStore has yet to search for any tweets : <a id='twordstore_update' onclick="javascript:twordstore_ajax_search('<?PHP echo $_GET['post']; ?>','<?PHP echo $twordstore_post_data->twordstore_variable; ?>',<?PHP echo $twordstore_post_data->twordstore_type; ?>,0,'replace')">Search now</a>.
					<?PHP
				}else{
					?>
					This TwordStore post was last harvested on <?PHP echo date("G:i:s jS F Y",$twordstore_post_data->last_recorded);  ?> : <a id='twordstore_update' onclick="javascript:twordstore_ajax_search('<?PHP echo $_GET['post']; ?>','<?PHP echo $twordstore_post_data->twordstore_variable; ?>',<?PHP echo $twordstore_post_data->twordstore_type; ?>,<?PHP echo $twordstore_post_data->last_recorded; ?>,'replace')">Search now</a>.
					<?PHP
				}

			?> 
			</p>
		<?PHP
		}
		?>
	<?PHP

}

function twordstore_tweets_list(){

	global $wpdb;

	if(isset($_GET['post'])){
	
		$table_name = $wpdb->prefix . "twordstore_tweets";

		$data = $wpdb->get_results("select * from " . $table_name . " where post_id = " . $_GET['post'] . " order by twitter_date_timestamp DESC ", OBJECT);
		
		?><form action="" method="POST"><?PHP

		wp_nonce_field('twordstore_twit_delete','delete_from_twordstore');
		
		if(count($data)!=0){
		
			?><div class="twordstore_tweet_holder"><?PHP
		
			while($tweet = array_shift($data)){
			
				?><div class="twordstore_tweet">
					<div class="twordstore_tweet_checkbox">
						<label>Hide</label>
						<?PHP
						
							if($tweet->display=="false"){
							
								$display = "checked";
							
							}else{
							
								$display = "";
							
							}
							
						?>
						<p>
							<input type="checkbox" name="tweet_<?PHP echo $_GET['post'] . "_" . $tweet->id; ?>"<?PHP echo $display; ?> />
						</p>
					</div>
					<div class="twordstore_tweet_profile">
							<img src='<?PHP echo $tweet->twitter_profile_image_url_https; ?>' height=48 width=48 />
							<p><?PHP
							
								echo $tweet->twitter_from_user_name;
								
							?></p>
					</div>
					<div class="twordstore_tweet_text">
						<p><?PHP
							
								echo $tweet->twitter_tweet_text;
								
						?></p>
						<p><?PHP
							
								echo date("G:i:s jS F Y",$tweet->twitter_date_timestamp);
								
						?></p>
					</div><p style='clear:left'>
					
						Shortcode for this tweet (basic) [twordstore_single_tweet id="<?PHP echo $tweet->tweet_id; ?>"]
					
					</p>
					<p>
					
						Shortcode for this tweet (full) [twordstore_single_tweet_full id="<?PHP echo $tweet->tweet_id; ?>"]
					
					</p>
				</div><?PHP
			
			}
		
		?><p style="clear:both"><input type="submit" value="Hide selected tweets" /></p></div><?PHP
		
		}
		
	}

}

function twordstore_post(){

	if(!empty($_POST)){
	
		if($_POST['post_type']=="twordstore"){

			// if this fails, check_admin_referer() will automatically print a "failed" page and die.
			if ( !empty($_POST) || wp_verify_nonce($_POST['post_or_edit_twordstore'],'twordstore_post_edit') )
			{

			   print 'Sorry, WordPress reports this security setting (for the Twordstore plugin) as not verifying.';
			   
			}
		
		}
	
	}

}

function twordstore_load_styles($styles){

	wp_enqueue_style("twordstore", plugins_url( "/css/twordstore_edit.css" , __FILE__ ) , null, null, "screen" );
	
}