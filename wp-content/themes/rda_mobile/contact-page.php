<?php get_header(); ?>
<!--CONTENT-->
  <div id="content">
  
 <div id="posts_container">  
  
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  	
   <!--POST-->
    <div class="page_post">

	<div class="entry">
  	
	<?php the_content(); ?>

  <?php $mailTo = get_option('ikn_email_account');
	$greetingsMessage = get_option('ikn_message'); ?>
    
<script type="text/javascript">
function isValidEmailAddress(emailAddress){
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
	jQuery(function() {
	jQuery('label.error').hide();
	//trigger ajax on submit
	jQuery('#contactform').submit( function(){

	// validate and process form here  

     jQuery('label.error').hide();  
      var name = jQuery("input#name").val();  
      if (name == "") {  
      jQuery("label#name_error").show();  
      jQuery("input#name").focus();  
      return false;  
     }  
     var email = jQuery("input#email").val();  
     if (email == "" || !isValidEmailAddress(email)) {  
     jQuery("label#email_error").show();  
     jQuery("input#email").focus();  
       return false;  
     }  
		//hide the form
		jQuery('#contactform').hide();
		
		//show the loading bar
		jQuery('.loader').append(jQuery('.bar')); 
		jQuery('.bar').css({display:'block'});
		
		//send the ajax request  
		jQuery.get('<?php bloginfo('template_directory'); ?>/mail.php',{name:jQuery('#name').val(), email:jQuery('#email').val(), subject:jQuery('#subject').val(), comment:jQuery('#message').val(),mailto:"<?php echo $mailTo; ?>",greetingsmessage:"<?php echo $greetingsMessage; ?>"},
	
		//return the data
		function(data){
			//hide the graphic
			jQuery('.bar').css({display:'none'}); 
			jQuery('.loader').append(data);  
		});	
	
		//stay on the page					
		return false;	
	});
});
</script> 
<!--RESPOND-->
<div id="respond">
  <h4 id="comment-form-title"><?php _e('Send us email','ikon'); ?></h4>
<div class="loader"></div>
	<div class="bar"></div>  
  
  <form id="contactform" method="post" action="<?php bloginfo('template_directory'); ?>/mail.php">

    
      <p>
        <input onblur="textInInput(this,0);" onfocus="textInInput(this,1);" type="text" id="name" name="name" value="<?php _e('Name','ikon'); ?>" />
        <label class="error" for="name" id="name_error">This field is required.</label>
      </p>
      
      <p>
        <input onblur="textInInput(this,0);" onfocus="textInInput(this,1);" type="text" id="email" name="email" value="<?php _e('E-mail','ikon'); ?>" />
        <label class="error" for="email" id="email_error">This field is required or enter a valid email address </label>
      </p>
      
      <p>
      
        <input onblur="textInInput(this,0);" onfocus="textInInput(this,1);" type="text" id="subject" name="subject" value="<?php _e('Subject','ikon'); ?>" /></p>
        
      <p>
        <textarea onblur="textInInput(this,0);" onfocus="textInInput(this,1);" id="message" name="message" cols="40" tabindex="4" rows="7" ><?php _e('Message','ikon'); ?></textarea>
      </p>
      <p>
        <button type="submit" id="comment-submit" class="btn_submit" tabindex="5"><span><?php echo esc_attr(__('Send Message','ikon')); ?></span></button>
      </p>

    <div> </div>
  </form>
</div>
<!--//END OF RESPONDS-->
 </div>
  
  
  </div>
    <!--// END OF THE POST-->

 <?php endwhile; ?>
 <?php else : ?>
		
  		<div class="post">
		
       	    <h2><?php _e('Error 404, no posts found!','ikon'); ?></h2>
	
       	    <p><?php _e('Sorry, no posts matched your criteria.','ikon'); ?></p>
	
		</div>
		
<?php endif; ?>       

</div>
<!--//END OF POSTS CONTAINER-->

  </div>
  <!--//END OF CONTENT-->
    <div class="bt_line_shd">&nbsp;</div>
    <div class="bt_shd">&nbsp;</div>   

<?php get_footer(); ?>