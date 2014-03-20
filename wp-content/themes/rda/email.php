<?php get_header(); ?>
<?php get_sidebar(); ?>

<div id="content" class="span-15"><h1 id="bloglogo"><a href="<?php echo get_option('home'); ?>/feeds" title="RDA News &amp; Notes">RDA News &amp; Notes</a></h1>
  
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  
  <div class="post" id="post-<?php the_ID(); ?>">
    <h2 class="title"> <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
      <?php the_title(); ?>
      </a></h2>
    <div class="entry">
      <?php the_content(); ?>  
    </div>
    <hr class="space"/>
  </div>
 <?php endwhile; endif; ?>
  
</div>

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>