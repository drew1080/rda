<?php get_header(); ?>

<!--CONTENT-->
<div id="content">

<div id="posts_container">
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

    <div class="left_side">
    		
            <?php if((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) : ?>
            
            	    <div class="imgwrapper"> 
            
                    	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('iknpostLoopThumb'); ?></a> 
            
        	        </div>

            <div class="meta float_left">

            		<span class="meta_bg"><?php _e('by','ikon'); ?> <span class="author"><?php the_author(); ?></span></span>
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
                    
                    <?php if(!empty($post->post_excerpt)) : ?>
          
          	<?php the_excerpt(); ?>
            
            <?php else: ?>

				<?php $page_content = apply_filters('the_content', $post->post_content); echo substr($page_content, 0, strpos($page_content, "<!--more-->")); ?>
                
                	<?php if (strpos($post->post_content, '<!--more-->')) : ?>

		<a href="<?php the_permalink(); ?>" class="more-link"><?php _e('Read more','ikon'); ?></a>

                    <?php endif; ?>
                    
             <?php endif; ?>
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