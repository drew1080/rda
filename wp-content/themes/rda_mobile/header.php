<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-icon" href="<?php echo get_option('ikn_apple_touch'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php bloginfo_rss('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<title><?php bloginfo('name'); wp_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

<?php wp_head(); ?>

<script>
            var jQT = new jQuery.jQTouch({
                icon: '<?php bloginfo('template_directory'); ?>/images/ikon_icon.png',
                startupScreen: '<?php bloginfo('template_directory'); ?>/images/ik_startup.png',
                
            });           
</script>

<script>
		jQuery(document).ready(function(){	
		jQuery('.slideshow').cycle({ 
	    fx:     '<?php echo get_option('ikn_slider_fx'); ?>', 
    	speed:   800, 
    	timeout: <?php echo get_option('ikn_slider_timer'); ?>,  
    	pause:   1,   
	    next:   '#nextBtn', 
	    prev:   '#prevBtn',
	    cleartype: true,
	    cleartypeNoBg: true
	});
		});	
	</script>

    
</head>
<body <?php body_class(); ?>>

 
<?php if(is_single() ) : comments_template(); endif; ?>
 
<!--PAGE WRAPPER-->
<div id="page" class="current">


<!--WRAPPER-->
<div id="wrapper">
	 <!--MAIN NAVIGATION-->
		  <?php wp_nav_menu( array( 'container' => 'nav', 'theme_location' => 'ikn_mobile_menu', 'container_id' => 'main-nav' ) ); ?>
	  <!--//END OF MAIN NAVIGATION--> 

  <div class="header">
 
    <!--TOP LINKS-->
    <div class="top-links">
	
	  <!--LOGO-->
    <div id="logo" style="float:left; padding-top:5px;"> 
    <a class="logo" href="<?php bloginfo('url'); ?>">
    <?php if(get_option('ikn_logo')<>"") : ?>
    	<img src="<?php echo get_option('ikn_logo') ?>" alt="<?php bloginfo('name'); ?>" />
    <?php else: ?>
    	<img src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" />
    <?php endif; ?>
    </a>
    </div>
    <!--//END OF LOGO-->
		
     <div style="padding-left:240px; padding-top:10px;">  <a class="t-menu" href="#"><span><?php _e('menu', 'ikon'); ?></span></a></div>

       

    </div>
<!--//END OF TOP LINKS-->  


  </div>
  <!--//END OF HEADER-->

<?php if(is_front_page()) : ?>

<div class="logofeaturedbg">	
	<?php include( TEMPLATEPATH . '/lib/slider.php'); ?>    
        
 </div>
 <div class="bt_line_shd">&nbsp;</div>
<div class="bt_shd">&nbsp;</div>
    
<?php endif; ?>

<?php if(!is_front_page() ) : ?>
    <!--TITLE-->
    <div class="title">
        <h1><?php 
                global $post;			
                if(is_category()): single_cat_title();
                elseif(is_single() ) : $category = get_the_category(); echo $category[0]->cat_name;
                else: wp_title('',true,''); endif;  ?>
        </h1>
    	</div>
        <div class="title_bt_shadow">&nbsp;</div>
    <!--//END OF TITLE-->
<?php endif; ?>