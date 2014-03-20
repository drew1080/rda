<div class="span-5 last" id="sidebarrp">

  <ul>

    <li id="sidebarcalendar">

      <h2><a href="<?php echo get_bloginfo('url'); ?>/category/calendar" title="Calendar">Calendar</a></h2>

      <ul>

        <li>

        <?php

	ec3_get_events(16,5, '<a href="%LINK%"><strong>%DATE%:</strong> &mdash; %TITLE% &mdash; %TIME%</a>','',get_option('date_format') );

	?>

          

        </li>

        <li id="morevents"><a href="<?php echo get_bloginfo('url'); ?>/category/calendar" title="More Events &gt;" class="rda">More Events &gt;</a>
				<a href="<?php echo get_bloginfo('url'); ?>/category/calendar/?ec3_ical" class="rda">Subscribe via iCal</a>
		</li>
      </ul>
      <div class="getengaged">Get Engaged</div>
<div class="socialmedia"><a href="https://twitter.com/RDAHouston"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" alt="Twitter" border="0"></a></div><!-- end socialmedia -->
<div class="socialmedia socialdash"><a href="https://www.facebook.com/pages/The-Rice-Design-Alliance/117888341746"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook.png" alt="Facebook" border="0"></a></div><!-- end socialmedia -->
<div class="socialplugin"><div class="fb-like-box" data-href="https://www.facebook.com/pages/The-Rice-Design-Alliance/117888341746" data-width="215" data-height="300" data-show-faces="false" data-stream="true" data-header="false"></div></div><!-- end socialplugin -->
    </li>

    <li class="extrascentered">

      <h2>LIVE FEED</h2>

      <h3>Images from RDA 2013 Gala</h3>

      <ul>

        <li><img src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/2013/12/antidote-crew.jpg" height="118" width="177" /></li>

        <li><a href="<?php echo get_bloginfo('url'); ?>/2013/2013-gala-live-feed" title="View Slideshow"><img src="<?php echo get_bloginfo('url'); ?>/wp-content/themes/rda/images/viewslideshow.png" alt="View Slideshow" height="18" width="112" /></a></li>

      </ul>

    </li>



    <!--<li id="extras">

      <ul>

        <li><a href="#" title="Image"><img src="<?php bloginfo('template_url'); ?>/images/diy.jpg" alt="DIY" height="156" width="196" /></a>Transparency, Exposing Graphic Design - Ellen Lupton</li>

        <li><a href="#" title="Image"><img src="<?php bloginfo('template_url'); ?>/images/purple.jpg" alt="Purple" height="94" width="197" /></a>Transparency, Exposing Graphic Design - Ellen Lupton</li>

      </ul>

    </li>-->

  </ul>

</div>
   