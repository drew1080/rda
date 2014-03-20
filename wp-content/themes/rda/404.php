<?php get_header(); ?>
<?php get_sidebar(); ?>        

	<div id="content" class="span-15"><h1 id="bloglogo"><a href="<?php echo get_option('home'); ?>/feeds" title="RDA News &amp; Notes Feeds">RDA News &amp; Notes Feeds</a></h1>
		<div class="post">
			<div class="entry">
            	<h2 class="title">Oops.  It looks like you're lost.</h2>
                <?php if (function_exists('useful404s')) { echo useful404s(); } ?>
			</div>
		</div>
	</div>    

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>