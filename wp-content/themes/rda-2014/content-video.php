<?php
/**
 * The template for displaying posts in the Video post format.
 *
 * @package WordPress
 * @subpackage Fruitful theme
 * @since Fruitful theme 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog_post'); ?>>
	
	<?php 
      // RDA CUSTOM CODE BY HIOWEB April 21st, 2014: Hide date if it's not an event
      $post_classes = get_post_class('blog_post');
      $display_post_date = false;
      if ( in_array("tribe_events", $post_classes) ) {
        //$day 		 = get_the_date('d'); 
  		  //$month_abr = get_the_date('M');
        $day = tribe_get_start_date($post->ID, false, 'd');
        $month_abr = tribe_get_start_date($post->ID, false, 'M');
        $display_post_date = true;
      }
	?>
  
	<div class="date_of_post">
		<span class="day_post"><?php print $display_post_date ? $day : ""; ?></span>
		<span class="month_post"><?php print $display_post_date ? $month_abr : ""; ?></span>
	</div>
	
	<div class="post-content">	
		<header class="post-header">
			<?php if ( is_single() ) : ?>
				<h1 class="post-title"><?php the_title(); ?></h1>
			<?php else : ?>
				<h1 class="post-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
			<?php endif; // is_single() ?>		
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'fruitful' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'fruitful' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php fruitful_entry_meta(); ?>

			<?php if ( comments_open() && ! is_single() ) : ?>
			<span class="comments-link">
				<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'fruitful' ) . '</span>', __( 'One comment so far', 'fruitful' ), __( 'View all % comments', 'fruitful' ) ); ?>
			</span><!-- .comments-link -->
			<?php endif; // comments_open() ?>

			<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>
			<?php edit_post_link( __( 'Edit', 'fruitful' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</div>
</article><!-- #post -->
