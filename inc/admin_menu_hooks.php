<?php

//ADMIN MENU INTERFACES

add_action('admin_menu','wbd_menu');

function wbd_menu() {

	add_menu_page('Bands Directory','Bands Directory','administrator','wbd_settings','wbd_settings_page');



}
