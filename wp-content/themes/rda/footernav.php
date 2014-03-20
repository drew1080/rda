<?php //prev/nav links
//get the current page number
$page_num = $paged;
if($page_num == ''){$page_num = '1';}
//get the number of the last page
$max_num_pages = $wp_query->max_num_pages;

//first and only page
if( $page_num == 1 && $max_num_pages==$page_num ){ ?>
<div id="footern">
    <ul>
        <li id="olderposts"><a class="disabled" href="#">Older Posts</a></li>
        <li id="foothome"><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
        <li id="newerposts"><a class="disabled" href="#">Newer Posts</a></li>
    </ul>
</div>
<?php }

//first of many pages
if( $page_num == 1 && $max_num_pages>$page_num ){ ?>
<div id="footern">
    <ul>
        <li id="olderposts"><?php next_posts_link('Older Posts') ?></li>
        <li id="foothome"><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
        <li id="newerposts"><a class="disabled" href="#">Newer Posts</a></li>
    </ul>
</div>
<?php }


//middle page
if( $page_num > 1 && $max_num_pages>$page_num ){ ?>
<div id="footern">
    <ul>
        <li id="olderposts"><?php next_posts_link('Older Posts') ?></li>
        <li id="foothome"><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
        <li id="newerposts"><?php previous_posts_link('Newer Posts') ?></li>
    </ul>
</div>
<?php }

//last page
if( $page_num == $max_num_pages && $max_num_pages > 1 ){ ?>
<div id="footern">
    <ul>
        <li id="olderposts"><a class="disabled" href="#">Older Posts</a></li>
        <li id="foothome"><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
        <li id="newerposts"><?php previous_posts_link('Newer Posts') ?></li>
    </ul>
</div>
<?php } ?>

<form method="get" id="searchformf" action="<?php bloginfo('url'); ?>/">
<p><input type="text" value="" name="sf" id="sf" />
<input type="image" src="<?php bloginfo('template_url'); ?>/images/footer_search.png" id="searchsubmitf" value="Go" /></p>
</form>