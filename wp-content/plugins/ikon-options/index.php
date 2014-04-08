<?php /*
Plugin Name: iKon Options
Plugin URI: 
Description: Manage options of the iKon Mobile WordPress theme on the same installation with the main theme of your site without to activate mobile version.
Author: Alex Samarschi
Version: 1.0.0
Author URI: http://themeforest.net/user/WebinCode
*/

// You can mess with these if you wish.
$mobiname = "Mobile";
$shortname = "ikn";

if(function_exists('plugin_dir_path'))
	define('IWBC_ABSPATH', plugin_dir_path(__FILE__));
else
	define('IWBC_ABSPATH', dirname(__FILE__) . '/');

if(function_exists('plugin_dir_url'))
	define('IWBC_URI', plugin_dir_url(__FILE__));
else
	define('IWBC_URI', plugins_url('', __FILE__));	

// Get theme panel
include_once(IWBC_ABSPATH . 'admin/iwbc_admin_panel.php');

// Get panel options
include_once(IWBC_ABSPATH . 'admin/iwbc_admin_panel_options.php');

// Functions related to admin panel
include_once(IWBC_ABSPATH . 'admin/iwbc_admin_panel_functions.php');

// Ajax Functions
include_once(IWBC_ABSPATH . 'admin/iwbc_admin_panel_ajax.php');

// Custom theme functions
include_once(IWBC_ABSPATH . 'admin/iwbc_theme_functions.php');

// Custom meta boxes
include_once(IWBC_ABSPATH . 'admin/iwbc_meta_boxes.php');

?>