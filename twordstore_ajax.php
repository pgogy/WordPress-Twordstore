<?PHP.

	add_action("wp_head","tweet_harvest_ajax_prepare");
	add_action("wp_print_scripts","tweet_harvest_ajax_prepare");
	
	function tweet_harvest_ajax_prepare(){
	
		?>
		<script type="text/javascript">
		ajaxurl = "<?PHP echo site_url(); ?>/wp-admin/admin-ajax.php";
		</script><?PHP
	
		wp_enqueue_script( "twordstore", path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/js/twordstore.js"), array( 'jquery' ) );
				
	}

?>