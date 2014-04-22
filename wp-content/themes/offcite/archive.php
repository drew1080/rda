<?php get_header(); ?>

      <div id="content">
        
      <?php if (have_posts()) : ?>
        
      <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="singlecatheader">Category Results</h2>
        <h3 class="singlecattitle"><span>Category:</span> <?php single_cat_title(); ?></h3>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time( 'F jS Y' ); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="singlemonthlytitle">Archive for <span><?php the_time( 'F Y' ); ?></span></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="singleyearlytitle">Archive for <span><?php the_time( 'Y' ); ?></span></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>
        
    
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
            
            
        <?php if ( $numpics > 0 ) { ?> 
        <div class="featuredphoto"> 
		<?php for ( $i=0; $i < $numpics ; $i++ ) { echo $fpics[0][$i]; }; ?>
		</div><!--end featuredphoto div-->
		<?php } else { ?>
        <div class="nofeatured"></div>
        <?php }; ?>
                    
            <div class="authormeta">
            <ul>
                <li class="authorname"><?php the_author(); ?></li>
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
    
    <ul id="prevnextlinks">
    <li id="backtotop"><a href="#" title="back to the top">back to the top</a></li>
    <li><?php if (function_exists( 'wp_pagebar' )) wp_pagebar(); ?></li>
    </ul>
    
    
    
    
    
    
    
    <?php else : ?>

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