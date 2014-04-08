<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" class="span-15"><h1 id="bloglogo"><a href="<?php echo get_option('home'); ?>/feeds" title="RDA News &amp; Notes">RDA News &amp; Notes</a></h1>
  
  <?php if (have_posts()) : ?> 
  <?php $firstpost = 'true'; //Prepare to check if this is the topmost Post on the page ?>
  <?php while (have_posts()) : the_post();
  		$allpostcontent = $post ->post_content;
		$regex = '#<!--\s*featured\s*-->(.*?)<!--\s*endfeatured\s*-->#s';
		preg_match_all( $regex, $allpostcontent, $fpics );
		$numpics = count ($fpics[0]);
	    if ( $numpics > 0 ) { ?>
        <div class="featuredphoto"><?php for ( $i=0; $i < $numpics ; $i++ ) { echo $fpics[0][$i]; }; ?></div>
  <?php } else { ?>
        <div class="nofeatured"></div>
  <?php }; ?>
  
  <div class="post" id="post-<?php the_ID(); ?>">
  
    <h2 class="title"> <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
      <?php the_title(); ?>
      </a></h2>
      <br/>
      
    <p class="postmetadata alt">
      <?php the_author(); ?> <?php if ( !in_category('calendar') ) { ?>| <?php the_time('g:i a'); ?> | <?php the_time('F j Y'); ?><?php } ?>
    </p>
   
    
      <?php // Outputting the content, minus the first image in the post
	  	$offcitecontent = preg_replace( $regex, '' , $allpostcontent);
		$offcitecontent = apply_filters('the_content', $offcitecontent);
		echo $offcitecontent; ?>  
      <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
      <?php edit_post_link('EDIT THIS ENTRY','<p>','</p>'); ?>
      <p class="thetags">
          <?php if(function_exists('wp_email')) { email_link(); } ?>
          Filed Under: <?php the_category(', '); ?>, <span class="lowercase"><?php the_tags('',', '); ?></span>
      </p>
      <?php if (function_exists('sociable_html')) { echo sociable_html(); } ?>
      <?php comments_template(); ?>
    </div>
    <hr class="space"/>
  
  
  
  <?php endwhile; endif; ?>
  
</div>

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>