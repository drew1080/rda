<?php

/*
	Define Paths
*/
$offcite_includes_path = TEMPLATEPATH . '/includes/';

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 522;
	
/*
	Tell WordPress to run offcite_theme_setup() when the 'after_setup_theme' hook is run.
*/
add_action( 'after_setup_theme', 'offcite_theme_setup' );

if ( ! function_exists( 'offcite_theme_setup' ) ) {
	
	function offcite_theme_setup() {
					
		/*
			This feature enables post-thumbnail support for a Theme.
		*/
			add_theme_support( 'post-thumbnails' );
			add_image_size( 'uc-featured', '538', '9999', true );
		
		/*
			This feature enables post and comment RSS feed links to head.
		*/
			add_theme_support( 'automatic-feed-links' );
							
		/*
			Add theme support for custom backgrounds.
		*/
			add_custom_background();
					
	}
	
}

/*
	Default Widgets
*/
require_once ($offcite_includes_path . 'offcite-widgets.php' );

// Add Menu Theme Support
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'nav-menus' );
	add_action( 'init', 'register_menus' );

	function register_menus() {
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu' )
			)
		);
	}
}