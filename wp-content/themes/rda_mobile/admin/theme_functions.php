<?php 
/*  INIT LOCALIZATION  */

load_theme_textdomain('ikon', TEMPLATEPATH . '/lang');

// ADD A FAVICON

function ikon_favicon() { if(get_option('ikn_favicon')<>"") : ?>
<link rel="shortcut icon" href="<?php echo get_option('ikn_favicon'); ?>" />
<?php else : ?>
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/ikn-icon2.png" />
<?php endif; } 

add_action('wp_head', 'ikon_favicon');
add_theme_support('automatic-feed-links');	

//	WP3 MENUS

add_action( 'init', 'register_ikon_menu' );
	function register_ikon_menu() {
	register_nav_menu( 'ikn_mobile_menu', __( 'iKon Menu' ) );
}
// Register sidebar
if ( function_exists('register_sidebar') )
    register_sidebar(array('name' => 'iKon widgets section',
						   'id' => 'ikon-sidebar',
						   'before_widget' => '<div id="%1$s" class="widget-section-widget %2$s">',
						   'after_widget' => '</div><!-- end module -->',
						   'before_title' => '<h3 class="trigger widgets_title"><a href="#"><span>',
						   'after_title' => '</span></a></h3>')
);

// WP 2.9, 3.0 POST THUMBNAILS

if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');

//BLOG, PORTFOLIO AND GALLERY IMAGES	
	add_image_size('iknpostLoopThumb', 92, 92, true);
	add_image_size('iknsinglepostThumb', 290, 230, true);
	add_image_size('galleryImages', 90, 90, true);
	add_image_size('galleryImages', 90, 90, true);
	

//SLIDER IMAGES
	add_image_size('iknsliderImage', 290, 231, true);

//PORTFOLIO IMAGES 

	add_image_size('iknfolioOneColumn', 280, 185, true);
	add_image_size('ikngalleryThumbs', 86, 86, true);
	add_image_size('ikngalleryImageLandscape', 480, 320, true);
	add_image_size('ikngalleryImagePortrait', 320, 480, true);
}
// Add specific CSS class by filter
add_filter('body_class','bg_class_names');
function bg_class_names($classes) {
	$bg_class_name = get_option('ikn_bg_body');
	// add 'class-name' to the $classes array
	$classes[] = $bg_class_name;
	// return the $classes array
	return $classes;
}

// ADD CUSTOM BACKGROUND

function ikon_custom_bg() { ?>

<?php if(get_option('ikn_custom_bg_body')<>"" || get_option('ikn_custom_color_body')<>""  ) :?>
<style type="text/css">
	body{<?php if(get_option('ikn_custom_color_body')<>"" ) : ?>
		background-color:#<?php echo get_option('ikn_custom_color_body'); ?> !important;
	    <?php if(get_option('ikn_custom_bg_body')<>"" ) : ?>
		background-image:url(<?php echo get_option('ikn_custom_bg_body'); ?> ) !important;
		background-position:<?php echo get_option('ikn_back_x'); ?> top !important;
		background-repeat:<?php echo get_option('ikn_back_repeat'); ?> !important;
			<?php else: ?>
			background-image:none;
			<?php endif; ?>
		<?php endif; ?>
	    <?php if(get_option('ikn_custom_bg_body')<>"" && get_option('ikn_custom_color_body')=="" ) : ?>
		background-image:url(<?php echo get_option('ikn_custom_bg_body'); ?> ) !important;
		background-position:<?php echo get_option('ikn_back_x'); ?> top !important;
		background-repeat:<?php echo get_option('ikn_back_repeat'); ?> !important;
		<?php endif; ?>
	}
</style>
<?php endif; ?>
<?php }
function ikon_custom_css() {

	$custom_css = get_option('ikn_custom_css');
	$heading_one = get_option('ikn_size_heading_one');
	$heading_two = get_option('ikn_size_heading_two');
	$heading_three = get_option('ikn_size_heading_three');
	$heading_four = get_option('ikn_size_heading_four');
	$heading_five = get_option('ikn_size_heading_five');
	$body_font = get_option('ikn_general_text');
	
	if($body_font > '12' || $heading_one > '22' || $heading_two > '18' || $heading_three > '16' || $heading_four > '12' || $heading_five > '12') :
	
	echo "<style type='text/css'>\n";
		if($body_font > '12') { echo "body,.post_content p, .entry p, .entry{font-size:".$body_font."px;}\n";}
		if($heading_one > '22') { echo "h1{font-size:".$heading_one."px;}\n";}
		if($heading_two > '18') { echo "h2{font-size:".$heading_two."px;}\n";}
		if($heading_three > '16') { echo "h3{font-size:".$heading_three."px;}\n";}
		if($heading_four > '12') { echo "h4{font-size:".$heading_four."px;}\n";}
		if($heading_five > '12') { echo "h5{font-size:".$heading_five."px;}\n";}
	echo "</style>\n";
	endif;
	
	if($custom_css<>"") : 
	
		echo "<style type='text/css'>\n";
		echo $custom_css."\n";
		echo "</style>\n";
		
	endif;

}
add_action ('wp_head', 'ikon_custom_css');
add_action ('wp_head', 'ikon_custom_bg');

/*	WBC THEME HEAD	*/

add_action('wp_enqueue_scripts', 'wbc_add_scripts');

function wbc_add_scripts() {

	//wp_enqueue_script('jquery');
	wp_dequeue_script('jquery');
	wp_enqueue_script('jquery-old', get_template_directory_uri().'/js/jquery-1.4.2.min.js', array('jquery'), '1.4.2', false);
	wp_enqueue_script('jquery-tools', get_template_directory_uri().'/js/jquery.tools.min.js', array('jquery'), '1.4.8', false);
	wp_enqueue_script('jquery-touch', get_template_directory_uri().'/js/jqtouch.min.js', array('jquery'), '1.4.8', false);
	if(is_front_page() ) {
	wp_enqueue_script('jquery-cycle', get_template_directory_uri().'/js/jquery.cycle.all.min.js', array('jquery'), '1.3', false);
	}
	wp_enqueue_script('custom', get_template_directory_uri().'/js/custom.js', array('jquery'), '1.4.8', false);
	if(get_option('ikn_color_scheme')=='black') :
	wp_enqueue_style("black-css", get_template_directory_uri().'/css/black.css', false, "1.0", "all");
	endif;
	wp_enqueue_style("body-bg", get_template_directory_uri().'/css/bg.css', false, "1.0", "all");
	wp_enqueue_style("custom", get_template_directory_uri().'/custom.css', false, "1.0", "all");		

 }	

/*	WBC FOOTER SCRIPTS	*/

add_action('wp_footer', 'wbc_footer_add_scripts'); 

function wbc_footer_add_scripts() {
	$output = get_option('ikn_googleanalytics');
	if ($output <>"") {
		echo stripslashes($output) . "\n"; 
	}
} // END OF THE FOOTER JAVASCRIPTS

 //GET SOCIAL ICONS
function ikn_get_social_icons() {?>

<div class="soc-content">
	<div class="soc-inner">
         <ul class="soc-list-net">
			
          <?php if(get_option('ikn_social_one')!='Select an icon' && get_option('ikn_social_one_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('glt_social_one_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_one'); ?>" alt="" /></a></li>
          <?php endif; ?>
           <?php if(get_option('ikn_social_two')!='Select an icon' && get_option('ikn_social_two_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('ikn_social_two_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_two'); ?>" alt="" /></a></li>
          <?php endif; ?>
           <?php if(get_option('ikn_social_three')!='Select an icon' && get_option('ikn_social_three_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('ikn_social_three_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_three'); ?>" alt="" /></a></li>
          <?php endif; ?>
           <?php if(get_option('ikn_social_four')!='Select an icon' && get_option('ikn_social_four_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('ikn_social_four_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_four'); ?>" alt="" /></a></li>
          <?php endif; ?>
           <?php if(get_option('ikn_social_five')!='Select an icon' && get_option('ikn_social_five_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('ikn_social_five_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_five'); ?>" alt="" /></a></li>
          <?php endif; ?>
           <?php if(get_option('ikn_social_six')!='Select an icon' && get_option('ikn_social_six_url')<>'' ) : ?>  
          <li><a href="<?php echo get_option('ikn_social_six_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/social_icons/<?php echo get_option('ikn_social_six'); ?>" alt="" /></a></li>
          <?php endif; ?>
                  
                  </ul>

                </div>

              </div>
<?php
 } ?>
<?php  // CUSTOM COMMENTS LIST

function ikn_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		
        <div id="comment-<?php comment_ID(); ?>" class="comment_wrap">			
		
        	<div class="comment-data">

        <div class="comment-avatar"><?php echo get_avatar($comment, $size = '32', $default = get_stylesheet_directory_uri().'/images/default-avatar.png'); ?></div>

<?php if ($comment->comment_approved == '0') : ?>
		
        <p><strong><?php _e('Your comment is awaiting moderation.','gallant') ?></strong></p>
<?php endif; ?>
		
        <div class="comment-author"> <span class="authorname"><?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?></span> <span class="commentdate"> on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a></span></div>
        
		<div class="comment-text"><?php comment_text() ?></div>
        <div class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
        
	</div>
</div>

<?php } ?>
<?php 

// GET AUTHOR INFO

function get_author_info() { ?>

<!--AUTHOR INFO-->
	<div class="author_info_container">
    	<div class="author_inner">
		<div class="author_avatar"><?php echo get_avatar( get_the_author_meta('email'), '75' ); ?></div>
		<div class="author_description"><span class="author_name"><?php the_author_link(); ?></span><br /><span class="author_bio"><?php the_author_meta('description'); ?></span></div>
		<div class="cleaner">&nbsp;</div>
        	</div>
	</div>         
<div class="bt_line_shd">&nbsp;</div>
<div class="bt_shd">&nbsp;</div>  
<!--//END OF AUTHOR INFO-->
<?php
}
 
// GET PAGE ID

function get_page_id($page_name){
	global $wpdb;
	$page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."' AND post_status = 'publish' AND post_type = 'page'");
	return $page_name;
}
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
} 
?>