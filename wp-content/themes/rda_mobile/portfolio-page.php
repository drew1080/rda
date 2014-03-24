<?php get_header(); ?>

<!--CONTENT-->
<div id="content">

<div id="posts_container" class="portfolios">
<?php

global $post;
	
			$blog_cats = get_post_meta($post->ID, 'ikn_blog_cats', true);
			$excluded_cats = get_post_meta($post->ID, 'ikn_exclude_blog_cats', true);
	
    		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args=array('paged'=>$paged);
			if($blog_cats != '') { $args['cat'] = $blog_cats; } else { $args['cat'] = '0'; }
            if($excluded_cats != '') { $args['category__not_in'] = $excluded_cats; }
            
            query_posts($args);

 if (have_posts()) : while (have_posts()) : the_post(); ?>

  <!--POST-->

   <div class="post">
              
              <div class="post_teaser portfolio">
            
                	    <?php if((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) : ?>
            
            	    <div class="imgwrapper"> 
            
                    	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('iknsinglepostThumb'); ?></a> 
            
        	        </div>
            
		            <?php endif; ?>  
                    
                    
                     <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    
			</div>                      

  </div>
  <!--// END OF THE POST-->

<?php endwhile; ?>


 <?php else : ?>
		
  		<div class="post">
		
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

	<!--PAGINATION-->
       		<?php if(function_exists(wp_pagenavi)) wp_pagenavi();?>
        <!--//END OF PAGINATION-->

<?php get_footer(); ?>