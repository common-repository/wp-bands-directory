<?php
/* Artist Custom Post Type */
add_action( 'add_meta_boxes', 'wbd_artist_add_custom_boxes' );
add_action( 'save_post', 'wbd_artist_save_postdata' );

function wbd_artist_add_custom_boxes() {
    add_meta_box(
        'wbd_cpt_details',
        'Artist Details',
        'wbd_artist_details_box',
        'wbd_artist',
        'side',
        'high'
    );
}

function wbd_artist_details_box( $post ) {
  wp_nonce_field( plugin_basename( __FILE__ ), 'wbd_artist_details_nonce' );
  
  $meta=get_post_meta($post->ID);
  
  //echo "<p>".print_r($meta)."</p>";
  

  $artisttype = (isset($meta['_wbd_artist_details_artisttype'][0]))? $meta['_wbd_artist_details_artisttype'][0] : null;
  echo '<label for="wbd_artist_details_artisttype">Artist or Group</label> ';
  echo '<select id="wbd_artist_details_artisttype" name="wbd_artist_details_artisttype">';
  echo '<option value="artist" ';
  echo ($artisttype=='artist')? 'selected="selected"' : null;
  echo ' >artist</option>';
  echo '<option value="group" ';
  echo ($artisttype=='group')? 'selected="selected"' : null;
  echo ' >group</option>';
  echo '</select>';

  $datestart = (isset($meta['_wbd_artist_details_datestart'][0]))? $meta['_wbd_artist_details_datestart'][0] : null;
  echo '<label for="wbd_artist_details_datestart">Birth Date <span>(artist)</span> or Year Formed <span>(group)</span></label> ';
  echo '<input type="text" id="wbd_artist_details_datestart" name="wbd_artist_details_datestart" value="'.esc_attr($datestart).'" />';

  $dateend = (isset($meta['_wbd_artist_details_dateend'][0]))? $meta['_wbd_artist_details_dateend'][0] : null;
  echo '<label for="wbd_artist_details_dateend">Death Date <span>(artist)</span> or Year Split <span>(group)</span></label> ';
  echo '<input type="text" id="wbd_artist_details_dateend" name="wbd_artist_details_dateend" value="'.esc_attr($dateend).'" />';

  $origin = (isset($meta['_wbd_artist_details_origin'][0]))? $meta['_wbd_artist_details_origin'][0] : null;
  echo '<label for="wbd_artist_details_origin">Origin</label> ';
  echo '<input type="text" id="wbd_artist_details_origin" name="wbd_artist_details_origin" value="'.esc_attr($origin).'" />';

  $label = (isset($meta['_wbd_artist_details_label'][0]))? $meta['_wbd_artist_details_label'][0] : null;
  echo '<label for="wbd_artist_details_label">Recording Label <span>(Current or Most Recent)</span></label> ';
  echo '<input type="text" id="wbd_artist_details_label" name="wbd_artist_details_label" value="'.esc_attr($label).'" />';
  
  $youtubeid = (isset($meta['_wbd_artist_details_youtubeid'][0]))? $meta['_wbd_artist_details_youtubeid'][0] : null;
  echo '<label for="wbd_artist_details_youtubeid">YouTube Video ID</label> ';
  echo '<input type="text" id="wbd_artist_details_youtubeid" name="wbd_artist_details_youtubeid" value="'.esc_attr($youtubeid).'" />';
  
if($artisttype!='artist'){
  $grpmembers = (isset($meta['_wbd_artist_details_grpmembers'][0]))? $meta['_wbd_artist_details_grpmembers'][0] : null;
  echo wbd_multi_text_meta($grpmembers,'wbd_artist_details_grpmembers','Group Members');
}

  echo '<label for="wbd_artist_details_albums">Albums <span>(<a href="/wp-admin/post-new.php?post_type=wbd_album&artistid='.$post->ID.'">click here</a> to add a new album)</span></label> ';
  echo wbd_artist_albums($post->ID,'admin');

  $similar = (isset($meta['_wbd_artist_details_similar'][0]))? $meta['_wbd_artist_details_similar'][0] : null;
  echo wbd_multi_select_meta($similar,'wbd_artist_details_similar','Similar Artists',array('post_type'=>'wbd_artist','posts_per_page' => 100));
  
  $artistlinks = (isset($meta['_wbd_artist_details_links'][0]))? $meta['_wbd_artist_details_links'][0] : null;
  echo wbd_multi_text_meta($artistlinks,'wbd_artist_details_links','Links <span>(link text::http://link.url.com)</span>');

  
  echo "<div style='clear:both;'></div>";
}

function wbd_artist_save_postdata( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !isset( $_POST['wbd_artist_details_nonce'] ) || !wp_verify_nonce( $_POST['wbd_artist_details_nonce'], plugin_basename( __FILE__ ) ) )
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
  
  $posted_artisttype = sanitize_text_field( $_POST['wbd_artist_details_artisttype'] );
  add_post_meta($post_ID, '_wbd_artist_details_artisttype', $posted_artisttype, true) or
    update_post_meta($post_ID, '_wbd_artist_details_artisttype', $posted_artisttype);

  $posted_datestart = sanitize_text_field( $_POST['wbd_artist_details_datestart'] );
  add_post_meta($post_ID, '_wbd_artist_details_datestart', $posted_datestart, true) or
    update_post_meta($post_ID, '_wbd_artist_details_datestart', $posted_datestart);

  $posted_dateend = sanitize_text_field( $_POST['wbd_artist_details_dateend'] );
  add_post_meta($post_ID, '_wbd_artist_details_dateend', $posted_dateend, true) or
    update_post_meta($post_ID, '_wbd_artist_details_dateend', $posted_dateend);

  $posted_origin = sanitize_text_field( $_POST['wbd_artist_details_origin'] );
  add_post_meta($post_ID, '_wbd_artist_details_origin', $posted_origin, true) or
    update_post_meta($post_ID, '_wbd_artist_details_origin', $posted_origin);

  $posted_label = sanitize_text_field( $_POST['wbd_artist_details_label'] );
  add_post_meta($post_ID, '_wbd_artist_details_label', $posted_label, true) or
    update_post_meta($post_ID, '_wbd_artist_details_label', $posted_label);

  $posted_grpmembers = wbd_posted_group_array($_POST,'wbd_artist_details_grpmembers_');
  add_post_meta($post_ID, '_wbd_artist_details_grpmembers', $posted_grpmembers, true) or
    update_post_meta($post_ID, '_wbd_artist_details_grpmembers', $posted_grpmembers);

  $posted_similar = wbd_posted_group_array($_POST,'wbd_artist_details_similar_');
  add_post_meta($post_ID, '_wbd_artist_details_similar', $posted_similar, true) or
    update_post_meta($post_ID, '_wbd_artist_details_similar', $posted_similar);

  $posted_links = wbd_posted_group_array($_POST,'wbd_artist_details_links_');
  add_post_meta($post_ID, '_wbd_artist_details_links', $posted_links, true) or
    update_post_meta($post_ID, '_wbd_artist_details_links', $posted_links);

  $posted_youtubeid = sanitize_text_field( $_POST['wbd_artist_details_youtubeid'] );
  add_post_meta($post_ID, '_wbd_artist_details_youtubeid', $posted_youtubeid, true) or
    update_post_meta($post_ID, '_wbd_artist_details_youtubeid', $posted_youtubeid);

}
