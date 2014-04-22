<?php
/**
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
	<?php if (get_the_title() == '') : ?>
		<a href="<?php the_permalink(); ?>" rel="bookmark">
	<?php endif; ?>	
	
	<div class="date_of_post">
		<span class="day_post"><?php print $display_post_date ? $day : ""; ?></span>
		<span class="month_post"><?php print $display_post_date ? $month_abr : ""; ?></span>
	</div>
	<?php if (get_the_title() == '') : ?>
		</a>
	<?php endif; ?>
	
	<div class="post-content">	
	<header class="post-header">
		<?php if ( is_single() ) : ?>
				<h1 class="post-title"><?php the_title(); ?></h1>
		<?php else : ?>
			<?php if (get_the_title() != '') : ?>
			<h1 class="post-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'fruitful' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
			<?php endif; ?>
		<?php endif; // is_single() ?>		
		
		
		<?php if ( !is_single() ) : ?>
			<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
				<div class="entry-thumbnail">
					<?php the_post_thumbnail(); ?>
				</div>
			<?php endif; ?>
		<?php endif; // is_single() ?>
	</header><!-- .entry-header -->

	<?php if ( (is_search())) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Read More <span class="meta-nav">&rarr;</span>', 'fruitful' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'fruitful' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php fruitful_entry_meta(); ?>
		
		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'fruitful' ), __( '1 Comment', 'fruitful' ), __( '% Comments', 'fruitful' ) ); ?></span>
		<?php } ?>
		
		<?php edit_post_link( __( 'Edit', 'fruitful' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
