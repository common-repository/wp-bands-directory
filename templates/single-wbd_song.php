<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * WP Bands Directory - Song Page Template
 *
 *
 * @file           single-wbd_song.php
 * @package        WP Bands Directory 
 * @author         Bryan Haddock 
 * @copyright      2013
 * @version        Release: 1.0
 * @filesource     wp-content/plugins/wp-bands-directory/templates/single-wbd_song.php
 */

get_header(); ?>

<div id="content">
        
 <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php
	$artist_id=wbd_song_meta($post->ID,'artist_id');
?>

 <div id="wbd_primary">
   <?php echo wbd_song(); ?>
 </div>

 <div id="wbd_secondary">
   <?php echo wbd_artist_albums($artist_id); ?>
 </div>

<?php comments_template(); ?>

 <?php endwhile; else: ?>

 <p>Sorry, no posts matched your criteria.</p>

 <?php endif; ?>
      
</div><!-- end of #content -->

<?php get_footer(); ?>
