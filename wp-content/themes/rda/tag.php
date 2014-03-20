<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" class="span-15"><h1 id="bloglogo"><a href="<?php echo get_option('home'); ?>/feeds" title="RDA News &amp; Notes">RDA News &amp; Notes</a></h1>
  
  <?php if (have_posts()) : ?> 
<h2 class="singlesearchheader">Tag Results</h2>
	
<div class="post searchresults" id="post-<?php the_ID(); ?>">

<table width="500" border="0" cellspacing="0" cellpadding="0" id="searchtable">
<thead>
  <tr>
    <th class="query">All Offcite Posts Tagged: <span><?php single_cat_title(); ?></span></th>
    <th class="dateposted">Date Posted</th>
    <th class="categories">Categories</th>
  </tr>
</thead>
<tbody>
  <?php while (have_posts()) : the_post(); ?>
  
    <tr>
    <td class="first"><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt();?></td>
    <td class="second"><?php the_time('m.d.y') ?></td>
    <td class="third"><?php the_category('<br />'); ?></td>
  </tr>

  
  <?php endwhile; ?> 
  
  </tbody>
</table>
 <ul id="prevnextsearch">
    <li id="backtotop"><a href="#" title="back to the top">back to the top</a></li>
    <li><?php if (function_exists('wp_pagebar')) wp_pagebar(); ?></li>
    </ul>
</div>
  
  
  
  
  <?php endif; ?>
  
</div>

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>