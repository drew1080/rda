<?php
/**
 * The Generate the javascript for the ajax remove image feature
 *
 */
function iwbc_javascript() {
global $shortname;
$nonce= wp_create_nonce  ('my-nonce');
?>
<?php include( IWBC_ABSPATH . '/admin/js/checkbox.php'); ?>
<script type="text/javascript" >
jQuery(document).ready(function($) {
	
	$(':range').rangeinput();
	$("ul.tabins").tabs("div.panes > div");
	$('input:checkbox:not([safari])').checkbox();
	$('input[safari]:checkbox').checkbox({cls:'jquery-safari-checkbox'});
	$('input:radio').checkbox();
	
	// Register the action we are looking for
	$('.remove_img').click(function(){

		var id = $(this).attr('id');

		var container = $(this).parent();

		// Set what we are going to send via ajax
		var data = {
			action: 'remove_header_image',
			_nonce: '<?php echo $nonce; ?>',
			field: id,
		};

		// Disable submitting options
		$('#childoption_submit').attr('disabled', 'disabled');

		jQuery.post(ajaxurl, data, function(response) {
			if (response == 'Nice Try')
			{
				alert(response)
			}
			else if (response == 'Error')
			{
				alert('<?php _e('Unable to remove image') ?>')
			}
			else
			{

				//remove thumbnail, path to image, and remove button
				$(container).children('.img_preview').fadeOut("slow");
				$(container).children('.remove_img').fadeOut("slow");
				$(container).children('.img_location').fadeOut("slow");

				//remove input so it doesn't accidentaly get processed
				$(container).children('.img_location').remove();

				//let user know what happened
				$(container).prepend('<p><span class="updated inline">' + response +'</span><br /></p>');
			}
		});

		// Allow option submition again
		$('#childoption_submit').removeAttr("disabled");

		// Stop the submit button from actually submitting the form
		return false;
	});
	
	$('input.color-picker-input').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
	onChange: function (hsb, hex, rgb) {
		jQuery('.demo-menu, .menu').css('backgroundColor', '#' + hex);
	}
	})
	.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});

	$('input.color-picker-input-link').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
	onChange: function (hsb, hex, rgb) {
		jQuery('.menu a.link').css('color', '#' + hex);
	}
	})
	.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});
	$('input.color-picker-input-link-hover').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	},
	onChange: function (hsb, hex, rgb) {
		jQuery('.menu a.hover-link').css('color', '#' + hex);
	}
	})
	.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});

});
</script>
<?php 
}
add_action('wp_ajax_remove_header_image', 'iwbc_remove_header_image_callback');
/**
 * Process our Ajax request to remove the header image
 *
 */
function iwbc_remove_header_image_callback() {
$nonce=$_REQUEST['_nonce'];
if (! wp_verify_nonce($nonce, 'my-nonce') ) die('Nice Try');
if (! iwbc_can_edit_theme_options() ) die('Nice Try');

	global $wpdb; // this is how you get access to the database
	$delete = delete_option($_REQUEST['field']);
	if ($delete == true)
		_e("Image Removed");
	else
		_e("Error");

	die();
}
?>