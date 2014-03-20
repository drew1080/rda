<?php 
//	WP3 MENUS

add_action( 'init', 'register_iwbc_menu' );
	function register_iwbc_menu() {
	register_nav_menu( 'ikn_mobile_menu', __( 'iKon Menu' ) );
}
// Register sidebar
if ( function_exists('register_sidebar') )
    register_sidebar(array('name' => 'iKon widgets section',
						   'id' => 'ikon-sidebar',
						   'before_widget' => '<div id="%1$s" class="widget-section-widget %2$s">',
						   'after_widget' => '</div><!-- end module -->',
						   'before_title' => '<h3 class="trigger"><a href="#"><span>',
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

//WIDGETS 
//============================ ikon Twitter Widget ===============================
class iknm_Twitter extends WP_Widget {
 
	function iknm_Twitter() {
        $widget_ops = array('classname' => 'widget-twitter', 'description' => __('Display latest tweets','ikon') );
		$this->WP_Widget('ikn_twitter', __('iKon - Twitter','ikon'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('Latest Tweets','ikon') : $instance['title'];
        $user	= $instance['user'];
        $twit_link	= $instance['twitter_link'] ? '1' : '0';
        $label	= empty($instance['twitter_label']) ? __('More twitts','ikon') : $instance['twitter_label'];
        if ( !$show = (int) $instance['twitter_nr'] )
			$show = 3;
		else if ( $show < 1 )
			$show = 1;
		else if ( $show > 15 )
			$show = 15;
 
        ?>
			<?php echo $before_widget; ?>
				<h3 class="trigger twitter_widget"><a href="#"><span><?php echo $title; ?></span></a></h3>
				
				<div class="twitter_container"><div class="twitter_lightbox">
    				<ul id="twitter_update_list"><li></li></ul>
                  
                <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
    			<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $user; ?>.json?callback=twitterCallback2&amp;count=<?php echo $show; ?>"></script>
                  
                <?php if($twit_link) : ?>
				<a class="link-to-twitter" href="http://twitter.com/<?php echo $user; ?>"><?php echo $label; ?></a>
                <?php endif; ?>
 </div></div>
			<?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['user'] = strip_tags($new_instance['user']);
    	$instance['twitter_link'] = $new_instance['twitter_link'] ? 1 : 0;
    	$instance['twitter_label'] = strip_tags($new_instance['twitter_label']);
    	$instance['twitter_nr'] = (int) $new_instance['twitter_nr'];
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'user' => '', 'twitter_link' => '', 'twitter_label' => '', 'twitter_nr' => '') );
		$title 		= strip_tags($instance['title']);
		$user		= strip_tags($instance['user']);
		$twit_link 		= (bool) $instance['twitter_link'];
		$label 		= strip_tags($instance['twitter_label']);
		if (!$show = (int) $instance['twitter_nr']) $show = 3;
?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('twitter_nr'); ?>"><?php _e('Tweets to show','ikon'); ?>:</label>
			<input id="<?php echo $this->get_field_id('twitter_nr'); ?>" name="<?php echo $this->get_field_name('twitter_nr'); ?>" type="text" value="<?php echo $show; ?>" size="3" /><br />
			<small><?php _e('(at most 15)'); ?></small>
		</p>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('twitter_link'); ?>" name="<?php echo $this->get_field_name('twitter_link'); ?>"<?php checked( $twit_link ); ?> />
			<label for="<?php echo $this->get_field_id('twitter_link'); ?>"><?php _e('Show link to Twitter','ikon'); ?></label>		
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('twitter_label'); ?>"><?php _e('Link label','ikon'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('twitter_label'); ?>" name="<?php echo $this->get_field_name('twitter_label'); ?>" type="text" value="<?php echo esc_attr($label); ?>" />
			</label>
		</p>
		
<?php
	}

}
 
// Register widget
  function iknm_TwitterInit() { register_widget('iknm_Twitter'); }
  add_action('widgets_init', 'iknm_TwitterInit');
  
  
//============================ ikon Facebook Fab Box Widget ===============================
class iknm_Ffanbox extends WP_Widget {
 
	function iknm_Ffanbox() {
        $widget_ops = array('classname' => 'widget-twitter', 'description' => __('Display Facebook Fan Box','ikon') );
		$this->WP_Widget('ikn_ffanbox', __('Facebook Like Box (iKon)','ikon'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('Facebook Fan Box','ikon') : apply_filters('widget_title', $instance['title']);
		$fpage_link = $instance['user_fpage_link'];        
		$show_faces	= $instance['f_show_faces'] ? 'true' : 'false';
		$stream	= $instance['f_stream'] ? 'true' : 'false';
		$header	= $instance['f_header'] ? 'true' : 'false';
		$f_width = $instance['ffbox_width'];
		$f_height = $instance['ffbox_height'];
        
 		
			
        ?>
        
        <h3 class="trigger facebook_widget"><a href="#"><span><?php echo $title; ?></span></a></h3>
			<?php echo $before_widget; ?>

				<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" data-href="<?php echo $fpage_link; ?>" data-width="<?php echo $f_width; ?>" data-height="<?php echo $f_height; ?>" data-colorscheme="light" data-show-faces="<?php echo $show_faces; ?>" data-stream="<?php echo $stream; ?>" data-header="<?php echo $header; ?>"></div>


			<?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);    	
		$instance['user_fpage_link'] = strip_tags($new_instance['user_fpage_link']);
    	$instance['f_show_faces'] = $new_instance['f_show_faces'] ? true : false;
		$instance['f_stream'] = $new_instance['f_stream'] ? true : false;
		$instance['f_header'] = $new_instance['f_header'] ? true : false;		
    	$instance['ffbox_width'] = (int) $new_instance['ffbox_width'];
		$instance['ffbox_height'] = (int) $new_instance['ffbox_height'];
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'user_fpage_link'=> '', 'ffbox_width' => '', 'ffbox_height' => '', 'f_header' => '', 'f_stream' => '', 'f_show_faces' => '') );
		$title 		= strip_tags($instance['title']);
		$fpage_link = strip_tags($instance['user_fpage_link']);
		$show_faces	= (bool) $instance['f_show_faces'];
		$stream	= (bool) $instance['f_stream'];
		$header	= (bool) $instance['f_header'];
		if (!$f_width = (int) $instance['ffbox_width']) $f_width = 292;
		if (!$f_height = (int) $instance['ffbox_height']) $f_height = '';
				
?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user_fpage_link'); ?>"><?php _e('Facebook Page URL','ikon'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('user_fpage_link'); ?>" name="<?php echo $this->get_field_name('user_fpage_link'); ?>" type="text" value="<?php echo esc_attr($fpage_link); ?>" /><br />
			</label>
            <small><?php _e('(The URL of the Facebook Page for this like box.)'); ?></small>
		</p>
        
		
		<p>
			<label for="<?php echo $this->get_field_id('ffbox_width'); ?>"><?php _e('Width','ikon'); ?>:</label>
			<input id="<?php echo $this->get_field_id('ffbox_width'); ?>" name="<?php echo $this->get_field_name('ffbox_width'); ?>" type="text" value="<?php echo $f_width; ?>" size="3" /><br />
			<small><?php _e('(The width of the plugin in pixels.)'); ?></small>
		</p>
        
        <p>
			<label for="<?php echo $this->get_field_id('ffbox_height'); ?>"><?php _e('Height','ikon'); ?>:</label>
			<input id="<?php echo $this->get_field_id('ffbox_height'); ?>" name="<?php echo $this->get_field_name('ffbox_height'); ?>" type="text" value="<?php echo $f_height; ?>" size="3" /><br />
			<small><?php _e('(The height of the plugin in pixels (optional).)'); ?></small>
		</p>
        
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('f_show_faces'); ?>" name="<?php echo $this->get_field_name('f_show_faces'); ?>"<?php checked( $show_faces ); ?> />
			<label for="<?php echo $this->get_field_id('f_show_faces'); ?>"><?php _e('Show Faces','ikon'); ?></label>
            <small><?php _e('(Show profile photos in the plugin.)'); ?></small>		
		</p>
        
        <p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('f_stream'); ?>" name="<?php echo $this->get_field_name('f_stream'); ?>"<?php checked( $stream ); ?> />
			<label for="<?php echo $this->get_field_id('f_stream'); ?>"><?php _e('Stream','ikon'); ?></label>
            <small><?php _e('(Show the profile stream for the public profile.)'); ?></small>		
		</p>
        
        <p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('f_header'); ?>" name="<?php echo $this->get_field_name('f_header'); ?>"<?php checked( $header ); ?> />
			<label for="<?php echo $this->get_field_id('f_header'); ?>"><?php _e('Header','ikon'); ?></label>
            <small><?php _e('(Show the "Find un on Facebook" bar at top. Only shown when either stream of faces are present.)'); ?></small>		
		</p>
		
		
<?php
	}

}
 
// Register widget
  function iknm_FfanboxInit() { register_widget('iknm_Ffanbox'); }
  add_action('widgets_init', 'iknm_FfanboxInit');  

// =============================== Flickr widget ======================================

class iknm_Flickr extends WP_Widget {
 
	function iknm_Flickr() {
        $widget_ops = array('classname' => 'widget-flickr', 'description' => __('Display flickr badge','ikon') );
		$this->WP_Widget('ikn_flickr', __('Flickr Stream (iKon)','ikon'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('<strong style="color:#3993ff">Flick<span style="color:#ff1c92">r</span></strong> photos','ikon') : apply_filters('widget_title', $instance['title']);
        $user	= empty($instance['user']) ? get_option('dn_flickr_id') : $instance['user'];
        
        if ( !$count_items = (int) $instance['flickr_nr'] )
			$count_items = 6;

		else if ( $count_items < 1 )

			$count_items = 3;

		else if ( $count_items > 15 )
			$count_items = 15;
 
        ?>
			<?php echo $before_widget; ?>
            
				<h3 class="trigger flickr_widget"><a href="#"><span><?php echo $title; ?></span></a></h3>
				
    			<div class="flickr_lightbox">
    			
                <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $count_items; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $user; ?>"></script>
                
    			</div><!-- end module -->
 
			<?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['user'] = strip_tags($new_instance['user']);
    	$instance['flickr_nr'] = (int) $new_instance['flickr_nr'];
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'user' => '', 'flickr_nr' => '') );
		$title = strip_tags($instance['title']);
		$user = empty($instance['user']) ? get_option('dn_flickr_id') : $instance['user'];
		if (!$count_items = (int) $instance['flickr_nr']) $count_items = 6;
?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','ikon'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com">idGettr</a>)','ikon'); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('flickr_nr'); ?>"><?php _e('Photos to show','ikon'); ?>:</label>
			<input id="<?php echo $this->get_field_id('flickr_nr'); ?>" name="<?php echo $this->get_field_name('flickr_nr'); ?>" type="text" value="<?php echo $count_items; ?>" size="3" /><br />
			<small><?php _e('(at most 15)','ikon'); ?></small>
		</p>
		
<?php
	}

}
// Register widget
  function iknm_FlickrInit() { register_widget('iknm_Flickr'); }
  add_action('widgets_init', 'iknm_FlickrInit');

?>