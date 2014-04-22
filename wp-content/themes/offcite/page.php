<?php get_header(); ?>

    <div id="content">       
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="post page" id="post-<?php the_ID(); ?>">  
                <h2 class="title"><?php the_title(); ?></h2>
                <div class="pagecontent">
                <?php the_content(); ?>
                <?php edit_post_link( 'edit', '<p>', '</p>' ); ?>
                </div>
                <hr class="space"/>
            </div><!--end page div-->
            <?php endwhile; ?>
    </div><!--end content div-->

<div id="footer">
<?php
if ( is_page( 'contact' ) ) { ?>
	
     <ul>
        <li id="olderposts"><a href="http://offcite.org/about" title="Contact Offcite">About</a></li>
        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
        <li id="newerposts"><a href="http://offcite.org/feeds" title="Offcite Feeds">Feeds</a></li>
    </ul>

    
    
    
    
<?php } elseif ( is_page( 'about' ) ) { ?>

  <ul>
        <li id="olderposts"><a href="http://offcite.org/contact" title="Contact Offcite">Contact</a></li>
        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
        <li id="newerposts"><a href="http://offcite.org/feeds" title="Offcite Feeds">Feeds</a></li>
  </ul>


<?php } else { ?>

  <ul>
        <li id="olderposts"><a href="http://offcite.org/about" title="About Offcite">About</a></li>
        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
        <li id="newerposts"><a href="http://offcite.org/contact" title="Contact Offcite">Contact</a></li>
  </ul>

<?php } ?>

		<?php else : ?>
        <?php endif; ?>
  
<?php get_footer(); ?>

</div>

</div><!--end center div-->
<?php get_sidebar(); ?>