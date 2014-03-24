<?php get_header(); ?>

<!--CONTENT-->
<div id="content">

<div id="posts_container">
<?php

 if (have_posts()) : while (have_posts()) : the_post(); ?>

<!--POST-->
  <div class="post">

    <div class="left_side">
    		
            <?php if((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) : ?>
            
            	    <div class="imgwrapper"> 
            
                    	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('iknpostLoopThumb'); ?></a> 
            
        	        </div>

            <div class="meta float_left">

            		
			        <span class="meta_bg"><span class="date"><?php the_date('M d, Y');?></span></span>
                    <span class="meta_bg"><?php _e('in','ikon'); ?> <span class="category"><?php the_category(', ');?></span>

            </div>
            
            <?php endif; ?>  
            
    </div>
              
              <div class="post_teaser <?php if(!has_post_thumbnail()) { echo "no_l_margin"; } ?>">
                    
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    
                    <?php if(!has_post_thumbnail()) : ?>
                    
                 <div class="meta large">
            		<?php _e('by','ikon'); ?> <span class="author"><?php the_author(); ?></span> <span class="date"><?php the_date('M d, Y');?></span> <?php _e('in','ikon'); ?> <span class="category"><?php the_category(', ');?>
				</div>
                
                <?php endif; ?>
                    
                    <?php the_excerpt(); ?>
			</div>                      
			
            <div class="btns <?php if(!has_post_thumbnail()) { echo "no_l_margin"; } ?>">
            <span class="post-comments"><a href="<?php the_permalink(); ?>#comments"><span><img class="com_bubble" src="<?php bloginfo('template_directory'); ?>/images/comments_bubble.png" alt="" /> <?php $count_comments =  wp_count_comments($post->ID); echo $count_comments->total_comments; ?></span></a></span>            
            <a class="std-button" href="<?php the_permalink(); ?>"><span><?php _e('Read more','ikon'); ?></span></a>
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