<?php
global $wbd;

/* Artists post type */
function create_post_types() {
global $wbd;
	register_post_type( 'wbd_artist',
		array(
			'labels' => array(
				'name' => 'Artists',
				'singular_name' => 'Artist',
				'add_new' => 'Add A New Artist',
				'add_new_item' => 'Add A New Artist',
				'edit_item' => 'Edit Artist',
				'new_item' => 'New Artist',
				'view_item' => 'View Artist',
				'items_archive' => 'Artist Archive',
				'search_items' => 'Search Artists',
				'not found' => 'No Artists Found',
				'not found_in_trash' => 'No Artists Found in Trash'
			),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => $wbd->artist_slug),
		'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'taxonomies' => array( 'wbd_artist_style' )
		)
	);
	register_taxonomy(
		'wbd_artist_style',  
    	'wbd_artist',  
    	array(  
	        'hierarchical' => false,  
	        'label' => 'Artist Style',  
	        'query_var' => false,  
	        'rewrite' => array('slug' => $wbd->artist_slug.'_styles')  
	    )  
	); 
	register_post_type( 'wbd_album',
		array(
			'labels' => array(
				'name' => 'Albums',
				'singular_name' => 'Albums',
				'add_new' => 'Add A New Album',
				'add_new_item' => 'Add A New Album',
				'edit_item' => 'Edit Album',
				'new_item' => 'New Album',
				'view_item' => 'View Album',
				'items_archive' => 'Album Archive',
				'search_items' => 'Search Albums',
				'not found' => 'No Albums Found',
				'not found_in_trash' => 'No Albums Found in Trash',
				'parent' => 'wbd_artist'
			),
		'public' => true,
		'hierarchical' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => $wbd->album_slug.'/%parent_slug%'),
		'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'taxonomies' => array( 'post_tag' )
		)
	);
	register_post_type( 'wbd_song',
		array(
			'labels' => array(
				'name' => 'Songs',
				'singular_name' => 'Songs',
				'add_new' => 'Add A New Song',
				'add_new_item' => 'Add A New Song',
				'edit_item' => 'Edit Song',
				'new_item' => 'New Song',
				'view_item' => 'View Song',
				'items_archive' => 'Song Archive',
				'search_items' => 'Search Songs',
				'not found' => 'No Songs Found',
				'not found_in_trash' => 'No Songs Found in Trash',
				'parent' => 'wbd_album'
			),
		'public' => true,
		'hierarchical' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => $wbd->song_slug.'/%parent_slug%'),
		'supports' => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'taxonomies' => array( 'post_tag' )
		)
	);

}

/* Adds Custom Permalinks for WBD Albums/Songs */
function wbd_cpt_link_filter($post_link, $post){
	$meta=get_post_meta($post->ID);
	if(count($meta)!=0){
	switch($post->post_type){
		case 'wbd_album':
			$artist_slug = wbd_the_slug($meta['_wbd_album_details_artistid'][0]);
			return str_replace('%parent_slug%', $artist_slug, $post_link);
			break;
		case 'wbd_song':
			$artist_slug = wbd_the_slug($meta['_wbd_song_details_artistid'][0]);
			$album_slug = wbd_the_slug($meta['_wbd_song_details_albumid'][0]);
			$newslug="$artist_slug/$album_slug";
			return str_replace('%parent_slug%', $newslug, $post_link);
			break;
		default:
			return $post_link;
	}
	}else{
		return 0;
	}
}

/* REWRITE RULES */
function add_rewrite_rules(){
global $wbd;
	global $wp_rewrite;
	$new_rules = array(
		$wbd->album_slug.'/(.*?)/(.*?)/?$' => 'index.php?wbd_album='.$wp_rewrite->preg_index(2),
		$wbd->song_slug.'/(.*?)/(.*?)/(.*?)/?$' => 'index.php?wbd_song='.$wp_rewrite->preg_index(3)
	);
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

function wbd_query_vars( $query_vars )
{
    $query_vars[] = 'wbd_artist';
    $query_vars[] = 'wbd_album';
    $query_vars[] = 'wbd_song';
    return $query_vars;
}

/* Flush Permalinks on Activation */
function wbd_activate() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules(); 
}

/* Flush Permalinks on Deactivation */
function wbd_deactivate() {
    global $wp_rewrite;    
    $wp_rewrite->flush_rules(); 
}

register_activation_hook( __FILE__, 'wbd_activate' );
register_deactivation_hook( __FILE__, 'wbd_deactivate' );

if(($wbd->artist_slug!=null)&&($wbd->album_slug!=null)&&($wbd->song_slug!=null)){
	add_filter('post_type_link','wbd_cpt_link_filter',1,2);
	add_action('init','create_post_types');
	add_action('generate_rewrite_rules','add_rewrite_rules');
	add_filter('query_vars','wbd_query_vars');
	//register_taxonomy_for_object_type('wbd_artist_style','wbd_artist');
	
	include("cpt_wbd_artist.php");
	include("cpt_wbd_album.php");
	include("cpt_wbd_song.php");
}