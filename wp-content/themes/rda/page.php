<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/leftpagesidebar.php'); ?>	
	<div id="contentp" class="span-15">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<?php if( is_page('rdatv') ) { ?>
		<img src="<?php bloginfo('template_url'); ?>/images/rdatv_header.jpg" >
		<hr style="height: 10px; background: #330000;">
		<? } else { ?>
		 <h2><?php the_title(); ?></h2> 
		<? } ?>
		
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>    

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>