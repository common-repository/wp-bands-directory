<?php
/* Album Custom Post Type */
add_action( 'add_meta_boxes', 'wbd_album_add_custom_boxes' );
add_action( 'save_post', 'wbd_album_save_postdata' );

function wbd_album_add_custom_boxes() {
    add_meta_box(
        'wbd_cpt_details',
        'Album Details',
        'wbd_album_details_box',
        'wbd_album',
        'side',
        'high'
    );
}

function wbd_album_details_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'wbd_album_details_nonce' );

  $meta=get_post_meta($post->ID);

  $meta_artist_id = (isset($meta['_wbd_album_details_artistid'][0]))? $meta['_wbd_album_details_artistid'][0] : null;
  
  echo "<label for='wbd_album_details_artistid'>Artist</label> ";
  echo "<select id='wbd_album_details_artistid' name='wbd_album_details_artistid'>";
  	echo wbd_get_artist_options($meta_artist_id);
  echo "</select>";
  
  $datepublished = (isset($meta['_wbd_album_details_datepublished'][0]))? $meta['_wbd_album_details_datepublished'][0] : null;
  echo '<label for="wbd_album_details_datepublished">Year Published</label> ';
  echo '<input type="text" id="wbd_album_details_datepublished" name="wbd_album_details_datepublished" value="'.esc_attr($datepublished).'" />';

  $recordlabel = (isset($meta['_wbd_album_details_recordlabel'][0]))? $meta['_wbd_album_details_recordlabel'][0] : null;
  echo '<label for="wbd_album_details_recordlabel">Record Label</label> ';
  echo '<input type="text" id="wbd_album_details_recordlabel" name="wbd_album_details_recordlabel" value="'.esc_attr($recordlabel).'" />';

  $youtubeid = (isset($meta['_wbd_album_details_youtubeid'][0]))? $meta['_wbd_album_details_youtubeid'][0] : null;
  echo '<label for="wbd_album_details_youtubeid">YouTube Video ID</label> ';
  echo '<input type="text" id="wbd_album_details_youtubeid" name="wbd_album_details_youtubeid" value="'.esc_attr($youtubeid).'" />';

  echo '<label for="wbd_album_details_tracklist">Track List <span>(<a href="/wp-admin/post-new.php?post_type=wbd_song&artistid='.$meta_artist_id.'&albumid='.$post->ID.'">click here</a> to create a new track)</span></label> ';
  echo wbd_album_tracklist($post->ID,'admin');

  $albumlinks = (isset($meta['_wbd_album_details_links'][0]))? $meta['_wbd_album_details_links'][0] : null;
  echo wbd_multi_text_meta($albumlinks,'wbd_album_details_links','Links <span>(link text::http://link.url.com)</span>');

  echo "<div style='clear:both;'></div>";

}

function wbd_album_save_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !isset( $_POST['wbd_album_details_nonce'] ) || !wp_verify_nonce( $_POST['wbd_album_details_nonce'], plugin_basename( __FILE__ ) ) )
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

  $posted_artistid = sanitize_text_field( $_POST['wbd_album_details_artistid'] );
  add_post_meta($post_ID, '_wbd_album_details_artistid', $posted_artistid, true) or
    update_post_meta($post_ID, '_wbd_album_details_artistid', $posted_artistid);
  add_post_meta($post_ID, 'parent_id', $posted_artistid, true) or
    update_post_meta($post_ID, 'parent_id', $posted_artistid);

  $posted_datepublished = sanitize_text_field( $_POST['wbd_album_details_datepublished'] );
  add_post_meta($post_ID, '_wbd_album_details_datepublished', $posted_datepublished, true) or
    update_post_meta($post_ID, '_wbd_album_details_datepublished', $posted_datepublished);

  $posted_recordlabel = sanitize_text_field( $_POST['wbd_album_details_recordlabel'] );
  add_post_meta($post_ID, '_wbd_album_details_recordlabel', $posted_recordlabel, true) or
    update_post_meta($post_ID, '_wbd_album_details_recordlabel', $posted_recordlabel);

  $posted_youtubeid = sanitize_text_field( $_POST['wbd_album_details_youtubeid'] );
  add_post_meta($post_ID, '_wbd_album_details_youtubeid', $posted_youtubeid, true) or
    update_post_meta($post_ID, '_wbd_album_details_youtubeid', $posted_youtubeid);

  $posted_tracklist = (isset($_POST['wbd_album_details_tracklist']))? $_POST['wbd_album_details_tracklist'] : null;
  add_post_meta($post_ID, '_wbd_album_details_tracklist', $posted_tracklist, true) or
    update_post_meta($post_ID, '_wbd_album_details_tracklist', $posted_tracklist);

  $posted_links = wbd_posted_group_array($_POST,'wbd_album_details_links_');
  add_post_meta($post_ID, '_wbd_album_details_links', $posted_links, true) or
    update_post_meta($post_ID, '_wbd_album_details_links', $posted_links);

}