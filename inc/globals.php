<?php
//Global Variables
$wbd_array=array(
	'artist_slug'	=>	get_option('wbd_artist_slug'),
	'album_slug'	=>	get_option('wbd_album_slug'),
	'song_slug'		=>	get_option('wbd_song_slug'),
	'no_thumb' 		=>	'<img class="attachment-post-thumbnail" src="'.plugins_url().'/wp-bands-directory/images/no-image-available.png">'
);
$wbd=(object)$wbd_array;