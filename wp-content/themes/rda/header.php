<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>

<?php wp_title('&laquo;', true, 'right'); ?>

<?php bloginfo('name'); ?>

</title>

<?php //wp_enqueue_script('jquery'); ?>


<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/style.css" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/css/blueprint/screen.css" type="text/css" media="screen, projection" />

<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/css/blueprint/print.css" type="text/css" media="print" />

<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/js/superfish-1.4.8/css/superfish.css" type="text/css" media="screen, projection" />

<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/css/rda.css" type="text/css" media="screen, projection" />

<!--[if IE]><link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->

<!--[if IE 7]>  <link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/css/ie7.css" type="text/css" media="screen, projection" />  <![endif]-->

<!--[if lte IE 6]>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie6.css" type="text/css" media="screen, projection" />

<script src="<?php bloginfo('template_url'); ?>/js/ie6pngfix.js"></script>

<script>

  DD_belatedPNG.fix('#header h1 a,#homesidebar ul li#homesidebarcalendar ul li a,#sidebarrp ul li#sidebarcalendar ul li a');

</script>

<![endif]-->
<?php wp_head(); ?>

<?php if ( is_front_page() ) { ?>
    
<link rel="stylesheet" href="http://www.jacklmoore.com/colorbox/example1/colorbox.css" />


<script type="text/javascript">
// Skip this Page Script (c)2012 John Davenport Scheuer
// as first seen in http://www.dynamicdrive.com/forums/
// username: jscheuer1 - This Notice Must Remain for Legal Use
;(function(setting){
	var cook = {
		set: function(n, v, d){ // cook.set takes (name, value, optional_persist_days) - defaults to session if no days specified
			if(d){var dt = new Date(); 
				dt.setDate(dt.getDate() + d);
			d = '; expires=' + dt.toGMTString();}
			document.cookie = n + '=' + escape(v) + (d || '') + '; path=/';
		},
		get: function(n){ // cook.get takes (name)
			var c = document.cookie.match('(^|;)\x20*' + n + '=([^;]*)');
			return c? unescape(c[2]) : null;
		}
	};
	if(cook.get('skipthispage')){
		location.replace(setting.page);
	}
	if(!document.cookie){cook.set('temp', 1);}
	if(document.cookie){
		jQuery(function($){
			$('#optout').css({display: ''}).append(setting.optoutHTML).find('input').click(function(){
				this.checked? cook.set('skipthispage', '1', setting.days) : cook.set('skipthispage', '', -1);
				this.checked && setting.gowhenchecked && location.replace(setting.page);
			});
		});
	}
})({
	days: 365, // days cookie will persist
	page: '#', // page to goto if cookie is set
	gowhenchecked: true, // true/false - should page switch when the box is checked?
	optoutHTML: '<label for="optoutcheckbox">Don\'t Show this Splash Page Again: <input type="checkbox" id="optoutcheckbox" value=""></label>'
});
</script>

   
    <script src="http://www.jacklmoore.com/colorbox/jquery.colorbox.js"></script>
 <!--   <script>
      $(document).ready(function(){
        $('#iframe').colorbox({ open: true, innerWidth: "730", innerHeight: "620"});       
      });
    </script>
-->
<?php } ?>


<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>


</head>

<body>
		<div style='display:none'>
			 <a id="iframe" href="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/splash.php">###</a> 
		</div>

<div class="container">

<div class="span-25" id="navigation">

  <!--<ul class="sf-menu">

    <li class="first"><a href="/" title="Rice Design Alliance">Home</a></li>

    <?php /* Old menu functionality. Dropped in favor of WordPress 3.0 menus

    $menu = wp_page_menu('title_li=&link_before=<span>&link_after=</span>&sort_column=menu_order&echo=0');

    $menu = str_replace("<div class=\"menu\"><ul>","",$menu);

    $menu = str_replace("</ul></div>","",$menu);

    echo $menu; 

   */ ?>

  </ul> -->
  
  <?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => false, 'menu_class' => 'sf-menu', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
<div>

    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
      <p>
        <input type="text" id="s" value="" name="s" />
        <input type="image" src="<?php bloginfo('template_url'); ?>/images/go.png" id="searchsubmit" value="Search" />
      </p>
    </form>

</div>  
  
</div>
    
    
<div class="span-25" id="header">

  <div class="span-8">

    <h1><a href="<?php echo get_option('home'); ?>/" title="Rice Design Alliance">

      <?php bloginfo('name'); ?>

      </a></h1>

  </div>

  <div class="span-8" id="description">

    <p><br/><a href="<?php echo get_option('home'); ?>/" title="Rice Design Alliance">Rice Design Alliance</a> seeks to enhance the quality of life in the greater Houston community. </p>

  </div>

  <div class="span-10 last">

	<a href="http://www.youtube.com/watch?v=-jod0HzrdCg"  title="Who We Are" style="float: left;"> <img src="<?php bloginfo('template_url'); ?>/images/watchthevideo.jpg" style="margin-top:12px; clear: none;"></a> 
	<a href="<?php echo get_bloginfo('url'); ?>/rda-box-office" title="RDA Box Office"> <img src="<?php bloginfo('template_url'); ?>/images/boxoffice.png" style="margin-top:12px; padding-left: 12px;" ></a>	
  </div>

</div>

<div class="span-25" id="subnavigation">

  <div class="span-15" id="links">

    <ul>

        <li id="subscribetocite"><a href="https://securews.rice.edu/rda.rice.edu/cite/index.cfm" title="Subscribe To Cite">Subscribe To Cite</a></li>

        <li id="rdatv"><a href="<?php echo get_bloginfo('url'); ?>/rdatv" title="RDAtv">RDAtv</a></li>

        <li id="joinrda"><a href="https://securews.rice.edu/rda.rice.edu/membership/index.cfm" title="Join RDA">Join RDA</a></li>

    </ul>

  </div>

  <div class="span-10 last" id="ads">

    <p>Today's Web Site Brought To You By: <br /><?php echo adrotate_banner('1'); ?></p>

  </div>

</div>

<?php if ( ( is_page() || is_category('16') ) && !is_page('2385') ) { ?><div class="span-25" id="outerwrapper"><?php } ?>

<?php if ( !is_page('2385') ) { ?> <div class="span-25" id="innerwrapper"> <?php } ?>