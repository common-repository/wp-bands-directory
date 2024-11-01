<?php
function wbd_settings_page() {
?>
<div class="wrap">

<h2>WP Bands Directory General Settings</h2>

<?php
$action=isset($_POST['action'])? $_POST['action'] : null;
$showflushbutton=null;
if($action=='update_rewrites') {

	//Validate - fields must not be blank or contain special characters
	if(wbd_check_slugs(array($_POST['wbd_artist_slug'],$_POST['wbd_album_slug'],$_POST['wbd_song_slug']))){
		$kv=array();
		foreach($_POST as $key => $value){
			if($key!="update_rewrites"){
			update_option($key,stripslashes($value));	
			}
		}
		//after slugs are set, make sure to add them to the rewrite rules, then flush
global $wp_rewrite;
$wp_rewrite->flush_rules();
$showflushbutton=1;
		echo "<div id='message' class='updated below-h2'><p>Rewrites updated. Press the 'Flush Rewrites' button below to finish up.</p></div>";
	}else{
		echo "<div id='message' class='error below-h2'><p>Rewrites not updated. Make sure all base slugs are unique and do not contain special characters (alphanumeric and dashes only).</p></div>";
	}
}

if($action=='extra_flush') {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
		echo "<div id='message' class='updated below-h2'><p>URL Rewrites all set! Enjoy using WP Bands Directory!</p></div>";
}

if($action=='copy_template_files') {

$file = dirname(dirname(__FILE__)).'/wp-bands-directory.php';
$plugin_path = plugin_dir_path($file);

$theme_path=get_template_directory();

if($_POST['artist_template']==true){copy($plugin_path.'templates/single-wbd_artist.php', $theme_path.'/single-wbd_artist.php');}
if($_POST['album_template']==true){copy($plugin_path.'templates/single-wbd_album.php', $theme_path.'/single-wbd_album.php');}
if($_POST['song_template']==true){copy($plugin_path.'templates/single-wbd_song.php', $theme_path.'/single-wbd_song.php');}

echo "<div id='message' class='updated below-h2'><p>Selected Template Files Copied To Theme Folder.</p></div>";
}


if($action=='update_settings') {
/*
	$kv=array();
	foreach($_POST as $key => $value){
		if($key!="update_settings"){
		update_option($key,stripslashes($value));	
		}
	}
*/
echo "<div id='message' class='updated below-h2'><p>Settings Updated.</p></div>";
}
?>

<?php if($showflushbutton){ ?>

<form method="post">
	<input type="hidden" name="action" value="extra_flush" />

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Flush Rewrites') ?>" />
    </p>
</form>

<?php }else{ ?>

<h3>Initial Setup</h3>
<p>Before you can start using this plugin, you need to set up the URL Rewrites, so that your bands directory will function properly. Use the "WP Bands Directory URL Rewrites" section below to set these up and click 'Update URL Structure' once you've done this, your directory will be ready to start adding Artists, Albums, and Songs!</p>

<p>In order to use the Artist/Album/Song templates provided with this plugin, please use the the "WP Bands Directory Template Files" section below to copy the specified templates into your theme's folder.</p>

<p>Create a page for your "directory" (page that shows all artists and links to the artist pages) by pasting the [wbd_artists] shortcode into the editor for that page.</p>

<p>Have fun using this plugin and if you have any questions, requests, or positive feedback, we would love to hear from you. Contact us using the support form at <a href="http://www.blazingtorch.com/contact" target="_blank">http://www.blazingtorch.com/contact</a></p>

<div id="wbd_settings_rewrites">
<h3>WP Bands Directory URL Rewrites</h3>

<form method="post">
	<input type="hidden" name="action" value="update_rewrites" />

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Base Slug for Artist pages:</th>
        <td>
        What base slug do you want to use for artist pages?<br />
        <?php bloginfo('wpurl'); ?>/<input type="text" name="wbd_artist_slug" value="<?php echo get_option('wbd_artist_slug'); ?>" />/artist-name
        <br /><em>(only use alphanumeric characters and dashes)</em>
		</td>
        </tr>

        <tr valign="top">
        <th scope="row">Base Slug for Album pages:</th>
        <td>
        What base slug do you want to use for album pages?<br />
        <?php bloginfo('wpurl'); ?>/<input type="text" name="wbd_album_slug" value="<?php echo get_option('wbd_album_slug'); ?>" />/artist-name/album-name 
        <br /><em>(only use alphanumeric characters and dashes)</em>
		</td>
        </tr>

        <tr valign="top">
        <th scope="row">Base Slug for Song pages:</th>
        <td>
        What base slug do you want to use for Song pages?<br />
        <?php bloginfo('wpurl'); ?>/<input type="text" name="wbd_song_slug" value="<?php echo get_option('wbd_song_slug'); ?>" />/artist-name/album-name/song-name 
        <br /><em>(only use alphanumeric characters and dashes)</em>
		</td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Update URL Structure') ?>" />
    </p>

</form>
</div> <!-- Close WBD Settings Rewrites -->

<div id="wbd_template_files">
<h3>WP Bands Directory Template Files</h3>

<p>Use the checkboxes below to select which display templates you want to copy to your current theme directory.</p>

<p><strong>NOTE: adding the template files to your theme's directory using the form below will provide a basic layout for artist/album/song pages... for the most customized solution, use these templates as a starting point and add your own modifications.</strong></p>

<form method="post">
	<input type="hidden" name="action" value="copy_template_files" />

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><input type="checkbox" name="artist_template" /></th>
        <td>Copy <strong>artist</strong> template to your current theme directory.</td>
        </tr>

        <tr valign="top">
        <th scope="row"><input type="checkbox" name="album_template" /></th>
        <td>Copy <strong>album</strong> template to your current theme directory.</td>
        </tr>

        <tr valign="top">
        <th scope="row"><input type="checkbox" name="song_template" /></th>
        <td>Copy <strong>song</strong> template to your current theme directory.</td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Copy Selected Template Files To My Current Theme Directory') ?>" />
    </p>

</form>
</div> <!-- Close WBD Template Files -->

<? } ?>

</div> <!-- Close Wrap -->
<?php }