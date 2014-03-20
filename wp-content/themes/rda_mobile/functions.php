<?php

/* SET CONSTANT FOR THEME DIRECTORY */

$dirname = get_stylesheet_directory();
define('THEME_DIRECTORY', $dirname . '/');
$lib_path = TEMPLATEPATH . '/lib';
$admin_path = TEMPLATEPATH . '/admin';

/*	THEME NAME AND SHORTNAME	*/

$themename = "iKon";
$shortname = "ikn";

/*	GET THEME PANEL	*/

require_once($admin_path . '/admin_panel.php');

/*	GET PANEL OPTIONS	*/

require_once($admin_path . '/admin_panel_options.php');

/*	FUNCTIONS RELATED TO ADMIN PANEL	*/

require_once($admin_path . '/admin_panel_functions.php');

/*	AJAX FUNCTIONS	*/

require_once($admin_path . '/admin_panel_ajax.php');

/*	THEME FUNCTIONS	*/

require_once($admin_path . '/theme_functions.php');

/*	THEME CUSTOM META BOXES	*/

require_once($lib_path . '/meta_boxes.php');

/*	THEME SHOTCODES	*/

require_once($lib_path . '/shortcodes.php');

/*	THEME WIDGETS	*/

require_once($lib_path . '/widgets.php');

/*	WP PAGENAVI	*/

require_once($lib_path . '/wp-pagenavi.php');


?>