<?php get_header(); ?>

<!--CONTENT-->
  <div id="content">
  
  <div id="posts_container" >
  
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  	
 <!--POST-->

  <div class="single_post">
    		
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			
            
            <div class="meta large">
            		<?php _e('by','ikon'); ?> <span class="author"><?php the_author(); ?></span> <span class="date"><?php the_date('M d, Y');?></span> <?php _e('in','ikon'); ?> <span class="category"><?php the_category(', ');?>
				</div>
            
              <div class="entry">
                    
                    <?php the_content(); ?>

    <div class="social-networks">
              
			  <?php if(get_option('ikn_show_socials')=='true') : ?>
              
               <ul class="social_lists_networks">
            	<li id="twitter_social"><a title="Twitter" href="http://twitter.com/home?status=<?php the_permalink(); ?>" >&nbsp;</a></li>
                <li id="facebook_social"><a title="Facebook" href="http://www.facebook.com/share.php?u=<?php the_permalink() ?>" >&nbsp;</a></li>
                <li id="linkedin_social"><a title="LinkedIn" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink();?>&amp;title=<?php the_title();?>&amp;source=LinkedIn">&nbsp;</a></li>
                <li id="delicious_social"><a title="Delicious" href="http://del.icio.us/post?url=<?php the_permalink();?>&amp;title=<?php the_title();?>" >&nbsp;</a></li>
                <li id="digg_social"><a title="Digg" href="http://digg.com/submit?phase=2&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>">&nbsp;</a></li>
                <li id="reddit_social" class="last"><a title="Reddit" href="http://reddit.com/submit?url=<?php the_permalink(); ?>">&nbsp;</a></li>
            </ul>
            <?php endif; ?>
            
            </div>
                    
			</div>                      

  </div>

  <!--// END OF THE POST-->

 
 <?php endwhile; ?>


 <?php else : ?>
		
  		<div class="post">
		
       	    <h2><?php _e('Error 404, no posts found!','ikolos'); ?></h2>
	
       	    <p><?php _e('Sorry, no posts matched your criteria.','ikolos'); ?></p>
	
		</div>
		
<?php endif; ?>         

</div>
<!--//END OF POSTS CONTAINER-->

  </div>
  <!--//END OF CONTENT-->

<div class="bt_line_shd">&nbsp;</div>
<div class="bt_shd">&nbsp;</div>  
  
<?php if(get_option('ikn_author_info')=='true') : ?>                                                   
            
            <?php get_author_info(); ?>

<?php endif; ?>            
    
    <?php if(get_option('ikn_post_comments')=='true') : ?> 
    <div class="comments_btn_holder">           
    	<div class="btn_inner">
       <a href="#post_comments" class="flip single_comments"><span><img src="<?php bloginfo('template_directory'); ?>/images/comments_icon.png" alt="" /><?php $count_comments =  wp_count_comments($post->ID); echo $count_comments->total_comments; ?> <?php _e('Comments', 'ikon' ); ?> </span></a>
       <a href="#post_comments" class="flip single_reply"><span><img src="<?php bloginfo('template_directory'); ?>/images/comment_reply.png" alt="" /> <?php _e('Leave a reply', 'ikon' ); ?> </span></a>
       	</div>
    </div>
    
    <div class="bt_line_shd">&nbsp;</div>
	<div class="bt_shd">&nbsp;</div> 
    
        <?php endif; ?>
        

<?php get_footer(); ?>