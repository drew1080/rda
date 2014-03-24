<?php get_header(); ?>

<?php if(get_option('ikn_front_page')=='Page') : ?>

<!--CONTENT-->
<div id="content">

<!--POST-->
    <div class="page_post">
  
  <div class="entry">
  
  <?php 
  	
	$h_page_id = get_page_id(get_option('ikn_home_page'));
	$page_data = get_page($h_page_id);
  	$content = $page_data->post_content;

	echo $content;
  
  ?>
  
  
  </div>
  
  
  </div>
    <!--// END OF THE POST-->

</div>
<!--//END OF CONTENT-->


<?php else :   get_template_part('home', 'loop');  endif; ?>






<?php get_footer(); ?>