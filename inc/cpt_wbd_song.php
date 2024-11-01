<?php
/* Song Custom Post Type */
add_action( 'add_meta_boxes', 'wbd_song_add_custom_boxes' );
add_action( 'save_post', 'wbd_song_save_postdata' );

function wbd_song_add_custom_boxes() {
    add_meta_box(
        'wbd_cpt_details',
        'Album Details',
        'wbd_song_details_box',
        'wbd_song',
        'side',
        'high'
    );
}

function wbd_song_details_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'wbd_song_details_nonce' );

  $meta=get_post_meta($post->ID);

  $_meta_artist_id=(isset($meta['_wbd_song_details_artistid'][0]))? $meta['_wbd_song_details_artistid'][0] : null;
  $meta_artist_id = (isset($_GET['artistid']))? $_GET['artistid'] : $_meta_artist_id;
  echo "<label for='wbd_song_details_artistid'>Artist</label> ";
  echo "<select id='wbd_song_details_artistid' name='wbd_song_details_artistid'>";
  	echo wbd_get_artist_options($meta_artist_id);
  echo "</select>";
  
  $_meta_album_id=(isset($meta['_wbd_song_details_albumid'][0]))? $meta['_wbd_song_details_albumid'][0] : null;
  $meta_album_id = (isset($_GET['albumid']))? $_GET['albumid'] : $_meta_album_id;
  echo "<label for='wbd_song_details_albumid'>Album</label> ";
  echo "<select id='wbd_song_details_albumid' name='wbd_song_details_albumid'>";
  	echo wbd_get_album_options($meta_album_id,$meta_artist_id);
  echo "</select>";
  
  $tracknum = (isset($meta['_wbd_song_details_tracknum'][0]))? $meta['_wbd_song_details_tracknum'][0] : null;
  echo '<label for="wbd_song_details_tracknum">Track Number</label> ';
  echo '<input type="text" id="wbd_song_details_tracknum" name="wbd_song_details_tracknum" value="'.esc_attr($tracknum).'" />';

  $youtubeid = (isset($meta['_wbd_song_details_youtubeid'][0]))? $meta['_wbd_song_details_youtubeid'][0] : null;
  echo '<label for="wbd_song_details_youtubeid">YouTube Video ID</label> ';
  echo '<input type="text" id="wbd_song_details_youtubeid" name="wbd_song_details_youtubeid" value="'.esc_attr($youtubeid).'" />';

  $songlinks = (isset($meta['_wbd_song_details_links'][0]))? $meta['_wbd_song_details_links'][0] : null;
  echo wbd_multi_text_meta($songlinks,'wbd_song_details_links','Links <span>(link text::http://link.url.com)</span>');

  echo "<div style='clear:both;'></div>";
}

function wbd_song_save_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !isset( $_POST['wbd_song_details_nonce'] ) || !wp_verify_nonce( $_POST['wbd_song_details_nonce'], plugin_basename( __FILE__ ) ) )
      return;

    if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  $post_ID = $_POST['post_ID'];

  $posted_artistid = sanitize_text_field( $_POST['wbd_song_details_artistid'] );
  add_post_meta($post_ID, '_wbd_song_details_artistid', $posted_artistid, true) or
    update_post_meta($post_ID, '_wbd_song_details_artistid', $posted_artistid);

  $posted_albumid = sanitize_text_field( $_POST['wbd_song_details_albumid'] );
  add_post_meta($post_ID, '_wbd_song_details_albumid', $posted_albumid, true) or
    update_post_meta($post_ID, '_wbd_song_details_albumid', $posted_albumid);
  add_post_meta($post_ID, 'parent_id', $posted_albumid, true) or
    update_post_meta($post_ID, 'parent_id', $posted_albumid);

  $posted_tracknum = sanitize_text_field( $_POST['wbd_song_details_tracknum'] );
  add_post_meta($post_ID, '_wbd_song_details_tracknum', $posted_tracknum, true) or
    update_post_meta($post_ID, '_wbd_song_details_tracknum', $posted_tracknum);

  $posted_youtubeid = sanitize_text_field( $_POST['wbd_song_details_youtubeid'] );
  add_post_meta($post_ID, '_wbd_song_details_youtubeid', $posted_youtubeid, true) or
    update_post_meta($post_ID, '_wbd_song_details_youtubeid', $posted_youtubeid);

  $posted_links = wbd_posted_group_array($_POST,'wbd_song_details_links_');
  add_post_meta($post_ID, '_wbd_song_details_links', $posted_links, true) or
    update_post_meta($post_ID, '_wbd_song_details_links', $posted_links);

}