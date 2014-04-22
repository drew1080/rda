<?php get_header(); ?>

<div id="content">

	<?php while (have_posts()) : the_post(); ?>
	
	<div class="post page" id="post-<?php the_ID(); ?>">
		<h2 class="title">
			<?php the_title(); ?>
		</h2>
		<div class="pagecontent">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
			<?php edit_post_link( 'edit', '<p>', '</p>' ); ?>
		</div>
		<hr class="space"/>
	</div><!--end page div-->
	
	<?php endwhile; ?>
	
	<ul id="slider-nav">
	</ul>
	<?php $slider_posts_query = new WP_Query("showposts=5"); ?>
	<div id="slider-posts-wrapper">
		<div id="slider-posts">
			<?php if ( $slider_posts_query->have_posts() ): while ( $slider_posts_query->have_posts() ) : $slider_posts_query->the_post(); ?>
			<div class="slide">
				<div class="slide-thumbnail">
					<?php the_post_thumbnail('uc-featured'); ?>
				</div>
				<div class="slide-details">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( 'Permanent Link to %s', the_title_attribute( 'echo=0' ) ); ?>">
						<?php the_title(); ?>
						</a>
					</h2>
				</div>
				<div class="clear"></div>
			</div>
			<?php endwhile; endif; wp_reset_query(); ?>
		</div>
	</div>
	</div>
	
</div>
<!--end content div-->

<div id="footer">
	<ul>
		<li id="olderposts"><a href="http://offcite.org/about" title="About Offcite">About</a></li>
		<li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
		<li id="newerposts"><a href="http://offcite.org/contact" title="Contact Offcite">Contact</a></li>
	</ul>
	<?php get_footer(); ?>
</div>
</div>
<!--end center div-->
<?php get_sidebar(); ?>