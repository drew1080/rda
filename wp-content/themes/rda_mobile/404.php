<?php get_header(); ?>

<!--CONTENT-->
 <div id="content">
  
 <div id="posts_container">
  
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  	
   <!--POST-->
    <div class="page_post">
  
  <div class="entry">
  	
	<?php the_content(); ?>
  
  </div>
  
  
  </div>
    <!--// END OF THE POST-->


 <?php endwhile; ?>


 <?php else : ?>
		
  		<div class="page_post">
		
       	    <h2><?php _e('Error 404, no posts found!','ikon'); ?></h2>
	
       	    <p><?php _e('Sorry, no posts matched your criteria.','ikon'); ?></p>
	
		</div>
		
<?php endif; ?>       

</div>
<!--//END OF POSTS CONTAINER-->

  </div>
  <!--//END OF CONTENT-->
    <div class="bt_line_shd">&nbsp;</div>
    <div class="bt_shd">&nbsp;</div>   

<?php get_footer(); ?>