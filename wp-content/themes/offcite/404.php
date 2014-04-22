<?php get_header(); ?>

    <div id="content">       
            <div class="post page" id="post-<?php the_ID(); ?>" style="border-bottom: none;">
            <h2 class="title"><a href="http://offcite.org/" title="<?php bloginfo( 'name' ); ?> | <?php bloginfo( 'description' ); ?>">404</a></h2>
                <div class="pagecontent">
                <h2 class="noborder">It Looks Like You're Lost</h2>
                <p><img src="<?php bloginfo( 'template_url' ); ?>/images/404.jpg" alt="You're Lost" /></p>
                <p>There doesn't seem to be anything here, but not to worry.  Here are a few friendly suggestions about your next click:</p>
                <ul>
                <li>Try <a href="http://offcite.org/?s=offcite" title="Try the Offcite Search">searching</a> for what you need to find.</li>
                <li>Browse our <a href="http://offcite.org/2008/11" title="November 2008 Archives">archives</a> for quality content, old and new.</li>
                <li>Peruse our <a href="http://offcite.org/category/cite-magazine" title="Cite Magazine Category">categories</a> and gain a better understanding of what we write about at the Offcite blog.</li>
                <li>Visit the <a href="http://offcite.org/feeds" title="Offcite Feeds">Feeds</a> page for access to our content the moment that it's published.</li>
                </ul>
				<p>You are of course always welcome to <a href="http://offcite.org/contact" title="contact the Rice Design Alliance">contact us</a> if you need any assistance.  Happy browsing!</p>
                </div>
                <hr class="space"/>
            </div><!--end page div-->
    </div><!--end content div-->

<div id="footer">
   <ul class="barefooter">
        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>
    </ul>

<?php get_footer(); ?>

</div></div><!--end center div-->
<?php get_sidebar(); ?>