<?php get_header(); ?>

<!--CONTENT-->
  <div id="content">
  
  <div id="posts_container">
  
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  	
   <!--POST-->
    <div class="page_post">
  
<?php 
	$content = get_the_content();

		if($content) : ?>
        
          <!--ENTRY-->
          <div class="entry">
            
            <?php the_content(); ?>
            
             <?php edit_post_link(__('Edit Page','ikon'), '<p class="clear">', '</p>'); ?>
            
          </div>
          <!--//END OF ENTRY-->
		
        <?php endif; ?>


 <!--POSTS CONTAINER-->
          <div id="posts-container" class="portfolio-5col">

<?php          
if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	$orderby    = 'menu_order ID';
 ?>
          
<?php $attachments = get_children( array('post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => $orderby)); ?>
            
            <?php  $i=0; foreach ($attachments as $attachment_id => $attachment) :

 $src = wp_get_attachment_image_src($attachment_id, 'full');
 $img_title = $attachment->post_title;

 $i++;
  ?>
            <!--POST-->
           
              <div class="img-wrapper"> <div class="hover_fade zoom">

              <a rel="prettyPhoto[]" title="<?php echo $img_title; ?>" href="<?php echo $src[0]; ?>"><?php echo wp_get_attachment_image($attachment_id, 'galleryImages', array('alt' => ''.$img_title.'','title' => ''.$img_title.'')); ?></a> 

              </div>

              </div>
            
            <!--//END OF POST-->

<?php endforeach; ?>
  
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

<?php get_footer(); ?>