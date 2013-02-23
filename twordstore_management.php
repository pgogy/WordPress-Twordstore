<?PHP

	add_action('admin_menu', 'twordstore_menu_option');
	add_action('admin_head', 'twordstore_manage_postform');
	add_action('admin_notices', 'twordstore_setup_check');
	
		
	function twordstore_setup_check() {
	
		$url = site_url() . "/wp-admin/options-general.php?page=twordstore";
	
		$string = "";
	
		if(trim(get_option("twordstore_consumer_key"))===""){
		
			$string .= "<p>You need to set the <strong>Consumer Key</strong></p>";
			
		}
		
		if(trim(get_option("twordstore_consumer_secret"))===""){
	
			$string .= "<p>You need to set the <strong>Consumer Secret</strong></p>";
			
		}
		
		if(trim(get_option("twordstore_oauth_token"))===""){
	
			$string .= "<p>You need to set the page for the <strong>oAuth token</strong></p>";
			
		}
		
		if(trim(get_option("twordstore_oauth_token_secret"))===""){
	
			$string .= "<p>You need to set the page for the <strong>oAuth token secret</strong></p>";
			
		}
		
		if($string!=""){
		
			echo "<div class='update-nag' style='margin-top:10px'>";
			
			echo "<h2>TwordStore Notice</h2>";

			echo $string;
			
			echo "<p>Please visit the <a href='" . $url . "'>TwordStore Management page</a></p>";

			echo "</div>";
			
		}
	
	}
	
	function twordstore_options_page() {

	  ?>
	  <div>
		<h1>TwordStore Settings</h1>
		<form method="post" action="">
			<?PHP
			
				wp_nonce_field('twordstore_manage','twordstore_manage');
			
			?>
			<p>
				Consumer Key<br/>
				<input type="text" name="twordstore_consumer_key" size="100" value="<?PHP echo get_option("twordstore_consumer_key"); ?>" />
			</p>
			<p>
				Consumer Secret<br/>
				<input type="text" name="twordstore_consumer_secret" size="100" value="<?PHP echo get_option("twordstore_consumer_secret"); ?>" />
			</p>
			<p>
				oAuth Token<br/>
				<input type="text" name="twordstore_oauth_token" size="100" value="<?PHP echo get_option("twordstore_oauth_token"); ?>" />
			</p>
			<p>
				oAuth Token Secret<br/>
				<input type="text" name="twordstore_oauth_token_secret" size="100" value="<?PHP echo get_option("twordstore_oauth_token_secret"); ?>" />
			</p>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	  <?php
	}
	
	function twordstore_manage_postform(){
		
		if ($_POST['twordstore_manage']!=""){

			if(!wp_verify_nonce($_POST['twordstore_manage'],'twordstore_manage') ){
			
				print 'Sorry, your nonce did not verify.';
				exit;
				
			}else{			
			
				update_option("twordstore_consumer_key",$_POST["twordstore_consumer_key"]);
				update_option("twordstore_consumer_secret",$_POST["twordstore_consumer_secret"]);
				update_option("twordstore_oauth_token",$_POST["twordstore_oauth_token"]);
				update_option("twordstore_oauth_token_secret",$_POST["twordstore_oauth_token_secret"]);
					
			
			}
		
		}
	
	}
	
	function twordstore_menu_option() {
	
		add_options_page('TwordStore Options', 'TwordStore Options', 'manage_options', 'twordstore', 'twordstore_options_page');
		
	}

?>