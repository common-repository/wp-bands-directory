<?php
/*
Plugin Name: WP Bands Directory
Plugin URI: http://www.blazingtorch.com
Description: This is a plugin that creates custom post types for artists and albums so that they can be displayed in a directory fashion.
Version: 0.5
Author: Bryan Haddock
Author URI: http://www.blazingtorch.com/
*/

/*  Copyright 2011-2012 Bryan Haddock

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include("inc/globals.php");
include("inc/functions.php");
include("inc/admin_menu_hooks.php");
include("inc/custom_post_functions.php");
include("inc/display_functions.php");

add_action('admin_init','wbd_admin_jquery_init');
add_action('admin_init','wbd_admin_css_init');
add_action('admin_enqueue_scripts','wbd_admin_enqueue');
add_action('wp_enqueue_scripts','wbd_public_enqueue');

//ADMIN INCLUDES
include("inc/wbd_settings_page.php");

//SHORTCODE INCLUDES
add_shortcode('wbd_artists','wbd_artist_directory');

