	
	function add_shortcode(tweet_id,tweet_id_two){
		
		iframe = document.getElementById('content_ifr');
		
		var doc = iframe.contentDocument? iframe.contentDocument: iframe.contentWindow.document;
		
		doc.body.innerHTML += "[twordstore_single_tweet id='" + tweet_id.split("*").join("0").split('"').join("") + "" + tweet_id_two.split("*").join("0").split('"').join("") + "']";
	}
	
	function twordstore_ajax_search(post,term,type,time,replace){	

		jQuery(document).ready(function($) {
						
			var data = {
				action: 'twordstore_trigger',
				twordstore_post : post,
				term_search : term,
				type_search : type,
				time_search : time
			};			
				
			jQuery.post(ajaxurl, data, 
					
			function(response){
			
				if(replace=="replace"){
				
					document.getElementById("harvest_now").innerHTML = response;
					
				}
					
			});
					
		});
	
	}
		
	function twordstore_ajax_trigger(){

		jQuery(document).ready(function($) {
						
			var data = {
				action: 'twordstore_trigger_fake_cron'
			};				
				
			jQuery.post(ajaxurl, data, 
					
			function(response){
					
			});
					
		});
	
	}
	
	function twordstore_editor_get_posts_tweets(){

		jQuery(document).ready(function($) {
						
			var data = {
				action: 'twordstore_edit_get_store',
				post_id:document.getElementById('twordstore_get_post').value
			};				
				
			jQuery.post(ajaxurl, data, 
					
			function(response){
			
				document.getElementById('twordstore_returned_tweets').innerHTML = response;
				
			});
					
		});
	
	}
		
		window.onload = twordstore_ajax_trigger;
		