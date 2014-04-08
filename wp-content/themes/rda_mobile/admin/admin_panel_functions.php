<?php
/*
 * Build upload field
 */

function ikon_get_upload_field($id, $std = '', $desc = '') {
  $data = get_option($id);

  $field = '<input id="' . $id . '" type="file" name="attachment_' . $id . '" />' .
           '<span class="upload-submit"><input name="save" type="submit" value="Upload" class="button panel-upload-save" />
		   </span> ' .
           ($data ?
				ikon_get_upload_image_preview($data) .
		   '<div class="img_location" ><input id="header_img_location" class="img_location regular-text" type="text" class="" name="' . $id . '" value="' . ($data ? $data : $std) . '" readonly="readonly" /></div>
		   <input name="save" id="'.$id.'" type="submit" class="remove_img button panel-upload-save hide-if-no-js" value="' . __("Remove") .'" />
		   <input name="save" id="'.$id.'" type="submit" class="remove_img hide-if-js remove_img button panel-upload-save" value="' . __("Remove $id") .'" />

		   ' : '') ;

  return $field;
}

/*
 * Build image preview using timthumb.php
 */
function ikon_get_upload_image_preview($data = '') {
  if (!empty($data)) {
    $img_preview = '<div class="img_preview">' .
                  '<img src="'. $data . '" alt="Thumbnail Preview">' .
                  '</div>';

                    return $img_preview;

    return $img_preview;
  }
  else {
    return;
  }
}
/**
 * Check to see if the current user has sufficiant abilities to manage the options based on the WP version
 *
 * On pre 3.0, the capability checked is manage_options, post it is edit_theme_options
 *
 */
function ikon_can_edit_theme_options(){
	global $wp_version;

	if( $wp_version{0} != 3)
		return current_user_can( 'manage_options' );
	else
		return current_user_can( 'edit_theme_options' ) ;
}
?>