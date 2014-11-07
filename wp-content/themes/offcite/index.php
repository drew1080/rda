<?php get_header(); ?>

        <div id="content">       
        
    <?php if (have_posts()) : ?>
	
    
	<?php //Prepare to check if this is the topmost Post on the page
      $firstpost = 'true'; ?>

		<?php while (have_posts()) : the_post(); 
		$allpostcontent = $post ->post_content;
		$regex = '#<!--\s*featured\s*-->(.*?)<!--\s*endfeatured\s*-->#s';	
		preg_match_all( $regex, $allpostcontent, $fpics );
		$numpics = count ($fpics[0]); ?>
          
        <?php //Check to see if this is the topmost Post on the page
			if($firstpost == 'true' ) { ?>
            <div class="post firstpost" id="post-<?php the_ID(); ?>">
            <?php $firstpost = 'false'; ?>
        <?php } else { ?>
        	<div class="post" id="post-<?php the_ID(); ?>">  
        <?php } ?>
            
            
        <div class="featuredphoto ram"> 
        <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
          the_post_thumbnail('large');
        ?></div><!--end featuredphoto div-->
        <?php } else if ( $numpics > 0 ) { ?> 
  		  <?php 
        // Check for featured image, if not there, use the old featured comment method
        // This will allow for legacy posts to function properly
        for ( $i=0; $i < $numpics ; $i++ ) { 
          echo $fpics[0][$i]; 
        }; 
    
        ?>
    		</div><!--end featuredphoto div-->
    		<?php } else { ?>
        <div class="nofeatured"></div>
        <?php }; ?>
                    
            <div class="authormeta">
            <ul>
                <li class="authorname"><?php the_author_posts_link(); ?></li>
                <li><?php the_time( 'M. j, Y' ); ?></li>
                <li><?php the_time( 'g:i A' ); ?></li>
            </ul>
            </div><!--end authormeta div-->
            
            <div class="excerpt">
            <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
            <ul class="excerptmeta">
                <?php if ( comments_open() ) : ?>
                <li><strong><?php the_category( ', ' ); ?></strong></li>
                <li>Comments <span><?php comments_popup_link( '0', '1', '%' ); ?></span></li>
                <?php endif; ?>
                <?php if ( !comments_open() ) : ?>
                <li><strong style="border: none;"><?php the_category( ', ' ); ?></strong></li>
                <?php endif; ?>
            </ul>
            
      <?php // Outputting the content, minus the first image in the post
	  // Additionally, only content above the <!--more--> tag will be shown
      $offcitecontent = preg_replace( $regex, '' , $allpostcontent);
	  $pieces = explode( '<!--more-->',$offcitecontent);	  
      $offcitecontent = apply_filters( 'the_content', $pieces[0]); 
      echo $offcitecontent;              
      ?>
			<p><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">more &gt;</a></p>
            <?php edit_post_link( 'edit', '<p>', '</p>' ); ?>
            </div>
            
            <hr class="space"/>
            </div><!--end post div-->
			

		<?php endwhile; ?>
            
</div><!--end content div-->

<?php //prev/nav links
//get the current page number
$page_num = $paged;
if($page_num == '' ){$page_num = '1';}
//get the number of the last page
$max_num_pages = $wp_query->max_num_pages;

//first and only page
if( $page_num == 1 && $max_num_pages==$page_num ){ ?>
<div id="footer">
    <ul>
        <li id="olderposts"><a class="disabled" href="#">Older Posts</a></li>
        <li id="foothome"><a href="<?php echo get_site_url(); ?>" title="Home">Home</a></li>
        <li id="newerposts"><a class="disabled" href="#">Newer Posts</a></li>
    </ul>
<?php get_footer(); ?>
</div>
<?php }

//first of many pages
if( $page_num == 1 && $max_num_pages>$page_num ){ ?>
<div id="footer">
    <ul>
        <li id="olderposts"><?php next_posts_link( 'Older Posts' ); ?></li>
        <li id="foothome"><a href="<?php echo get_site_url(); ?>" title="Home">Home</a></li>
        <li id="newerposts"><a class="disabled" href="#">Newer Posts</a></li>
    </ul>
<?php get_footer(); ?>
</div>
<?php }


//middle page
if( $page_num > 1 && $max_num_pages>$page_num ){ ?>
<div id="footer">
    <ul>
        <li id="olderposts"><?php next_posts_link( 'Older Posts' ); ?></li>
        <li id="foothome"><a href="<?php echo get_site_url(); ?>" title="Home">Home</a></li>
        <li id="newerposts"><?php previous_posts_link( 'Newer Posts' ); ?></li>
    </ul>
<?php get_footer(); ?>
</div>
<?php }

//last page
if( $page_num == $max_num_pages && $max_num_pages > 1 ){ ?>
<div id="footer">
    <ul>
        <li id="olderposts"><a class="disabled" href="#">Older Posts</a></li>
        <li id="foothome"><a href="<?php echo get_site_url(); ?>" title="Home">Home</a></li>
        <li id="newerposts"><?php previous_posts_link( 'Newer Posts' ); ?></li>
    </ul>

<?php get_footer(); ?>

</div>
<?php } ?>


<?php else : ?>

<?php  endif; ?>

</div><!--end center div-->
<?php get_sidebar(); ?>