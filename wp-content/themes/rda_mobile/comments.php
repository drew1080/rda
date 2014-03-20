 <!--COMMENTS-->
 <div id="post_comments" class="selectable">
  <!--HEADER-->
  <div class="header">
<div class="top_hd_bg">&nbsp;</div>
    <!--TOP LINKS-->
    <div class="top-links">
		
        <a class="t_done goback left" href="#"><span><?php _e('Done', 'ikon'); ?></span></a>
        
        </div>
<!--//END OF TOP LINKS-->  
<div class="bt_hd_bg">&nbsp;</div>
  </div>  
  <!--//END OF HEADER-->
  
<?php
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');
 
if ( post_password_required() ) { ?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','ikon'); ?></p>
<?php
return;
}
?>
<div id="comments">
<!--COMMENTS-->
<?php if ( have_comments() ) : ?>

<div class="title_l">&nbsp;</div>
<div class="title"><h1><?php comments_number('No Responses', 'One Response', '% Responses' );?></h1></div>
<ol id="commentlist">
	<?php wp_list_comments('callback=ikn_comment&max_depth=1'); ?>
</ol>
<div class="navigation">
<div class="alignleft"><?php previous_comments_link() ?></div>
<div class="alignright"><?php next_comments_link() ?></div>
</div>
<!--//END OF COMMENTS-->

<?php else : // this is displayed if there are no comments so far ?>
 
<?php if ('open' == $post->comment_status) : ?>
<!-- If comments are open, but there are no comments. -->
 
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments"><?php _e('Comments are closed.','ikon'); ?></p>
<?php endif; ?>
<?php endif; ?>  

<?php if ('open' == $post->comment_status) : ?>
<div class="title_l">&nbsp;</div>
<div class="title"><h1 id="postcomment"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h1></div>

<!--RESPOND-->
<div id="respond">
	<div class="form-holder">
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p class="logged"><?php _e('You must be','ikon'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in','ikon'); ?></a> <?php _e('to post a comment.','ikon'); ?></p>
<?php else : ?>  
  
  <form id="commentform" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
  
  <?php if ( $user_ID ) : ?>
 
<p class="logged"><?php _e('Logged in as','ikon'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out &raquo;','ikon'); ?></a></p>


<?php else : ?>
  
    <p><input type="text" tabindex="1" size="22" onBlur="textInInput(this,0);" onFocus="textInInput(this,1);" value="Name..." id="author" name="author" class="required" gtbfieldid="24"></p>
  
    <p><input type="text" tabindex="2" size="22" onBlur="textInInput(this,0);" onFocus="textInInput(this,1);" value="Email..." id="email" name="email" class="required email" gtbfieldid="25"></p>
  
    <p><input type="text" tabindex="3" size="22" onBlur="textInInput(this,0);" onFocus="textInInput(this,1);" value="Website..." id="url" name="url" gtbfieldid="26"></p>


<?php endif; ?> 
  
    <p><textarea tabindex="4" onBlur="textInInput(this,0);" onFocus="textInInput(this,1);" rows="10" cols="20" id="comment" name="comment">Comment...</textarea></p>
  
    <p class="submit">
          <button tabindex="5" class="btn_submit right" id="comment-submit" type="submit"><span><?php echo esc_attr(__('Submit comment','ikon')); ?></span></button>
		    
              <?php comment_id_fields(); ?>
        	  <?php do_action('comment_form', $post->ID); ?>
          
    </p>
  </form>
<?php endif; // If registration required and not logged in ?>

  </div>

</div>
<!--//END OF RESPOND-->
<div class="bt_line_shd">&nbsp;</div>
<div class="bt_shd">&nbsp;</div>
 <?php endif;?>  

 
</div>
<!--//END OF THE COMMENTS-->

</div> 
