<?php
/*
Template Name: RDA Home Page
*/
?>

<?php get_header(); ?>

<?php 
	//preparation for multiple home page loops
		//Don't Miss Images and Links
		$dontmissPostVars = array('showposts'=>'5','tag'=>'dontmiss','orderby'=>'menu_order');
		//Sticky Posts on Home Page
		//$sticky = get_option( 'sticky_posts' );  //Get all sticky posts
		//array_reverse( $sticky ); //Arrange sticky posts by dates
		//$sticky = array_slice ( $sticky, 0, 2 ); //Get the two newest stickies
		//$stickyPostVars = array( 'post__in' => $sticky, 'category__not_in' => array(16) ); //Sticky posts query
		//Rework sticky posts query to totally ignore them and feature first post
		$stickyPostVars = array(
		'showposts' => 1,
		
		'tag__not_in' => array('128'),
		'category__not_in' => array('16')
		); //Sticky posts query
	//feed preparation into multiple WP_Query's
		$dontmissPost = new WP_Query($dontmissPostVars);
		$dontmissLinks = $dontmissPost;
		//$stickyPost = new WP_Query($stickyPostVars);
		$stickyPost = new WP_Query($stickyPostVars);
?>

<div class="span-25" id="home">
<div class="span-17" id="homecontent">
  
  <div id="hometop">
    <div class="span-11" id="featuredimage">
      <div class="featim">
        <?php while ($dontmissPost->have_posts()) : $dontmissPost->the_post();?>
        <?php $key="wppt_preset1"; $metatest = get_post_meta($post->ID, $key, true); if ($metatest == "") { ?>
        <img src="<?php bloginfo('template_url'); ?>/images/home_featured_image.jpg" alt="<?php the_title(); ?>" height="250" width="370" class="feat" />
        <?php } else { ?>
        <img src="<?php echo get_post_meta($post->ID, $key, true); ?>" alt="<?php the_title(); ?>" height="250" width="370" class="feat" />
        <?php } ?>
        <?php endwhile; ?>
      </div>
      <img src="<?php bloginfo('template_url'); ?>/images/arrow.png" alt="Don't Miss" height="12" width="9" id="arrow" /> </div>
    <div class="span-6 last" id="dontmiss">
      <ul>
        <li>
          <h2>Don't Miss&hellip;</h2>
          <ul>
            <?php while ($dontmissLinks->have_posts()) : $dontmissLinks->the_post();?>
            <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
              <?php the_title(); ?>
              <span>&gt;</span></a></li>
            <?php endwhile; ?>
          </ul>
        </li>
      </ul>
    </div>
    </div>
  
    <div class="span-17" id="primaryexcerpt">
      <h2><a name="rdanewsnotes" id="newsnotesa" title="RDA News &amp; Notes">RDA News &amp; Notes</a></h2>
      
      	<?php 
		//if (get_option('sticky_posts')) {
		while ($stickyPost->have_posts()) : $stickyPost->the_post();?>
        <div class="pepadding">
        <?php $key="wppt_preset2"; $metatest = get_post_meta($post->ID, $key, true); if ($metatest == "") { ?>
        <p><img src="<?php bloginfo('template_url'); ?>/images/home_second_featured_image.jpg" alt="<?php the_title(); ?>" height="170" width="230" class="left" /></p>
        <?php } else { ?>
        <p><img src="<?php echo get_post_meta($post->ID, $key, true); ?>" alt="<?php the_title(); ?>" height="170" width="230" class="left" /></p>
        <?php } ?>
        <p class="meta"><?php the_time('F j Y'); ?> | <?php the_time('g:i a'); ?> <?php if (comments_open()){ ?> | <a href="<?php comments_link(); ?>" title="View comments for <?php the_title(); ?>" rel="nofollow" class="commentlink">Comments <?php comments_number('0','1','%'); ?></a><?php } ?></p>
        <h3><?php the_title(); ?></h3>
	   
        <?php the_excerpt(); ?>

	   
	   
        
	   
	   
	   <p class="readmorelink"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">read more <span>&gt;</span></a></p>
        <p><em>filed under:</em> <?php the_category(', '); ?></p>
        </div>
        <?php $spcount = 1; ?>
        <?php endwhile; //} ?>
  
    </div>
    
    <div class="span-17" id="modules">
        
    <?php //Tiny post modules at bottom of home page
	$blogPosts = new WP_Query();
	$blogPosts->query(array(
		'showposts' => 4,
		'offset' => 1,
		'caller_get_posts' => 1,
		'tag__not_in' => array('128'),
		'category__not_in' => array('16')
		));
	?>
	<?php while ($blogPosts->have_posts()) : $blogPosts->the_post(); ?>
    
      <div class="span-8 module">
        <div class="modwrap">
            <p class="meta"><?php the_time('F j Y'); ?> | <?php the_time('g:i a'); ?> <?php if (comments_open()){ ?> | <a href="<?php comments_link(); ?>" title="View comments for <?php the_title(); ?>" rel="nofollow" class="commentlink">Comments <?php comments_number('0','1','%'); ?></a><?php } ?></p>
            <h4><?php the_title(); ?></h4>
            <?php $key="wppt_preset3"; $metatest = get_post_meta($post->ID, $key, true); if ($metatest == "") { ?>
            <p><img src="<?php bloginfo('template_url'); ?>/images/thumbnail.jpg" alt="<?php the_title(); ?>" height="66" width="66" class="left" /></p>
            <?php } else { ?>
            <p><img src="<?php echo get_post_meta($post->ID, $key, true); ?>" alt="<?php the_title(); ?>" height="66" width="66" class="left" /></p>
                
			 
	<?php }  the_excerpt(); ?>
			 
                
             
            <p class="readmorelink"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">read more <span>&gt;</span></a></p>
            <p><em>filed under:</em> <?php the_category(', '); ?></p>
        </div>
      </div>
      
	<?php endwhile; ?>
    </div>
    
  </div>
  

<?php include (TEMPLATEPATH . '/homesidebar.php'); ?>
</div>
<?php get_footer(); ?>
