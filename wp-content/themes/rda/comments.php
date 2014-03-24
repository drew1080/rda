<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->
<?php $i = 0; ?>
<?php if ($comments) : ?>
	<h4 id="comments"><?php comments_number('<span>0</span> Comments', '<span>1</span> Comment', '<span>%</span> Comments' );?></h4>

	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>
    <?php $i++; ?> 

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
        
        <p class="leftmetadata">
			<?php comment_author_link() ?> <span class="citewrite">writes:</span>
            
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			<br />

			<span class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('m.d.y') ?> <br /> <?php comment_time() ?></a></span>

		</p>
		<div class="commentcontent">
			<span class="commentcount"><?php echo $i; ?></span>

			<?php comment_text() ?>
        </div>
        
        <hr class="space" />

		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		
        <hr class="space" style="margin-bottom: 1.5em;" />

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<h4 id="respond">+ Add a Comment</h4>

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<p><label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label><br /><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
</p>

<p><label for="email"><small>Email <?php if ($req) echo "(required)"; ?> (will not be published)</small></label><br /><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2"  />
</p>

<p><label for="url"><small>Website</small></label><br /><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><label for="comment"><small>Comment</small></label><br /><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p style="text-align: right; margin: 0; padding: 0;"><input name="submit" type="image" src="<?php bloginfo('template_url'); ?>/images/submit_comment.png" id="submit" tabindex="5" value="Submit Comment" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

<p class="disclaimer">Rice Design Alliance reserves the right to restrict comments that do not contribute constructively to the conversation at hand, contain profanity, personal attacks or seek to promote a personal or unrelated business.</p>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>