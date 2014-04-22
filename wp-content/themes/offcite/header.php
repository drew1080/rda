<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<title><?php bloginfo( 'name' ); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/offcite/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/offcite/css/print.css" type="text/css" media="print" />  
<!--[if IE]>
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/offcite/css/ie.css" type="text/css" media="screen, projection" />
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/offcite/css/ie6.css" type="text/css" media="screen, projection" />
<![endif]-->
<script type="text/javascript">
try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(err) {}
</script>
<link rel="pingback" href="<?php echo get_site_url(); ?>/xmlrpc.php" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="container">
<div id="center">
    <div id="header">
            <h3 id="rdalink"><a href="http://ricedesignalliance.org" title="Rice Design Alliance">Rice Design Alliance</a></h3>
			<?php 
				if ( function_exists( 'wp_nav_menu' ) ) {
					wp_nav_menu( 'theme_location=primary&container=false&menu_id=headernavigation' );
				} else { ?>
				 <ul id="headernavigation">
					<li id="feeds"><a href="<?php echo get_site_url(); ?>/feeds" title="Offcite Feeds">Feeds</a></li>
					<li id="contact"><a href="<?php echo get_site_url(); ?>/contact" title="Contact Offcite">Contact</a></li>
					<li id="about"><a href="<?php echo get_site_url(); ?>/about" title="About Offcite">About</a></li>
				</ul>
			<?php } ?>
            <h1 id="logo"><a href="<?php echo get_site_url(); ?>/" title="Offcite Blog | Design.  Houston.  Architecure.">Offcite Blog | Design.  Houston.  Architecure.</a></h1>
        </div><!--end header div-->