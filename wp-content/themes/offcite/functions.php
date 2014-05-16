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
	$content_width = 640;
	
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

// START HIOWEB CUSTOMIZATIONS

add_filter( 'post_thumbnail_html', 'my_post_image_html', 10, 3 );

function my_post_image_html( $html, $post_id, $post_image_id ) {
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html .   '</a>' . the_post_thumbnail_caption();
  return $html;
}


function the_post_thumbnail_caption() {
  global $post;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
  $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

  if ($thumbnail_image && isset($thumbnail_image[0])) {
    $post_caption =  '<p>'.$thumbnail_image[0]->post_excerpt.'</p>';
  }
  return $post_caption;
}