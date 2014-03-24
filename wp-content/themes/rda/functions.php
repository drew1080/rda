<?php //rice design alliance functions


 if( !is_admin()){
 
wp_deregister_script('jquery');
wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"), false, '1.3.2');
//wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false, '1.7.2');
wp_enqueue_script('jquery');
}
 


//default left sidebar, widgetized
	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name'=> 'Rice Design Alliance Left Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name'=> 'Rice Design Alliance Home Sidebar',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>',
		));
//register WordPress 3.0 navigation menus
add_action( 'init', 'register_my_menus' );

function register_my_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' )
		)
	);
}
//rda categories widget
	function widget_rdacategories($args) 
		{
			extract($args);
			$wp_list_categories_mod = wp_list_categories('echo=0&orderby=name&exclude=16&show_count=1&title_li=<h2>Categories</h2>');
			$wp_list_categories_mod = str_replace(array('(',')'), '', $wp_list_categories_mod);
			echo $wp_list_categories_mod;
		}
	register_sidebar_widget('RDA Categories','widget_rdacategories');

//rda archives widget
function widget_rdaarchives($args)
	{ 
	extract($args); ?>
    <li id="archives"><h2>Archives</h2>
    <ul>
	<li><a class="toggle" href="#" title="2011">2011</a>
    <ul class="months">
    	<li><a href="/2011/01" title="January 2011"><span>January</span></a></li>
        <li><a href="/2011/02" title="February 2011"><span>February</span></a></li>
        <li><a href="/2011/03" class="disabled" title="March 2011"><span>March</span></a></li>
        <li><a href="/2011/04" class="disabled" title="April 2011"><span>April</span></a></li>
        <li><a href="/2011/05" class="disabled" title="May 2011"><span>May</span></a></li>
        <li><a href="/2011/06" class="disabled" title="June 2011"><span>June</span></a></li>
        <li><a href="/2011/07" class="disabled" title="July 2011"><span>July</span></a></li>
        <li><a href="/2011/08" class="disabled" title="August 2011"><span>August</span></a></li>
        <li><a href="/2011/09" class="disabled" title="September 2011"><span>September</span></a></li>
        <li><a href="/2011/10" class="disabled" title="October 2011"><span>October</span></a></li>
        <li><a href="/2011/11" class="disabled" title="November 2011"><span>November</span></a></li>
        <li><a href="/2011/12" class="disabled" title="December 2011"><span>December</span></a></li>
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
    	<li><a  href="/2008/01" title="January 2008"><span>January</span></a></li>
        <li><a  href="/2008/02" title="February 2008"><span>February</span></a></li>
        <li><a  href="/2008/03" title="March 2008"><span>March</span></a></li>
        <li><a  href="/2008/04" title="April 2008"><span>April</span></a></li>
        <li><a  href="/2008/05" title="May 2008"><span>May</span></a></li>
        <li><a  class="disabled" href="/2008/06" title="June 2008"><span>June</span></a></li>
        <li><a  href="/2008/07" title="July 2008"><span>July</span></a></li>
        <li><a  href="/2008/08" title="August 2008"><span>August</span></a></li>
        <li><a  href="/2008/09" title="September 2008"><span>September</span></a></li>
        <li><a  href="/2008/10" title="October 2008"><span>October</span></a></li>
        <li><a  href="/2008/11" title="November 2008"><span>November</span></a></li>
        <li><a  class="disabled" href="/2008/12" title="December 2008"><span>December</span></a></li>
    </ul>    
    </li>
   
    <li><a class="toggle" href="#" title="2007">2007</a>
    <ul class="months">
        <li><a  href="/2007/01" title="January 2007"><span>January</span></a></li>
        <li><a  href="/2007/02" title="February 2007"><span>February</span></a></li>
        <li><a  href="/2007/03" title="March 2007"><span>March</span></a></li>
        <li><a  href="/2007/04" title="April 2007"><span>April</span></a></li>
        <li><a  href="/2007/05" title="May 2007"><span>May</span></a></li>
        <li><a  href="/2007/06" title="June 2007"><span>June</span></a></li>
        <li><a  href="/2007/07" title="July 2007"><span>July</span></a></li>
        <li><a  href="/2007/08" title="August 2007"><span>August</span></a></li>
        <li><a  href="/2007/09" title="September 2007"><span>September</span></a></li>
        <li><a  class="disabled" href="/2007/10" title="October 2007"><span>October</span></a></li>
        <li><a  href="/2007/11" title="November 2007"><span>November</span></a></li>
        <li><a  href="/2007/12" title="December 2007"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="2006">2006</a>
    <ul class="months">
        <li><a  href="/2006/01" title="January 2006"><span>January</span></a></li>
        <li><a  href="/2006/02" title="February 2006"><span>February</span></a></li>
        <li><a  href="/2006/03" title="March 2006"><span>March</span></a></li>
        <li><a  href="/2006/04" title="April 2006"><span>April</span></a></li>
        <li><a  href="/2006/05" title="May 2006"><span>May</span></a></li>
        <li><a  href="/2006/06" title="June 2006"><span>June</span></a></li>
        <li><a  class="disabled" href="/2006/07" title="July 2006"><span>July</span></a></li>
        <li><a  href="/2006/08" title="August 2006"><span>August</span></a></li>
        <li><a  href="/2006/09" title="September 2006"><span>September</span></a></li>
        <li><a  href="/2006/10" title="October 2006"><span>October</span></a></li>
        <li><a  href="/2006/11" title="November 2006"><span>November</span></a></li>
        <li><a  href="/2006/12" title="December 2006"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="2005">2005</a>
    <ul class="months">
        <li><a class="disabled" href="/2005/01" title="January 2005"><span>January</span></a></li>
        <li><a class="disabled" href="/2005/02" title="February 2005"><span>February</span></a></li>
        <li><a  href="/2005/03" title="March 2005"><span>March</span></a></li>
        <li><a  href="/2005/04" title="April 2005"><span>April</span></a></li>
        <li><a  href="/2005/05" title="May 2005"><span>May</span></a></li>
        <li><a  href="/2005/06" title="June 2005"><span>June</span></a></li>
        <li><a class="disabled" href="/2005/07" title="July 2005"><span>July</span></a></li>
        <li><a class="disabled" href="/2005/08" title="August 2005"><span>August</span></a></li>
        <li><a class="disabled" href="/2005/09" title="September 2005"><span>September</span></a></li>
        <li><a class="disabled" href="/2005/10" title="October 2005"><span>October</span></a></li>
        <li><a class="disabled" href="/2005/11" title="November 2005"><span>November</span></a></li>
        <li><a class="disabled" href="/2005/12" title="December 2005"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="2004">2004</a>
    <ul class="months">
        <li><a  href="/2004/01" title="January 2004"><span>January</span></a></li>
        <li><a class="disabled" href="/2004/02" title="February 2004"><span>February</span></a></li>
        <li><a class="disabled" href="/2004/03" title="March 2004"><span>March</span></a></li>
        <li><a  href="/2004/04" title="April 2004"><span>April</span></a></li>
        <li><a class="disabled" href="/2004/05" title="May 2004"><span>May</span></a></li>
        <li><a class="disabled" href="/2004/06" title="June 2004"><span>June</span></a></li>
        <li><a  href="/2004/07" title="July 2004"><span>July</span></a></li>
        <li><a class="disabled" href="/2004/08" title="August 2004"><span>August</span></a></li>
        <li><a class="disabled" href="/2004/09" title="September 2004"><span>September</span></a></li>
        <li><a  href="/2004/10" title="October 2004"><span>October</span></a></li>
        <li><a class="disabled" href="/2004/11" title="November 2004"><span>November</span></a></li>
        <li><a class="disabled" href="/2004/12" title="December 2004"><span>December</span></a></li>
    </ul>    
</li>
<!--<li><a class="toggle" href="#" title="2003">2003</a>
    <ul class="months">
        <li><a class="disabled" href="/2003/01" title="January 2003"><span>January</span></a></li>
        <li><a class="disabled" href="/2003/02" title="February 2003"><span>February</span></a></li>
        <li><a class="disabled" href="/2003/03" title="March 2003"><span>March</span></a></li>
        <li><a class="disabled" href="/2003/04" title="April 2003"><span>April</span></a></li>
        <li><a class="disabled" href="/2003/05" title="May 2003"><span>May</span></a></li>
        <li><a class="disabled" href="/2003/06" title="June 2003"><span>June</span></a></li>
        <li><a class="disabled" href="/2003/07" title="July 2003"><span>July</span></a></li>
        <li><a class="disabled" href="/2003/08" title="August 2003"><span>August</span></a></li>
        <li><a class="disabled" href="/2003/09" title="September 2003"><span>September</span></a></li>
        <li><a class="disabled" href="/2003/10" title="October 2003"><span>October</span></a></li>
        <li><a class="disabled" href="/2003/11" title="November 2003"><span>November</span></a></li>
        <li><a class="disabled" href="/2003/12" title="December 2003"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="2002">2002</a>
    <ul class="months">
        <li><a class="disabled" href="/2002/01" title="January 2002"><span>January</span></a></li>
        <li><a class="disabled" href="/2002/02" title="February 2002"><span>February</span></a></li>
        <li><a class="disabled" href="/2002/03" title="March 2002"><span>March</span></a></li>
        <li><a class="disabled" href="/2002/04" title="April 2002"><span>April</span></a></li>
        <li><a class="disabled" href="/2002/05" title="May 2002"><span>May</span></a></li>
        <li><a class="disabled" href="/2002/06" title="June 2002"><span>June</span></a></li>
        <li><a class="disabled" href="/2002/07" title="July 2002"><span>July</span></a></li>
        <li><a class="disabled" href="/2002/08" title="August 2002"><span>August</span></a></li>
        <li><a class="disabled" href="/2002/09" title="September 2002"><span>September</span></a></li>
        <li><a class="disabled" href="/2002/10" title="October 2002"><span>October</span></a></li>
        <li><a class="disabled" href="/2002/11" title="November 2002"><span>November</span></a></li>
        <li><a class="disabled" href="/2002/12" title="December 2002"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="2001">2001</a>
    <ul class="months">
        <li><a class="disabled" href="/2001/01" title="January 2001"><span>January</span></a></li>
        <li><a class="disabled" href="/2001/02" title="February 2001"><span>February</span></a></li>
        <li><a class="disabled" href="/2001/03" title="March 2001"><span>March</span></a></li>
        <li><a class="disabled" href="/2001/04" title="April 2001"><span>April</span></a></li>
        <li><a class="disabled" href="/2001/05" title="May 2001"><span>May</span></a></li>
        <li><a class="disabled" href="/2001/06" title="June 2001"><span>June</span></a></li>
        <li><a class="disabled" href="/2001/07" title="July 2001"><span>July</span></a></li>
        <li><a class="disabled" href="/2001/08" title="August 2001"><span>August</span></a></li>
        <li><a class="disabled" href="/2001/09" title="September 2001"><span>September</span></a></li>
        <li><a class="disabled" href="/2001/10" title="October 2001"><span>October</span></a></li>
        <li><a class="disabled" href="/2001/11" title="November 2001"><span>November</span></a></li>
        <li><a class="disabled" href="/2001/12" title="December 2001"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="2000">2000</a>
    <ul class="months">
        <li><a class="disabled" href="/2000/01" title="January 2000"><span>January</span></a></li>
        <li><a class="disabled" href="/2000/02" title="February 2000"><span>February</span></a></li>
        <li><a class="disabled" href="/2000/03" title="March 2000"><span>March</span></a></li>
        <li><a class="disabled" href="/2000/04" title="April 2000"><span>April</span></a></li>
        <li><a class="disabled" href="/2000/05" title="May 2000"><span>May</span></a></li>
        <li><a class="disabled" href="/2000/06" title="June 2000"><span>June</span></a></li>
        <li><a class="disabled" href="/2000/07" title="July 2000"><span>July</span></a></li>
        <li><a class="disabled" href="/2000/08" title="August 2000"><span>August</span></a></li>
        <li><a class="disabled" href="/2000/09" title="September 2000"><span>September</span></a></li>
        <li><a class="disabled" href="/2000/10" title="October 2000"><span>October</span></a></li>
        <li><a class="disabled" href="/2000/11" title="November 2000"><span>November</span></a></li>
        <li><a class="disabled" href="/2000/12" title="December 2000"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1999">1999</a>
    <ul class="months">
        <li><a class="disabled" href="/1999/01" title="January 1999"><span>January</span></a></li>
        <li><a class="disabled" href="/1999/02" title="February 1999"><span>February</span></a></li>
        <li><a class="disabled" href="/1999/03" title="March 1999"><span>March</span></a></li>
        <li><a class="disabled" href="/1999/04" title="April 1999"><span>April</span></a></li>
        <li><a class="disabled" href="/1999/05" title="May 1999"><span>May</span></a></li>
        <li><a class="disabled" href="/1999/06" title="June 1999"><span>June</span></a></li>
        <li><a class="disabled" href="/1999/07" title="July 1999"><span>July</span></a></li>
        <li><a class="disabled" href="/1999/08" title="August 1999"><span>August</span></a></li>
        <li><a class="disabled" href="/1999/09" title="September 1999"><span>September</span></a></li>
        <li><a class="disabled" href="/1999/10" title="October 1999"><span>October</span></a></li>
        <li><a class="disabled" href="/1999/11" title="November 1999"><span>November</span></a></li>
        <li><a class="disabled" href="/1999/12" title="December 1999"><span>December</span></a></li>
    </ul>    
</li>
    <li><a class="toggle" href="#" title="1998">1998</a>
    <ul class="months">
    	<li><a href="/1998/01" title="January 1998"><span>January</span></a></li>
        <li><a href="/1998/02" title="February 1998"><span>February</span></a></li>
        <li><a href="/1998/03" title="March 1998"><span>March</span></a></li>
        <li><a class="disabled" href="/1998/04" title="April 1998"><span>April</span></a></li>
        <li><a class="disabled" href="/1998/05" title="May 1998"><span>May</span></a></li>
        <li><a class="disabled" href="/1998/06" title="June 1998"><span>June</span></a></li>
        <li><a class="disabled" href="/1998/07" title="July 1998"><span>July</span></a></li>
        <li><a class="disabled" href="/1998/08" title="August 1998"><span>August</span></a></li>
        <li><a class="disabled" href="/1998/09" title="September 1998"><span>September</span></a></li>
        <li><a class="disabled" href="/1998/10" title="October 1998"><span>October</span></a></li>
        <li><a class="disabled" href="/1998/11" title="November 1998"><span>November</span></a></li>
        <li><a class="disabled" href="/1998/12" title="December 1998"><span>December</span></a></li>
    </ul>    
    </li> 
    <li><a class="toggle" href="#" title="1997">1997</a>
    <ul class="months">
    	<li><a class="disabled" href="/1997/01" title="January 1997"><span>January</span></a></li>
        <li><a class="disabled" href="/1997/02" title="February 1997"><span>February</span></a></li>
        <li><a class="disabled" href="/1997/03" title="March 1997"><span>March</span></a></li>
        <li><a class="disabled" href="/1997/04" title="April 1997"><span>April</span></a></li>
        <li><a class="disabled" href="/1997/05" title="May 1997"><span>May</span></a></li>
        <li><a class="disabled" href="/1997/06" title="June 1997"><span>June</span></a></li>
        <li><a class="disabled" href="/1997/07" title="July 1997"><span>July</span></a></li>
        <li><a class="disabled" href="/1997/08" title="August 1997"><span>August</span></a></li>
        <li><a class="disabled" href="/1997/09" title="September 1997"><span>September</span></a></li>
        <li><a class="disabled" href="/1997/10" title="October 1997"><span>October</span></a></li>
        <li><a class="disabled" href="/1997/11" title="November 1997"><span>November</span></a></li>
        <li><a href="/1997/12" title="December 1997"><span>December</span></a></li>
    </ul>    
    </li>
   
    <li><a class="toggle" href="#" title="1996">1996</a>
    <ul class="months">
        <li><a class="disabled" href="/1996/01" title="January 1996"><span>January</span></a></li>
        <li><a class="disabled" href="/1996/02" title="February 1996"><span>February</span></a></li>
        <li><a class="disabled" href="/1996/03" title="March 1996"><span>March</span></a></li>
        <li><a class="disabled" href="/1996/04" title="April 1996"><span>April</span></a></li>
        <li><a class="disabled" href="/1996/05" title="May 1996"><span>May</span></a></li>
        <li><a class="disabled" href="/1996/06" title="June 1996"><span>June</span></a></li>
        <li><a class="disabled" href="/1996/07" title="July 1996"><span>July</span></a></li>
        <li><a class="disabled" href="/1996/08" title="August 1996"><span>August</span></a></li>
        <li><a class="disabled" href="/1996/09" title="September 1996"><span>September</span></a></li>
        <li><a class="disabled" href="/1996/10" title="October 1996"><span>October</span></a></li>
        <li><a class="disabled" href="/1996/11" title="November 1996"><span>November</span></a></li>
        <li><a class="disabled" href="/1996/12" title="December 1996"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="1995">1995</a>
    <ul class="months">
        <li><a class="disabled" href="/1995/01" title="January 1995"><span>January</span></a></li>
        <li><a class="disabled" href="/1995/02" title="February 1995"><span>February</span></a></li>
        <li><a class="disabled" href="/1995/03" title="March 1995"><span>March</span></a></li>
        <li><a class="disabled" href="/1995/04" title="April 1995"><span>April</span></a></li>
        <li><a class="disabled" href="/1995/05" title="May 1995"><span>May</span></a></li>
        <li><a class="disabled" href="/1995/06" title="June 1995"><span>June</span></a></li>
        <li><a class="disabled" href="/1995/07" title="July 1995"><span>July</span></a></li>
        <li><a class="disabled" href="/1995/08" title="August 1995"><span>August</span></a></li>
        <li><a class="disabled" href="/1995/09" title="September 1995"><span>September</span></a></li>
        <li><a class="disabled" href="/1995/10" title="October 1995"><span>October</span></a></li>
        <li><a class="disabled" href="/1995/11" title="November 1995"><span>November</span></a></li>
        <li><a class="disabled" href="/1995/12" title="December 1995"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="1994">1994</a>
    <ul class="months">
        <li><a class="disabled" href="/1994/01" title="January 1994"><span>January</span></a></li>
        <li><a class="disabled" href="/1994/02" title="February 1994"><span>February</span></a></li>
        <li><a class="disabled" href="/1994/03" title="March 1994"><span>March</span></a></li>
        <li><a class="disabled" href="/1994/04" title="April 1994"><span>April</span></a></li>
        <li><a class="disabled" href="/1994/05" title="May 1994"><span>May</span></a></li>
        <li><a class="disabled" href="/1994/06" title="June 1994"><span>June</span></a></li>
        <li><a class="disabled" href="/1994/07" title="July 1994"><span>July</span></a></li>
        <li><a class="disabled" href="/1994/08" title="August 1994"><span>August</span></a></li>
        <li><a class="disabled" href="/1994/09" title="September 1994"><span>September</span></a></li>
        <li><a class="disabled" href="/1994/10" title="October 1994"><span>October</span></a></li>
        <li><a class="disabled" href="/1994/11" title="November 1994"><span>November</span></a></li>
        <li><a class="disabled" href="/1994/12" title="December 1994"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1993">1993</a>
    <ul class="months">
        <li><a class="disabled" href="/1993/01" title="January 1993"><span>January</span></a></li>
        <li><a class="disabled" href="/1993/02" title="February 1993"><span>February</span></a></li>
        <li><a class="disabled" href="/1993/03" title="March 1993"><span>March</span></a></li>
        <li><a class="disabled" href="/1993/04" title="April 1993"><span>April</span></a></li>
        <li><a class="disabled" href="/1993/05" title="May 1993"><span>May</span></a></li>
        <li><a class="disabled" href="/1993/06" title="June 1993"><span>June</span></a></li>
        <li><a class="disabled" href="/1993/07" title="July 1993"><span>July</span></a></li>
        <li><a class="disabled" href="/1993/08" title="August 1993"><span>August</span></a></li>
        <li><a class="disabled" href="/1993/09" title="September 1993"><span>September</span></a></li>
        <li><a class="disabled" href="/1993/10" title="October 1993"><span>October</span></a></li>
        <li><a class="disabled" href="/1993/11" title="November 1993"><span>November</span></a></li>
        <li><a class="disabled" href="/1993/12" title="December 1993"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1992">1992</a>
    <ul class="months">
        <li><a class="disabled" href="/1992/01" title="January 1992"><span>January</span></a></li>
        <li><a class="disabled" href="/1992/02" title="February 1992"><span>February</span></a></li>
        <li><a class="disabled" href="/1992/03" title="March 1992"><span>March</span></a></li>
        <li><a class="disabled" href="/1992/04" title="April 1992"><span>April</span></a></li>
        <li><a class="disabled" href="/1992/05" title="May 1992"><span>May</span></a></li>
        <li><a class="disabled" href="/1992/06" title="June 1992"><span>June</span></a></li>
        <li><a class="disabled" href="/1992/07" title="July 1992"><span>July</span></a></li>
        <li><a class="disabled" href="/1992/08" title="August 1992"><span>August</span></a></li>
        <li><a class="disabled" href="/1992/09" title="September 1992"><span>September</span></a></li>
        <li><a class="disabled" href="/1992/10" title="October 1992"><span>October</span></a></li>
        <li><a class="disabled" href="/1992/11" title="November 1992"><span>November</span></a></li>
        <li><a class="disabled" href="/1992/12" title="December 1992"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1991">1991</a>
    <ul class="months">
        <li><a class="disabled" href="/1991/01" title="January 1991"><span>January</span></a></li>
        <li><a class="disabled" href="/1991/02" title="February 1991"><span>February</span></a></li>
        <li><a class="disabled" href="/1991/03" title="March 1991"><span>March</span></a></li>
        <li><a class="disabled" href="/1991/04" title="April 1991"><span>April</span></a></li>
        <li><a class="disabled" href="/1991/05" title="May 1991"><span>May</span></a></li>
        <li><a class="disabled" href="/1991/06" title="June 1991"><span>June</span></a></li>
        <li><a class="disabled" href="/1991/07" title="July 1991"><span>July</span></a></li>
        <li><a class="disabled" href="/1991/08" title="August 1991"><span>August</span></a></li>
        <li><a class="disabled" href="/1991/09" title="September 1991"><span>September</span></a></li>
        <li><a class="disabled" href="/1991/10" title="October 1991"><span>October</span></a></li>
        <li><a class="disabled" href="/1991/11" title="November 1991"><span>November</span></a></li>
        <li><a class="disabled" href="/1991/12" title="December 1991"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1990">1990</a>
    <ul class="months">
        <li><a class="disabled" href="/1990/01" title="January 1990"><span>January</span></a></li>
        <li><a class="disabled" href="/1990/02" title="February 1990"><span>February</span></a></li>
        <li><a class="disabled" href="/1990/03" title="March 1990"><span>March</span></a></li>
        <li><a class="disabled" href="/1990/04" title="April 1990"><span>April</span></a></li>
        <li><a class="disabled" href="/1990/05" title="May 1990"><span>May</span></a></li>
        <li><a class="disabled" href="/1990/06" title="June 1990"><span>June</span></a></li>
        <li><a class="disabled" href="/1990/07" title="July 1990"><span>July</span></a></li>
        <li><a class="disabled" href="/1990/08" title="August 1990"><span>August</span></a></li>
        <li><a class="disabled" href="/1990/09" title="September 1990"><span>September</span></a></li>
        <li><a class="disabled" href="/1990/10" title="October 1990"><span>October</span></a></li>
        <li><a class="disabled" href="/1990/11" title="November 1990"><span>November</span></a></li>
        <li><a class="disabled" href="/1990/12" title="December 1990"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1989">1989</a>
    <ul class="months">
        <li><a class="disabled" href="/1989/01" title="January 1989"><span>January</span></a></li>
        <li><a class="disabled" href="/1989/02" title="February 1989"><span>February</span></a></li>
        <li><a class="disabled" href="/1989/03" title="March 1989"><span>March</span></a></li>
        <li><a class="disabled" href="/1989/04" title="April 1989"><span>April</span></a></li>
        <li><a class="disabled" href="/1989/05" title="May 1989"><span>May</span></a></li>
        <li><a class="disabled" href="/1989/06" title="June 1989"><span>June</span></a></li>
        <li><a class="disabled" href="/1989/07" title="July 1989"><span>July</span></a></li>
        <li><a class="disabled" href="/1989/08" title="August 1989"><span>August</span></a></li>
        <li><a class="disabled" href="/1989/09" title="September 1989"><span>September</span></a></li>
        <li><a class="disabled" href="/1989/10" title="October 1989"><span>October</span></a></li>
        <li><a class="disabled" href="/1989/11" title="November 1989"><span>November</span></a></li>
        <li><a class="disabled" href="/1989/12" title="December 1989"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1988">1988</a>
    <ul class="months">
        <li><a class="disabled" href="/1988/01" title="January 1988"><span>January</span></a></li>
        <li><a class="disabled" href="/1988/02" title="February 1988"><span>February</span></a></li>
        <li><a class="disabled" href="/1988/03" title="March 1988"><span>March</span></a></li>
        <li><a class="disabled" href="/1988/04" title="April 1988"><span>April</span></a></li>
        <li><a class="disabled" href="/1988/05" title="May 1988"><span>May</span></a></li>
        <li><a class="disabled" href="/1988/06" title="June 1988"><span>June</span></a></li>
        <li><a class="disabled" href="/1988/07" title="July 1988"><span>July</span></a></li>
        <li><a class="disabled" href="/1988/08" title="August 1988"><span>August</span></a></li>
        <li><a class="disabled" href="/1988/09" title="September 1988"><span>September</span></a></li>
        <li><a class="disabled" href="/1988/10" title="October 1988"><span>October</span></a></li>
        <li><a class="disabled" href="/1988/11" title="November 1988"><span>November</span></a></li>
        <li><a class="disabled" href="/1988/12" title="December 1988"><span>December</span></a></li>
    </ul>    
</li>
    <li><a class="toggle" href="#" title="1987">1987</a>
    <ul class="months">
    	<li><a href="/1987/01" title="January 1987"><span>January</span></a></li>
        <li><a href="/1987/02" title="February 1987"><span>February</span></a></li>
        <li><a href="/1987/03" title="March 1987"><span>March</span></a></li>
        <li><a class="disabled" href="/1987/04" title="April 1987"><span>April</span></a></li>
        <li><a class="disabled" href="/1987/05" title="May 1987"><span>May</span></a></li>
        <li><a class="disabled" href="/1987/06" title="June 1987"><span>June</span></a></li>
        <li><a class="disabled" href="/1987/07" title="July 1987"><span>July</span></a></li>
        <li><a class="disabled" href="/1987/08" title="August 1987"><span>August</span></a></li>
        <li><a class="disabled" href="/1987/09" title="September 1987"><span>September</span></a></li>
        <li><a class="disabled" href="/1987/10" title="October 1987"><span>October</span></a></li>
        <li><a class="disabled" href="/1987/11" title="November 1987"><span>November</span></a></li>
        <li><a class="disabled" href="/1987/12" title="December 1987"><span>December</span></a></li>
    </ul>    
    </li> 
    <li><a class="toggle" href="#" title="1986">1986</a>
    <ul class="months">
    	<li><a class="disabled" href="/1986/01" title="January 1986"><span>January</span></a></li>
        <li><a class="disabled" href="/1986/02" title="February 1986"><span>February</span></a></li>
        <li><a class="disabled" href="/1986/03" title="March 1986"><span>March</span></a></li>
        <li><a class="disabled" href="/1986/04" title="April 1986"><span>April</span></a></li>
        <li><a class="disabled" href="/1986/05" title="May 1986"><span>May</span></a></li>
        <li><a class="disabled" href="/1986/06" title="June 1986"><span>June</span></a></li>
        <li><a class="disabled" href="/1986/07" title="July 1986"><span>July</span></a></li>
        <li><a class="disabled" href="/1986/08" title="August 1986"><span>August</span></a></li>
        <li><a class="disabled" href="/1986/09" title="September 1986"><span>September</span></a></li>
        <li><a class="disabled" href="/1986/10" title="October 1986"><span>October</span></a></li>
        <li><a class="disabled" href="/1986/11" title="November 1986"><span>November</span></a></li>
        <li><a href="/1986/12" title="December 1986"><span>December</span></a></li>
    </ul>    
    </li>
   
    <li><a class="toggle" href="#" title="1985">1985</a>
    <ul class="months">
        <li><a class="disabled" href="/1985/01" title="January 1985"><span>January</span></a></li>
        <li><a class="disabled" href="/1985/02" title="February 1985"><span>February</span></a></li>
        <li><a class="disabled" href="/1985/03" title="March 1985"><span>March</span></a></li>
        <li><a class="disabled" href="/1985/04" title="April 1985"><span>April</span></a></li>
        <li><a class="disabled" href="/1985/05" title="May 1985"><span>May</span></a></li>
        <li><a class="disabled" href="/1985/06" title="June 1985"><span>June</span></a></li>
        <li><a class="disabled" href="/1985/07" title="July 1985"><span>July</span></a></li>
        <li><a class="disabled" href="/1985/08" title="August 1985"><span>August</span></a></li>
        <li><a class="disabled" href="/1985/09" title="September 1985"><span>September</span></a></li>
        <li><a class="disabled" href="/1985/10" title="October 1985"><span>October</span></a></li>
        <li><a class="disabled" href="/1985/11" title="November 1985"><span>November</span></a></li>
        <li><a class="disabled" href="/1985/12" title="December 1985"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="1984">1984</a>
    <ul class="months">
        <li><a class="disabled" href="/1984/01" title="January 1984"><span>January</span></a></li>
        <li><a class="disabled" href="/1984/02" title="February 1984"><span>February</span></a></li>
        <li><a class="disabled" href="/1984/03" title="March 1984"><span>March</span></a></li>
        <li><a class="disabled" href="/1984/04" title="April 1984"><span>April</span></a></li>
        <li><a class="disabled" href="/1984/05" title="May 1984"><span>May</span></a></li>
        <li><a class="disabled" href="/1984/06" title="June 1984"><span>June</span></a></li>
        <li><a class="disabled" href="/1984/07" title="July 1984"><span>July</span></a></li>
        <li><a class="disabled" href="/1984/08" title="August 1984"><span>August</span></a></li>
        <li><a class="disabled" href="/1984/09" title="September 1984"><span>September</span></a></li>
        <li><a class="disabled" href="/1984/10" title="October 1984"><span>October</span></a></li>
        <li><a class="disabled" href="/1984/11" title="November 1984"><span>November</span></a></li>
        <li><a class="disabled" href="/1984/12" title="December 1984"><span>December</span></a></li>
    </ul>    
</li>

<li><a class="toggle" href="#" title="1983">1983</a>
    <ul class="months">
        <li><a class="disabled" href="/1983/01" title="January 1983"><span>January</span></a></li>
        <li><a class="disabled" href="/1983/02" title="February 1983"><span>February</span></a></li>
        <li><a class="disabled" href="/1983/03" title="March 1983"><span>March</span></a></li>
        <li><a class="disabled" href="/1983/04" title="April 1983"><span>April</span></a></li>
        <li><a class="disabled" href="/1983/05" title="May 1983"><span>May</span></a></li>
        <li><a class="disabled" href="/1983/06" title="June 1983"><span>June</span></a></li>
        <li><a class="disabled" href="/1983/07" title="July 1983"><span>July</span></a></li>
        <li><a class="disabled" href="/1983/08" title="August 1983"><span>August</span></a></li>
        <li><a class="disabled" href="/1983/09" title="September 1983"><span>September</span></a></li>
        <li><a class="disabled" href="/1983/10" title="October 1983"><span>October</span></a></li>
        <li><a class="disabled" href="/1983/11" title="November 1983"><span>November</span></a></li>
        <li><a class="disabled" href="/1983/12" title="December 1983"><span>December</span></a></li>
    </ul>    
</li>
<li><a class="toggle" href="#" title="1982">1982</a>
    <ul class="months">
        <li><a class="disabled" href="/1982/01" title="January 1982"><span>January</span></a></li>
        <li><a class="disabled" href="/1982/02" title="February 1982"><span>February</span></a></li>
        <li><a class="disabled" href="/1982/03" title="March 1982"><span>March</span></a></li>
        <li><a class="disabled" href="/1982/04" title="April 1982"><span>April</span></a></li>
        <li><a class="disabled" href="/1982/05" title="May 1982"><span>May</span></a></li>
        <li><a class="disabled" href="/1982/06" title="June 1982"><span>June</span></a></li>
        <li><a class="disabled" href="/1982/07" title="July 1982"><span>July</span></a></li>
        <li><a class="disabled" href="/1982/08" title="August 1982"><span>August</span></a></li>
        <li><a class="disabled" href="/1982/09" title="September 1982"><span>September</span></a></li>
        <li><a class="disabled" href="/1982/10" title="October 1982"><span>October</span></a></li>
        <li><a class="disabled" href="/1982/11" title="November 1982"><span>November</span></a></li>
        <li><a class="disabled" href="/1982/12" title="December 1982"><span>December</span></a></li>
    </ul>    
</li>-->
    </ul>
    </li>
<?php } 
register_sidebar_widget('RDA Archives','widget_rdaarchives');

//rda tags widget
function widget_rdatags($args)
	{
	extract($args);
	if ( function_exists('wp_tag_cloud') ) : ?>
        <li id="popular">
        <h2>Popular Topics</h2>
        <?php wp_tag_cloud('smallest=8&largest=12&orderby=count&order=DESC'); ?>
        </li>
	<?php endif;
	} 
register_sidebar_widget('RDA Tags','widget_rdatags');

//home calendar
function widget_rdacalendar($args) { ?>
      <li id="homesidebarcalendar">
        <h2><a href="http://ricedesignalliance.org/category/calendar" title="Calendar">Calendar</a></h2>
        <ul>
          <li>
          
          <?php
	ec3_get_events(
	  16,
      4,
      '<a href="%LINK%"><strong>%DATE%:</strong> &mdash; %TITLE% &mdash; %TIME%</a>',
      '',
      get_option('date_format')
    );
	?>
          </li>
          <div id="morevents">
          	<a href="http://ricedesignalliance.org/category/calendar" title="More Events &gt;" class="rda" style="clear: none;">More Events &gt;</a> |
          	<a href="<?php echo get_bloginfo('url'); ?>/category/calendar/?ec3_ical" class="rda" style="clear: none;">Subscribe via iCal</a>
          </div>
        </ul>
      </li>
<?php }
register_sidebar_widget('RDA Home Calendar','widget_rdacalendar');

//offcite stuff
function widget_rdaoffcite($args) { ?>
      <li id="citemagazinepanel">
        <h2><a href="http://citemag.org/" title="Cite Magazine">Cite Magazine</a></h2>
        <ul id="citemagpadding">
        	<?php 
					$rdaocwidget = new WP_Query();
					$rdaocwidget->query('page_id=3125'); ?>
          <?php while ($rdaocwidget->have_posts()) : $rdaocwidget->the_post(); ?>
          			<?php the_content(); ?>
          <?php endwhile; ?>
        
       
        
        <li><ul id="citelinks">
            <li><a href="http://ricedesignalliance.org/what-we-do/cite-magazine" title="go to Cite Magazine">go to <strong>Cite Magazine</strong> &gt;</a></li>
            <li><a href="https://securews.rice.edu/rda.rice.edu/cite/index.cfm" title="subscribe to Cite Magazine">subscribe to <strong>Cite Magazine</strong> &gt;</a></li>
            
        </ul></li>
        <li><h4><a href="http://offcite.org/feeds" title="OffCite Feed">OffCite Feed</a></h4></li>
        <li><script src="http://feeds2.feedburner.com/offciteblog?format=sigpro" type="text/javascript" ></script><noscript><p>Subscribe to RSS headline updates from: <a href="http://feeds2.feedburner.com/offciteblog"></a><br/>Powered by FeedBurner</p> </noscript></li></ul>
      </li>
<?php }
register_sidebar_widget('RDA Offcite Feed','widget_rdaoffcite'); ?>