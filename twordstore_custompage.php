<?PHP

	add_action('init', 'twordstore_custom_page_type_create');
	
	
	function twordstore_custom_page_type_create()
	{
	  $labels = array(
		'name' => _x('TwordStores', 'post type general name'),
		'singular_name' => _x('TwordStore', 'post type singular name'),
		'add_new' => _x('Add New', 'twordstore'),
		'add_item' => __('Add New '),
		'edit_item' => __('Edit TwordStore'),
		'item' => __('New TwordStore'),
		'view_item' => __('View TwordStore'),
		'search_items' => __('Search TwordStore'),
		'not_found' =>  __('No TwordStores found'),
		'not_found_in_trash' => __('No TwordStores found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => 'TwordStore'

	  );
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'_edit_link' => 'post.php?post=%d',
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'description' => 'A Collection of terms which which to search for resources with',
		'supports' => array('title')
	  ); 
	  register_post_type('twordstore',$args);

	}
