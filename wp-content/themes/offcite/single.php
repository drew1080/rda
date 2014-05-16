<?php get_header(); ?>



        <div id="content">       

        

    <?php if (have_posts()) : 
	
	if ( has_category( 'unexpected-city' ) ) : ?>
		<img src="http://offcite.org/wp-content/themes/offcite/images/unexpected-city-badge.png" class="unexpected-cities-badge" height="26" width="187" />
	<?php endif;
	
	
	 //Prepare to check if this is the topmost Post on the page

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

            

            

        <div class="featuredphoto"> 
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

                <li class="authorname"><a href="http://offcite.org/about" title="About OffCite"><?php the_author_posts_link(); ?></a></li>

                <li><?php the_time( 'M. j, Y' ); ?></li>

                <li><?php the_time( 'g:i A' ); ?></li>

            </ul>

            </div><!--end authormeta div-->

            

            <div class="excerpt entry">

            <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

            

            <ul class="excerptmeta">

            	 <?php if ( comments_open() ) : ?>

                <li><strong><?php the_category( ', ' ); ?></strong></li>

                <li>Comments <span><a href="#respond" title="Write Your Response"><?php comments_number( '0', '1', '%' ); ?></a></span></li>		<?php endif; ?>

                <?php if ( !comments_open() ) : ?>

                <li><strong style="border: none;"><?php the_category( ', ' ); ?></strong></li>

                <?php endif; ?>                

            </ul>

            

            

            

      <?php // Outputting the content, minus the first image in the post

	  // Additionally, only content above the <!--more--> tag will be shown

      $offcitecontent = preg_replace( $regex, '' , $allpostcontent);

      $offcitecontent = apply_filters( 'the_content', $offcitecontent); 

      echo $offcitecontent;
	  
	  wp_link_pages();          

      edit_post_link( 'edit', '<p>', '</p>' )

	  //tags ?>

      

      <p class="thetags"><?php if(function_exists( 'wp_email' )) { email_link(); } ?> <?php the_tags( 'Filed Under: ',', ' ); ?></p>

      

      <?php 

      //sociable links

	  if (function_exists( 'sociable_html' )) { echo sociable_html(); } ?>

            <?php comments_template(); ?>

            </div>

            

            <hr class="space"/>

            </div><!--end post div-->

			



		<?php endwhile; ?>

            

</div><!--end content div-->



<div id="footer">

    <ul>

        <?php previous_post_link( '<li id="olderposts">%link</li>', 'older posts' ); ?>

        <li id="foothome"><a href="http://offcite.org/" title="Home">Home</a></li>

        <?php next_post_link( '<li id="newerposts">%link</li>', 'newer posts' ); ?>

    </ul>

    

<?php get_footer(); ?>



</div>



	<?php else : ?>

	<?php endif; ?>



</div><!--end center div-->

<?php get_sidebar(); ?>