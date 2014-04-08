<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" class="span-15"><h1 id="bloglogo"><a href="<?php echo get_option('home'); ?>/feeds" title="RDA News &amp; Notes">RDA News &amp; Notes</a></h1>

	  <?php if (have_posts()) : ?>

      <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
        <h2 class="singlecattitle"><span>Category:</span> <?php single_cat_title(); ?></h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="singlemonthlytitle">Archive for <span><?php the_time('F Y'); ?></span></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="singleyearlytitle">Archive for <span><?php the_time('Y'); ?></span></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>

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
    <p class="postmetadata alt">
      <?php the_author(); ?> <?php if ( !in_category('calendar') ) { ?>| <?php the_time('g:i a'); ?> | <?php the_time('F j Y'); ?><?php } ?>
    </p>
    <div class="entry">
    
      <?php // Outputting the content, minus the first image in the post
	  	$offcitecontent = preg_replace( $regex, '' , $allpostcontent);
		$pieces = explode('<!--more-->',$offcitecontent);	
		$offcitecontent = apply_filters('the_content', $pieces[0]); 
		echo $offcitecontent; ?>  
      <p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">more &gt;</a></p>
      <?php edit_post_link('EDIT THIS ENTRY','<p>','</p>'); ?>
    </div>
    <hr class="space"/>
  </div>
  
  
  <?php endwhile; ?> 
    <ul id="prevnextlinks">
    <li id="backtotop"><a href="#" title="back to the top">back to the top</a></li>
    <li><?php if (function_exists('wp_pagebar')) wp_pagebar(); ?></li>
    </ul>
  <?php include (TEMPLATEPATH . '/footernav.php'); endif; ?>

</div>

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>