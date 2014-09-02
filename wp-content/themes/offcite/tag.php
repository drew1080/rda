<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>


<h2 class="singlesearchheader">Tag Results</h2>
	
<div class="post searchresults" id="post-<?php the_ID(); ?>">

<table width="540" border="0" cellspacing="0" cellpadding="0" id="searchtable">
<thead>
  <tr>
    <th class="query">All Offcite Posts Tagged: <span><?php single_cat_title(); ?></span></th>
    <th class="dateposted">Date Posted</th>
    <th class="categories">Categories</th>
  </tr>
</thead>
<tbody>

		<?php while (have_posts()) : the_post(); ?>

			
				
                
                

  <tr>
    <td class="first"><h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt();?></td>
    <td class="second"><?php the_time( 'm.d.y' ); ?></td>
    <td class="third"><?php the_category( '<br />' ); ?></td>
  </tr>


		<?php endwhile; ?>
</tbody>
</table>
 <ul id="prevnextsearch">
    <li id="backtotop"><a href="#" title="back to the top">back to the top</a></li>
    <li>
    <?php
    global $wp_query;

    $big = 999999999; // need an unlikely integer
    echo "<div class='pagebar-custom'><span>Pages: </span>";
    echo paginate_links( array(
    	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    	'format' => '?paged=%#%',
    	'current' => max( 1, get_query_var('paged') ),
    	'total' => $wp_query->max_num_pages,
      'prev_text'    => __('Prev'),
      'next_text'    => __('Next')
    ) );
    echo "</div>";
      
    ?>
    </li>
    </ul>
    
</div>

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