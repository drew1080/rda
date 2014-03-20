<?php
	
	global $post;
	$ikn_template_page = get_post_meta($post->ID, 'ikn_template_page', true);
	
	switch ($ikn_template_page) {
	
		case 'default':
		include( TEMPLATEPATH . '/default.php');
		break;
		
		case 'blog':
		include( TEMPLATEPATH . '/blog-page.php');
		break;
		
		case 'portfolio':
		include( TEMPLATEPATH . '/portfolio-page.php');
		break;
		
		case 'gallery':
		include( TEMPLATEPATH . '/gallery-page.php');
		break;
		
		case 'contact_page':
		include( TEMPLATEPATH . '/contact-page.php');
		break;
	
	}

 ?>