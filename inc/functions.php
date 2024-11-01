<?php

add_filter('widget_text', 'do_shortcode');

// GENERAL FUNCTIONS

function wbd_admin_jquery_init() {
    wp_register_script( 'wbd-admin-jquery', plugins_url( '/wp-bands-directory/js/wbd_admin_jquery.js'));
}

function wbd_admin_css_init() {
    wp_register_style( 'wbd-admin-css', plugins_url( '/wp-bands-directory/css/admin.css'));
}

function wbd_admin_enqueue() {
    wp_enqueue_script( 'wbd-admin-jquery' );
    wp_enqueue_style( 'wbd-admin-css' );
}

function wbd_public_enqueue() {
	wp_register_style('wbd-public-css',plugins_url('/wp-bands-directory/css/public.css'));
	wp_enqueue_style('wbd-public-css');
}

function wbd_the_slug($id) {
	$post_data = get_post($id, ARRAY_A);
	$slug = (isset($post_data['post_name']))? $post_data['post_name'] : null;
	return $slug; 
}

function wbd_check_slugs($array) {
	$i=0;
	//count all values
	$total=count($array);
	//count unique values
	$unique_count=count(array_unique($array));
	//no illegal characters
	foreach ($array as $item){
		if((preg_match('/^[A-Za-z0-9-]+$/',$item))&&($item!="")){
			$i++;
		}
	}
	return (($total==$unique_count)&&($total==$i))? true : false;
}

function wbd_get_artist_options($selected_artist){
  $artist_array=get_posts(array('post_type'=>'wbd_artist','posts_per_page' => 100));
  $return=null;
  foreach($artist_array as $artist){
  	$select_statement=($selected_artist==$artist->ID)? "selected='selected'" : null;
  	$return.="<option value='".$artist->ID."' $select_statement>".$artist->post_title."</option>";
  }

  return $return;
}

function wbd_get_album_options($selected_album,$artist_id){
  $album_array=get_posts(array('post_type'=>'wbd_album','meta_key'=>'_wbd_album_details_artistid','meta_value'=>$artist_id,'posts_per_page' => 100));
  foreach($album_array as $album){
  	$select_statement=($selected_album==$album->ID)? "selected='selected'" : null;
  	$return.="<option value='".$album->ID."' $select_statement>".$album->post_title."</option>";
  }
  return $return;
}

function wbd_select_options($selected,$select_args){
  $options=get_posts($select_args);
  $return=null;
  foreach($options as $option){
  	$select_statement=($selected==$option->ID)? "selected='selected'" : null;
  	$return.="<option value='".$option->ID."' $select_statement>".$option->post_title."</option>";
  }
	return $return;
}

function wbd_input_display($field_name,$i,$selected_val,$select_args){
	$return=null;
	$return.=	"<div id='$field_name".'ItemDiv'."$i'>";
	$return.= 	"<select id='$field_name"."_$i"."' name='$field_name"."_$i"."'>";
	$return.=	"<option value=''>- select option -</option>";
	$return.= 	wbd_select_options($selected_val,$select_args);
	$return.=	"</select>";
	$return.=	"</div>";
	
	return $return;
}

function wbd_multi_text_meta($value_string,$field_name,$label,$line_fields=null){
  $value_array=explode("|",$value_string);
  
  $return=null;
  $return.= "<label for='$field_name'>$label</label> ";
  $return.=	"<div id='$field_name".'Group'."'>";
  
  $i=1;
  foreach($value_array as $val){
	  $return.=	"<div id='$field_name".'ItemDiv'."$i'>";
	  $return.= "<input type='text' id='$field_name"."_$i"."' name='$field_name"."_$i"."' value='".esc_attr($val)."' />";
	  $return.=	"</div>";
  $i++;
  }

$return.=	"</div><div class='multibuttons'>";
$return.=	"<input type='button' value='+' class='addButton' rel='$field_name' />";
$return.=	"<input type='button' value='-' class='removeButton' rel='$field_name' />";
$return.=	"<input type='hidden' id='$field_name".'itemcount'."' rel='$i' />";
$return.=	"<input type='hidden' id='$field_name".'fieldtype'."' rel='text' />";
$return.=	"</div>";

  return $return;
}

function wbd_multi_select_meta($value_string,$field_name,$label,$select_args){
	
  $value_array=explode("|",$value_string);
  $return=null;
  $return.= "<label for='$field_name'>$label</label> ";
  $return.=	"<div id='$field_name".'Group'."'>";
  $i=1;
  foreach($value_array as $val){
	  $return.= wbd_input_display($field_name,$i,$val,$select_args);
  $i++;
  }

$return.=	"</div><div class='multibuttons'>";
$return.=	"<input type='button' value='+' class='addButton' rel='$field_name' />";
$return.=	"<input type='button' value='-' class='removeButton' rel='$field_name' />";
$return.=	"<input type='hidden' id='$field_name".'itemcount'."' rel='$i' />";
$return.=	"<input type='hidden' id='$field_name".'fieldtype'."' rel='select' />";
$return.=	"</div>";

  return $return;
}

function wbd_posted_group_array($post_array,$key_prefix){
	$return=null;
	foreach($post_array as $k=>$v){
		if(strpos($k,$key_prefix)!==false){
		$return.= $v."|";
		}
	}
	return substr($return,0,-1);
}
