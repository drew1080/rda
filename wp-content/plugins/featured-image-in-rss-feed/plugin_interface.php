<?php
// create custom plugin settings menu
add_action('admin_menu', 'fir_create_menu');

function fir_create_menu() {
	add_options_page('Image In RSS Feed', 'Image In Rss Feed', 'administrator', __FILE__, 'fir_settings_page');
	add_action('admin_init', 'register_firsettings');
}

function register_firsettings() {
	register_setting('fir-settings-group', 'fir_rss_image_size');
}

function fir_settings_page() {
	$fir_rss_image_size 			= get_option('fir_rss_image_size');
	if (empty($fir_rss_image_size)){
		update_option('fir_rss_image_size', 'thumbnail');
		$fir_rss_image_size	= get_option('fir_rss_image_size');
	}
	include('includes/fir_header.php');
	include('includes/fir_image_select.php');
	include('includes/fir_footer.php');
}