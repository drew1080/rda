<?php get_header(); ?>

    <div id="content">       
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="post page" id="post-<?php the_ID(); ?>">  
                
                <div class="pagecontent">
                <?php the_content(); ?>
                <?php edit_post_link( 'edit', '<p>', '</p>' ); ?>
                </div>
                <hr class="space"/>
            </div><!--end page div-->
            <?php endwhile; ?>
				<?php else : ?>
                <h2 class="center">Not Found</h2>
                <p class="center">Sorry, but you are looking for something that isn't here.</p>
                <?php include (TEMPLATEPATH . "/searchform.php"); ?>
        <?php endif; ?>
    </div><!--end content div-->

<div id="footer">
    <ul class="barefooter">
        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
    </ul>

<?php get_footer(); ?>

</div>

</div><!--end center div-->
<?php get_sidebar(); ?>