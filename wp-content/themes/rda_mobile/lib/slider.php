<?php if(get_option('ikn_show_slider')=='true' ) : ?>


<?php $slider_items = get_option('ikn_slider_items'); if(!$slider_items) $slider_items = 5;
$slider_con = get_option('ikn_slider_con') ? get_option('ikn_slider_con') : 'Posts'; ?>

<?php if($slider_con == 'Posts') : $slider_posts = new WP_Query('cat='.get_cat_id(get_option('ikn_slider_cat')).'&showposts='.$slider_items); ?>
	
	<?php if ($slider_posts->have_posts()) : ?>

<!--SLIDER-->
<div id="slider_container">
  <div id="slider">
    <ul class="slideshow">
    
    
    <?php while ($slider_posts->have_posts()) : $slider_posts->the_post(); ?>
    
        <?php if (has_post_thumbnail()) : ?>
    
      <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('iknsliderImage', array('alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a></li>
      
      <?php endif; ?>
      
    <?php endwhile; ?>
    
    </ul>
 </div>
 <div id="controller-wrap">
		<div id="controller">
			<a href="#" id="prevBtn">prev</a>
            <a href="#" id="nextBtn">next</a>
		</div>
	</div>
  </div>
<!--//END OF SLIDER--> 
<?php endif; // endif slider Posts ?>	

<?php else : $slider_pages = new WP_Query('post_type=page&meta_key=ikn_featured_page&meta_compare==&meta_value=on&orderby=menu_order&order=ASC'); ?>

<?php if ($slider_pages->have_posts()) : ?>

    <!--SLIDER-->
<div id="slider_container">
  <div id="slider">
    <ul class="slideshow">
    
    <?php while ($slider_pages->have_posts()) : $slider_pages->the_post(); ?>
    <?php if (has_post_thumbnail()) : ?>
      <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('iknsliderImage', array('alt' => ''.get_the_title().'', 'title' => ''.get_the_title().'')); ?></a></li>
      
      <?php endif; ?>
    <?php endwhile; ?>
    
    </ul>
 </div>
 <div id="controller-wrap">
		<div id="controller">
			<img src="<?php bloginfo('template_directory'); ?>/images/scroll-arrow-l.png" id="prevBtn" alt="controller-prev" width="21" height="21" />
	    	<img src="<?php bloginfo('template_directory'); ?>/images/scroll-arrow-r.png" id="nextBtn" alt="controller-next" width="21" height="21" />
		</div>
	</div>
  </div>
<!--//END OF SLIDER--> 

	<?php endif; // endif slider Pages ?>	
    
    <?php endif; //endif slider content ?>
    
<?php endif; //if front page ?>