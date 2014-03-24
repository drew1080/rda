<?php
/*
 * Template Name: Splash Page
 *
 * A custom page template without sidebar.
 *
 */

 ?>
 
 
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>

<?php wp_title('&laquo;', true, 'right'); ?>

<?php bloginfo('name'); ?>

</title>

<?php wp_enqueue_script('jquery'); ?>

<link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/style.css" type="text/css" media="screen" />

<link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/css/blueprint/screen.css" type="text/css" media="screen, projection" />

<link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/css/blueprint/print.css" type="text/css" media="print" />

<link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/js/superfish-1.4.8/css/superfish.css" type="text/css" media="screen, projection" />

<link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/css/rda.css" type="text/css" media="screen, projection" />

<!--[if IE]><link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->

<!--[if IE 7]>  <link rel="stylesheet" href="http://ricedesignalliance.org/wp-content/themes/rda/css/ie7.css" type="text/css" media="screen, projection" />  <![endif]-->

<!--[if lte IE 6]>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie6.css" type="text/css" media="screen, projection" />

<script src="<?php bloginfo('template_url'); ?>/js/ie6pngfix.js"></script>

<script>

  DD_belatedPNG.fix('#header h1 a,#homesidebar ul li#homesidebarcalendar ul li a,#sidebarrp ul li#sidebarcalendar ul li a');

</script>

<![endif]-->



<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<body style="background:#FFFFFF;">





	<div style="width:602px; margin:auto;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center">
<table border="0" cellpadding="0" cellspacing="0" style="width:602px;">
<tr>
	<td align="center" valign="top" colspan="2"><img src="/wp-content/themes/rda/images/splash_top.jpg"></td>
</tr>
<tr>
	<td align="left" valign="top"><img src="/wp-content/themes/rda/images/splash_bottom.jpg"></td>
	<td align="left" valign="top" style="padding-left:275px;" >
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="left" valign="top"><a href="https://signup.rice.edu/2012Tour"><img src="/wp-content/themes/rda/images/splash_tickets.jpg"></a></td>
		</tr>
		<tr>
			<td align="right" valign="top"><a href="http://ricedesignalliance.org/home"><img src="/wp-content/themes/rda/images/splash_website.jpg"></a></td>
		</tr>
		</table>
</tr>
</table>

</td>
<br /></tr>
<br /></table>
</div>