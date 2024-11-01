<?php
//add wbd_artist tags to tag arrays
add_filter('pre_get_posts', 'wbd_query_post_type');
function wbd_query_post_type($query) {
  if(is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','wbd_artist');
    $query->set('post_type',$post_type);
	return $query;
    }
}

function wbd_artist_meta($_id=null,$type=null){
	$id=($_id==null)? get_the_ID() : $_id;
	$meta=get_post_meta($id);
	$artisttype = $meta['_wbd_artist_details_artisttype'][0];
	$datestart=$meta['_wbd_artist_details_datestart'][0];
	$dateend=$meta['_wbd_artist_details_dateend'][0];
	$origin=$meta['_wbd_artist_details_origin'][0];
	$label=$meta['_wbd_artist_details_label'][0];
	$youtubeid=$meta['_wbd_artist_details_youtubeid'][0];
	$grpmembers=explode("|",$meta['_wbd_artist_details_grpmembers'][0]);
	$similar_artists=explode("|",$meta['_wbd_artist_details_similar'][0]);
	$links=explode("|",$meta['_wbd_artist_details_links'][0]);

	$born_disp=($artisttype=="artist")? "Born" : "Formed";
	$died_disp=($artisttype=="artist")? "Died" : "Split";
	$return=null;
	
	if($type==null){
		$return.=	"<div class='wbd_meta'><ul>";
		$return.=	"<li class='heading'>Artist Info</li>";
		$return.=	($datestart!='')? "<li><label>$born_disp:</label> $datestart</li>" : "";
		$return.=	($dateend!='')? "<li><label>$died_disp:</label> $dateend</li>" : "";
		$return.=	($label!='')? "<li><label>Label:</label> $label</li>" : "";
		$return.=	($origin!='')? "<li><label>Origin:</label> $origin</li>" : "";
		if(($artisttype=="group")&&(count($grpmembers)!=0)&&($grpmembers[0]!="")){
			$return.=	"<li><label>Members:</label><ul>";
			foreach($grpmembers as $member){
				$return.=	"<li>$member</li>";
			}
			$return.=	"</ul></li>";
		}
		if((count($similar_artists)!=0)&&($similar_artists[0]!="")){
			$return.=	"<li><label>Similar Artists:</label><ul>";
			foreach($similar_artists as $similar_artist_id){
				$artist_permalink=get_permalink($similar_artist_id);
				$return.=	"<li><a href='$artist_permalink'>".get_the_title($similar_artist_id)."</a></li>";
			}
			$return.=	"</ul></li>";
		}
		if((count($links)!=0)&&($links[0]!="")){
			$return.=	"<li><label>Artist Links:</label><ul>";
			foreach($links as $link){
				$link_part=explode("::",$link);

				$return.="<li><a href='".$link_part[1]."' target='_blank' />".$link_part[0]."</a></li>";
			}
			$return.=	"</ul></li>";
		}
		$return.=	"</ul><div style='clear:both'></div></div>";
	}else{
		switch($type){
			case 'artist_type':
				$return=$artisttype;
				break;
			case 'date_start':
				$return=$datestart;
				break;
			case 'date_end':
				$return=$dateend;
				break;
			case 'origin':
				$return=$origin;
				break;
			case 'label':
				$return=$label;
				break;
			case 'yt_id':
				$return=$youtubeid;
				break;
		}
	}
	return $return;
}

function wbd_album_meta($_id=null,$type=null){
	$id=($_id==null)? get_the_ID() : $_id;
	$meta=get_post_meta($id);
	$artistid = $meta['_wbd_album_details_artistid'][0];
	$datepublished = $meta['_wbd_album_details_datepublished'][0];
	$recordlabel = $meta['_wbd_album_details_recordlabel'][0];
	$youtubeid = $meta['_wbd_album_details_youtubeid'][0];
	$links=explode("|",$meta['_wbd_album_details_links'][0]);
	$return=null;

	if($type==null){
		$return.=	"<div class='wbd_meta'><ul>";
		$return.=	"<li class='heading'>Album Info</li>";
		$artist_permalink=get_permalink($artistid);
		$return.=	"<li><label>Artist:</label> <a href='$artist_permalink'>".get_the_title($artistid)."</a></li>";
		$return.=	"<li><label>Published:</label> $datepublished</li>";
		$return.=	"<li><label>Record Label:</label> $recordlabel</li>";
		if((count($links)!=0)&&($links[0]!="")){
			$return.=	"<li><label>Album Links:</label><ul>";
			foreach($links as $link){
				$link_part=explode("::",$link);

				$return.="<li><a href='".$link_part[1]."' target='_blank' />".$link_part[0]."</a></li>";
			}
			$return.=	"</ul></li>";
		}
		$return.=	"</ul><div style='clear:both'></div></div>";
	}else{
		switch($type){
			case 'artist_id':
				$return=$artistid;
				break;
			case 'date_published':
				$return=$datepublished;
				break;
			case 'record_label':
				$return=$recordlabel;
				break;
			case 'youtube_id':
				$return=$youtubeid;
				break;
		}
	}
	return $return;
}

function wbd_song_meta($_id=null,$type=null){
	$id=($_id==null)? get_the_ID() : $_id;
	$meta=get_post_meta($id);
	$artistid = $meta['_wbd_song_details_artistid'][0];
	$albumid = $meta['_wbd_song_details_albumid'][0];
	$youtubeid = $meta['_wbd_song_details_youtubeid'][0];
	$links=explode("|",$meta['_wbd_song_details_links'][0]);
	$return=null;

	if($type==null){
		$return.=	"<div class='wbd_meta'><ul>";
		$return.=	"<li class='heading'>Song Info</li>";
		$artist_permalink=get_permalink($artistid);
		$return.=	"<li><label>Artist:</label> <a href='$artist_permalink'>".get_the_title($artistid)."</a></li>";
		$album_permalink=get_permalink($albumid);
		$return.=	"<li><label>Album:</label> <a href='$album_permalink'>".get_the_title($albumid)."</a></li>";
		if((count($links)!=0)&&($links[0]!="")){
			$return.=	"<li><label>Song Links:</label><ul>";
			foreach($links as $link){
				$link_part=explode("::",$link);

				$return.="<li><a href='".$link_part[1]."' target='_blank' />".$link_part[0]."</a></li>";
			}
			$return.=	"</ul></li>";
		}
		$return.=	"</ul><div style='clear:both'></div></div>";
	}else{
		switch($type){
			case 'artist_id':
				$return=$artistid;
				break;
			case 'album_id':
				$return=$albumid;
				break;
			case 'youtube_id':
				$return=$youtubeid;
				break;
		}
	}
	return $return;
}

function wbd_artist($_id=null){
	global $wbd;
	$id=($_id==null)? get_the_ID() : $_id;
	$return=null;
	$return.= "<div class='wbd_artist'> ";            
    $return.= "<h1 class='post-title'>".get_the_title($id)."</h1>";
                        
    $return.= "<div class='wbd_data'>";
    $return.= (has_post_thumbnail($id))? get_the_post_thumbnail($id) : $wbd->no_thumb;
    $return.= get_the_term_list($id,'wbd_artist_style','<strong>Style:</strong> ',', ','');   
    $return.= wbd_artist_meta();
    $return.= "</div>";

    $return.= "<div class='wbd_content'>";
    $return.= apply_filters('the_content',get_the_content($id));
    $return.= "</div>";
        
    $return.= "<div style='clear:both'></div></div>";
	return $return;
}

function wbd_album($_id=null){
	global $wbd;
	$id=($_id==null)? get_the_ID() : $_id;
	$return=null;
	$return.= "<div class='wbd_album'> ";            
    $return.= "<h1 class='post-title'>".get_the_title($id)."</h1>";
                        
    $return.= "<div class='wbd_data'>";
    $return.= (has_post_thumbnail($id))? get_the_post_thumbnail($id) : $wbd->no_thumb;
    $return.= wbd_album_meta();
    $return.= "</div>";

    $return.= "<div class='wbd_content'>";
    $return.= apply_filters('the_content',get_the_content($id));
    $return.= "</div>";
        
    $return.= "<div style='clear:both'></div></div>";
	return $return;
}

function wbd_song($_id=null){
	global $wbd;
	$id=($_id==null)? get_the_ID() : $_id;
	$artist_id=wbd_song_meta($id,'artist_id');
	$album_id=wbd_song_meta($id,'album_id');
	$return=null;
	$return.= "<div class='wbd_song'> ";            
    $return.= "<h1 class='post-title'>".get_the_title($id)."</h1>";
                        
    $return.= "<div class='wbd_data'>";
    $return.= (has_post_thumbnail($album_id))? get_the_post_thumbnail($album_id) : $wbd->no_thumb;
    $return.= wbd_song_meta();
    $return.= "</div>";

    $return.= "<div class='wbd_content'>";
    $return.= apply_filters('the_content',get_the_content($id));
    $return.= "</div>";
        
    $return.= "<div style='clear:both'></div></div>";
	return $return;
}

function wbd_album_tracklist($_id=null,$display_type='public'){
	global $wbd;
	$album_id=($_id==null)? get_the_ID() : $_id;
	$return=null;
	$childargs = array(
	'post_type' => 'wbd_song',
	'orderby' => 'wbd_song_details_tracknum',
	'order' => 'ASC',
	'meta_key' => '_wbd_song_details_albumid',
	'meta_value' => $album_id,
	'posts_per_page' => 100
	);
	$child_posts = get_posts($childargs);

	if($display_type=='public'){
		$return.= "<div class='wbd_track'>";
		$return.= "<h4>Tracks on ".get_the_title()."</h4>";
		foreach($child_posts as $child){
			$song_link=get_permalink($child->ID);
			$return.= "<div class='wbd_secondary_item'>";
			$return.= "<h3 class='title'>".$child->_wbd_song_details_tracknum.": <a href='$song_link'>".$child->post_title."</a></h3>";
			$return.= "</div>";
		}
		$return.= "<div style='clear:both'></div></div>";
	}elseif($display_type=='admin'){
		$return.= "<ul id='songs'>";
		if(!$child_posts){
			$return.="<li><em>No Tracks Added Yet</em></li>";
		}
		foreach($child_posts as $child){
			$return.= "<li>".$child->_wbd_song_details_tracknum.": <a href='/wp-admin/post.php?post=".$child->ID."&action=edit'>".$child->post_title."</a></li>";
		}
		$return.= "</ul>";
	}

	return $return;
}

function wbd_artist_albums($_id=null,$display_type='public'){
	global $wbd;
	$artist_id=($_id==null)? get_the_ID() : $_id;
	$return=null;

	$childargs = array(
	'post_type' => 'wbd_album',
	'orderby' => 'title',
	'order' => 'ASC',
	'meta_key' => '_wbd_album_details_artistid',
	'meta_value' => $artist_id,
	'posts_per_page' => 100
	);
	$child_posts = get_posts($childargs);

	if($display_type=='public'){
		$return.= "<div class='wbd_album'>";
		$return.= "<h4>Albums by ".get_the_title($artist_id)."</h4>";
		foreach($child_posts as $child){
			$album_link=get_permalink($child->ID);
			$return.= "<div class='wbd_secondary_item'>";
			if(has_post_thumbnail($child->ID)){
				$return.= "<a href='$album_link'>".get_the_post_thumbnail($child->ID, 'thumbnail')."</a>";
			}else{
				$return.= "<a href='$album_link'><img class='attachment-thumbnail wp-post-image' width='150' height='150' alt='name of item' src='http://christian-rock-bands.com/wp-content/plugins/wp-bands-directory/images/nothumb-150x150.png'></a>";
			}
			$return.= "<h3 class='title'><a href='$album_link'>".$child->post_title."</a></h3>";
			$return.= "</div>";
		}
		$return.= "<div style='clear:both'></div></div>";
	}elseif($display_type=='admin'){
		$return.= "<ul id='albums'>";
		if(!$child_posts){
			$return.="<li><em>No Albums Added Yet</em></li>";
		}
		foreach($child_posts as $child){
			$return.= "<li><a href='/wp-admin/post.php?post=".$child->ID."&action=edit'>".$child->post_title."</a></li>";
		}
		$return.= "</ul>";
	}

	return $return;
}

/* Shortcode Display Functions */
function wbd_artist_directory(){
global $wbd;

$artist_args = array(
'post_type' => 'wbd_artist',
'orderby' => 'title',
'order' => 'ASC',
'posts_per_page' => 100
);
$artists = get_posts($artist_args);

	echo "<div id='artist_directory'>";

	foreach($artists as $artist){
		$artist_link=get_permalink($artist->ID);
		echo "<div class='wbd_directory_item'>";
		if(has_post_thumbnail($artist->ID)){
			echo "<a href='$artist_link'>".get_the_post_thumbnail($artist->ID, 'thumbnail')."</a>";
		}else{
			echo "<a href='$artist_link'><img class='attachment-thumbnail wp-post-image' width='150' height='150' alt='name of item' src='http://christian-rock-bands.com/wp-content/plugins/wp-bands-directory/images/nothumb-150x150.png'></a>";
		}
		echo "<h3 class='title'><a href='$artist_link'>".$artist->post_title."</a></h3>";
		echo "</div>";
	}
	
	echo "<div style='clear:both'></div></div>";
}
