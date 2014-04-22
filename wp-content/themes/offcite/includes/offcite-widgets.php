<?php

//Widgetize Offcite Theme
if ( function_exists( 'register_sidebar' ) )
    register_sidebar(array(
		'name' => 'Left Offcite Sidebar',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

function widget_offcitecategories($args){
	extract($args);
	$offcite_cats = wp_list_categories( 'echo=0&show_count=1&title_li=<h2>Categories</h2>' );
	$offcite_cats = str_replace(array( '( ',' )' ), '', $offcite_cats);
	echo $offcite_cats;
}

wp_register_sidebar_widget( 'offcitecategories','Offcite&rsquo;s Categories','widget_offcitecategories' ); 

function widget_offcitearchives($args) { extract($args); ?>

<li id="archives">
	<h2>Archives</h2>
	<ul>
		<li><a class="toggle" href="#" title="2011">2011</a>
			<ul class="months">
				<li><a href="/2011/01" title="January 2011"><span>January</span></a></li>
				<li><a href="/2011/02" title="February 2011"><span>February</span></a></li>
				<li><a href="/2011/03" title="March 2011"><span>March</span></a></li>
				<li><a href="/2011/04" title="April 2011"><span>April</span></a></li>
				<li><a href="/2011/05" title="May 2011"><span>May</span></a></li>
				<li><a class="/2011/06" href="/2011/06" title="June 2011"><span>June</span></a></li>
				<li><a class="/2011/07" href="/2011/07" title="July 2011"><span>July</span></a></li>
				<li><a class="/2011/08" href="/2011/08" title="August 2011"><span>August</span></a></li>
				<li><a class="/2011/09" href="/2011/09" title="September 2011"><span>September</span></a></li>
				<li><a class="/2011/10" href="/2011/10" title="October 2011"><span>October</span></a></li>
				<li><a class="disabled" href="/2011/11" title="November 2011"><span>November</span></a></li>
				<li><a class="disabled" href="/2011/12" title="December 2011"><span>December</span></a></li>
			</ul>
		</li>
		<li><a class="toggle" href="#" title="2010">2010</a>
			<ul class="months">
				<li><a href="/2010/01" title="January 2010"><span>January</span></a></li>
				<li><a href="/2010/02" title="February 2010"><span>February</span></a></li>
				<li><a href="/2010/03" title="March 2010"><span>March</span></a></li>
				<li><a href="/2010/04" title="April 2010"><span>April</span></a></li>
				<li><a href="/2010/05" title="May 2010"><span>May</span></a></li>
				<li><a href="/2010/06" title="June 2010"><span>June</span></a></li>
				<li><a href="/2010/07" title="July 2010"><span>July</span></a></li>
				<li><a href="/2010/08" title="August 2010"><span>August</span></a></li>
				<li><a href="/2010/09" title="September 2010"><span>September</span></a></li>
				<li><a href="/2010/10" title="October 2010"><span>October</span></a></li>
				<li><a href="/2010/11" title="November 2010"><span>November</span></a></li>
				<li><a href="/2010/12" title="December 2010"><span>December</span></a></li>
			</ul>
		</li>
		<li><a class="toggle" href="#" title="2009">2009</a>
			<ul class="months">
				<li><a href="/2009/01" title="January 2009"><span>January</span></a></li>
				<li><a href="/2009/02" title="February 2009"><span>February</span></a></li>
				<li><a href="/2009/03" title="March 2009"><span>March</span></a></li>
				<li><a href="/2009/04" title="April 2009"><span>April</span></a></li>
				<li><a href="/2009/05" title="May 2009"><span>May</span></a></li>
				<li><a href="/2009/06" title="June 2009"><span>June</span></a></li>
				<li><a href="/2009/07" title="July 2009"><span>July</span></a></li>
				<li><a href="/2009/08" title="August 2009"><span>August</span></a></li>
				<li><a href="/2009/09" title="September 2009"><span>September</span></a></li>
				<li><a href="/2009/10" title="October 2009"><span>October</span></a></li>
				<li><a href="/2009/11" title="November 2009"><span>November</span></a></li>
				<li><a href="/2009/12" title="December 2009"><span>December</span></a></li>
			</ul>
		</li>
		<li><a class="toggle" href="#" title="2008">2008</a>
			<ul class="months">
				<li><a class="disabled" href="/2008/01" title="January 2008"><span>January</span></a></li>
				<li><a class="disabled" href="/2008/02" title="February 2008"><span>February</span></a></li>
				<li><a class="disabled" href="/2008/03" title="March 2008"><span>March</span></a></li>
				<li><a class="disabled" href="/2008/04" title="April 2008"><span>April</span></a></li>
				<li><a class="disabled" href="/2008/05" title="May 2008"><span>May</span></a></li>
				<li><a class="disabled" href="/2008/06" title="June 2008"><span>June</span></a></li>
				<li><a class="disabled" href="/2008/07" title="July 2008"><span>July</span></a></li>
				<li><a class="disabled" href="/2008/08" title="August 2008"><span>August</span></a></li>
				<li><a class="disabled" href="/2008/09" title="September 2008"><span>September</span></a></li>
				<li><a class="disabled" href="/2008/10" title="October 2008"><span>October</span></a></li>
				<li><a class="disabled" href="/2008/11" title="November 2008"><span>November</span></a></li>
				<li><a href="/2008/12" title="December 2008"><span>December</span></a></li>
			</ul>
		</li>
	</ul>
</li>
<?php }

wp_register_sidebar_widget( 'offcitearchives','Offcite&rsquo;s Archives','widget_offcitearchives' ); 

function widget_offcitetags($args) {
	extract($args);
	if ( function_exists( 'wp_tag_cloud' ) ) : ?>
	<li id="popular">
		<h2>Popular Topics</h2>
		<?php wp_tag_cloud( 'smallest=8&largest=12&orderby=count&order=DESC&exclude=21&separator=, ' ); ?>
	</li>
	<?php endif;
}

wp_register_sidebar_widget( 'offcitetags','Offcite&rsquo;s Tags','widget_offcitetags' ); 

function widget_offciterp($args) {
	extract($args); ?>
	<li id="recommended">
		<h2>Recommended Posts</h2>
		<?php 
	$recommendedPosts = new WP_Query();
	$recommendedPosts->query( 'showposts=5&tag=recommended' ); ?>
		<ul class="dotted">
			<?php while ($recommendedPosts->have_posts()) : $recommendedPosts->the_post(); ?>
			<li>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
					</a></p>
			</li>
			<?php endwhile; ?>
		</ul>
	</li><?php
} 

wp_register_sidebar_widget( 'offciterp','Offcite&rsquo;s Recommended Posts','widget_offciterp' );

function widget_offcitenoted($args) {
	extract($args);
	wp_list_bookmarks( 'title_li=&category=27&limit=15' ); 
}

wp_register_sidebar_widget( 'offcitenoted','Offcite&rsquo;s Noted Links','widget_offcitenoted' ); 

function widget_offcitegoodreads($args) {
	extract($args); ?>
	<li id="goodreads">
		<h2>Good Reads</h2>
		<ul class="dottedreads">
			<li><a href="http://www.amazon.com/Ephemeral-City-Cite-Looks-Houston/dp/029270187X/ref=sr_1_1?ie=UTF8&s=books&qid=1228931927&sr=1-1" title="Ephemeral City"><img src="http://offcite.org/wp-content/uploads/2008/12/ephemeral_city_cover.jpg"><span>Ephemeral City: <em>Cite</em> Looks at Houston</span> by <em>Barrie Scardino, Bruce Webb, and William Stern</em></a></li>
		</ul>
	</li>
	<?php
}

wp_register_sidebar_widget( 'offcitegoodreads','Offcite&rsquo;s Good Reads','widget_offcitegoodreads' );