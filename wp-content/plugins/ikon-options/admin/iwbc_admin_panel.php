<?php
add_action('admin_init', 'iwbc_add_init');
add_action('admin_menu' , 'iwbc_add_admin');
/*
 * Create theme panel.
 */
function iwbc_add_admin() {
  global $mobiname, $shortname, $moboptions;
  if ($_GET['page'] == basename(__FILE__)) {
  if (! iwbc_can_edit_theme_options() ) wp_die('Nice Try');
	if ('save' == $_REQUEST['action']) {
      if (	  strpos($_REQUEST['save'], 'Remove') !== false	  ){
		  $field = substr_replace($_REQUEST['save'], '', 0, 7);
		  delete_option($field);
		  header('Location: admin.php?page=' . basename(__FILE__) . '&imgremoved=true' . $error);
      die;
	  }
	  foreach ($moboptions as $value) {
        $id = $value['id'];
        if ($value['type'] == 'upload') {
         if (!empty($_FILES['attachment_' . $id]['name'])) {
            // New Upload
            $whitelist = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
            $filetype = $_FILES['attachment_' . $id]['type'];
           if (in_array($filetype, $whitelist)) {
              $upload = wp_handle_upload($_FILES['attachment_' . $id], array('test_form' => false));
              $upload['option_name'] = $value['name'];
              update_option($id, $upload['url']);
            }
            else {
              $error = '&error=1';
            }
          }
          elseif (isset($_REQUEST[$id])) {
            // No new file, just the url
            update_option($id, $_REQUEST[$id]);
          }
          else {
            // Delete unwanted data
            delete_option($id);
          }
        }
        elseif ($value['type'] == 'checkbox') {
          if (isset($_REQUEST[$id])) {
            update_option($id, 'true');
          }
          else {
            update_option($id, 'false');
          }
        }
        else {
          if (isset($_REQUEST[$id])) {
            update_option($id, $_REQUEST[$id]);
          }
          else {
            delete_option($id);
          }
        }
      }
      header('Location: admin.php?page=' . basename(__FILE__) . '&saved=true' . $error);
      die;
    }
    else if ('reset' == $_REQUEST['action']) {
      foreach ($moboptions as $value) {
        delete_option($value['id']);
      }
      header('Location: admin.php?page=' . basename(__FILE__) . '&reset=true');
      die;
    }
    else if ('reset_widgets' == $_REQUEST['action']) {
      $null = null;
      update_option('sidebars_widgets', $null);
      header('Location: themes.php?page=' . basename(__FILE__) . '&reset=true');
      die;
    }
  }	
    $optionspage =  add_menu_page($mobiname, $mobiname, 'administrator', basename(__FILE__), 'iwbc_admin', IWBC_URI."admin/images/iphone-image.png");
	
	add_action('admin_print_styles-' . $optionspage, 'iwbc_admin_print_styles');
	add_action('admin_head-' . $optionspage, 'iwbc_javascript');

 // Default
}
function iwbc_add_init() {
wp_enqueue_style("functions-mob", IWBC_URI."/admin/iwbc_admin_panel_styles.css", false, "1.0", "all");
wp_enqueue_script('jquery');
wp_enqueue_script( 'tabs', IWBC_URI.'/admin/js/tabs.js', array( 'jquery' ) );
wp_enqueue_script( 'colorpicker-jq', IWBC_URI.'/admin/js/colorpicker.js', array( 'jquery' ) );
wp_enqueue_script( 'jquery-tools', IWBC_URI.'/admin/js/jquery.tools.min.js', array( 'jquery' ) );

}
function iwbc_admin_print_styles(){
	wp_enqueue_style('iwbc_style');
}

// Set up admin panel
function iwbc_admin() {
  if (! iwbc_can_edit_theme_options() ) wp_die('Nice Try');
  global $mobiname, $shortname, $moboptions;

  if ($_REQUEST['saved']) {
    echo '<div id="message" class="updated fade">' . $mobiname . ' ' . __('settings saved.') . '</div>';
  }
  if ($_REQUEST['reset']) {
    echo '<div id="message" class="updated fade">' . $mobiname . ' ' . __('settings reset.') . '</div>';
  }
  if ($_REQUEST['reset_widgets']) {
    echo '<div id="message" class="updated fade">' . $mobiname . ' ' . __('widgets reset.') . '</div>';
  }
  if ($_REQUEST['error']) {
    echo '<div id="message" class="updated fade">The file you submitted was not a valid image type.</div>';
  }
  if ($_REQUEST['imgremoved']) {
	  echo '<div id="message" class="updated fade">'. __('Image Removed') .'</div>';
  }
?>
<div class="wrap rm_wrap">

 <ul class="tabs">
    <li class="general-settings"><a href="#tab1">General</a></li>
    <li class="general-typography"><a href="#tab2">Typography</a></li>
    <li class="backgrounds-schemes"><a href="#tab3">Backgrounds and Schemes</a></li>
    <li class="header-options"><a href="#tab4">Header</a></li>    
    <li class="sliders-options"><a href="#tab5">Slider</a></li>
    <li class="homepage-options"><a href="#tab6">Homepage</a></li>
    <li class="blog-setup"><a href="#tab7">Blog</a></li>
    <li class="Contact"><a href="#tab8">Contact Form</a></li>
    <!--li class="miscellaneous"><a href="#tab5">Miscellaneous</a></li-->
</ul>
  <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <div class="tab_container">
        <?php iwbc_admin_get_my_options(); ?>
     </div>

<input type="hidden" name="action" value="save" />
  </form>
  <form method="post" action="">
    <span class="submit" style="float:left;">
      <input  name="reset" type="submit" value="<?php _e('Reset'); ?>" />
      <input type="hidden" name="action" value="reset" />
    </span>
  </form>
</div>
<?php
}

/*
 * This function does the actual work building out the theme options.
 *
 * The switch statement below detects the type of each option and builds the form fields.
 *
 * @todo split out each option into a unique function, drop functions into switch statement.
 */
function iwbc_admin_get_my_options() {
  global $mobiname, $shortname, $moboptions;
  $i=0;
  $moboptions = apply_filters('iwbc_options_list', $moboptions);
  foreach ($moboptions as $value) {
    switch ($value['type']) {
      case "open":
?>
<?php break;

case "title":
?>
<?php break;

case 'text':
?>
        <div class="rm_input rm_text">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>

        </div>
      
        <?php
        break;
		case 'range':
?>
        <div class="rm_input rm_range">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input class="range" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" min="<?php echo $value['min']?>" max="<?php echo $value['max']?>" type="range" step="<?php echo $value['step']?>" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>

        </div>
      
        <?php
        break;
		case 'colorpicker':
?>
        <div class="rm_input rm_text">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input class="color-picker-input" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <div class="colorpicker_show_color" style="background-color:#<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>">&nbsp;</div>
 
 <div class="clearfix"></div>

        </div>
      
        <?php
        break;
		case 'colorpicker-link':
?>
        <div class="rm_input rm_text">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input class="color-picker-input-link" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <div class="menu" style="background-color:#<?php echo get_option('mgu_back_menu'); ?>;"><a class="link" style="color:#<?php echo get_option('mgu_color_links'); ?>" href="#">menu</a></div><div class="clearfix"></div>

        </div>
      
        <?php
        break;
		case 'colorpicker-link-hover':
?>
        <div class="rm_input rm_text">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input class="color-picker-input-link-hover" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <div class="menu" style="background-color:#<?php echo get_option('mgu_back_menu'); ?>;"><a class="hover-link" style="color:#<?php echo get_option('mgu_color_hover_links'); ?>" href="#">menu</a></div><div class="clearfix"></div>

        </div>
      
        <?php
        break;
		case 'text-icon':
?>
        <div class="rm_input rm_text_icon">
      <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'])  ); } else { echo $value['std']; } ?>" /><div class="clearfix"></div>

        </div>
      
        <?php
        break;
		
      case 'select':
        ?>
       <div class="rm_input rm_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
        <?php
        break;
		case 'select-icon':
        ?>
<div class="select-icon-container">        
<div class="icon-holder"><a href="#"><img src="<?php echo IWBC_URI; ?>/social_icons/<?php echo get_option($value['id']); ?>" alt="" /></a></div>
       <div class="rm_input rm_select_icon">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	
</div>
<div class="clearfix"></div>
</div>
        <?php
        break;
		
		case 'tabins': ?>
		<ul class="tabins">
        	<?php foreach ($value['options'] as $option) { ?>
            	<li><a href="#"><?php echo $option; ?></a></li>
                <?php } ?>
        </ul>
		<?php break;	

		case 'open-pane': ?>
		<div class="panes"><div>
		<?php break;	

		case 'close-pane': ?>
		</div></div>
		<?php break;	

		case 'multi-select':
		?>
<div class="rm_input rm_multiple">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<select multiple="multiple" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_option( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
		<?php
        break;
		
      case 'textarea':
        ?>
        <div class="rm_input rm_textarea">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>

        <?php
        break;
      case 'checkbox':
           $checked = '';
           $val = get_option($value['id']);

            if (!empty($val)) {
              $checked = ($val == 'true' ? 'checked="checked"' : '');
            }
            elseif ($value['std'] == 'true') {
              $checked = 'checked="checked"';
            }
            else {
              $checked = '';
            }
        ?>
        <div class="rm_input rm_checkbox">
          <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
            <input id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" type="checkbox" value="true" <?php print $checked; ?> />
             <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
		 </div>
        <?php
        break;    
	  case 'radio':
        $val = get_option($value['id']);
        ?>
        <div class="rm_input rm_radio">
          <label><?php echo $value['name']; ?></label>
          
            <?php foreach ($value['options'] as $option) {
              $id = $option;
              $checked = '';

              if (!empty($val)) {
                $checked = ($val == $option ? 'checked="checked"' : '');
              }
              elseif ($value['std'] == $option) {
                $checked = 'checked="checked"';
              }
              else {
                $checked = '';
              }
            ?>
                <div class="radio-button">
                  <input id="<?php print $id; ?>" type="radio" name="<?php echo $value['id']; ?>" value="<?php print $option; ?>" <?php print $checked; ?> />
                  <label class="right" for="<?php print $id; ?>"><?php print $option; ?></label>
                </div>
            <?php } ?>
            <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
		 </div>
        <?php
        break;
      case 'upload':
        ?>
       <div class="rm_input rm_upload">
          <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
		  <div class='imgupload'>
            <?php print iwbc_get_upload_field($value['id'], $value['std'], $value['desc']); ?>
			</div>
            <small><?php echo $value['desc']; ?></small>
          <div class="clearfix"></div>
        </div>
        <?php
        break;
		case "section":
$i++;
?>
<div class="tab_content" id="tab<?php echo $i ?>">
<div class="rm_title" ><h3><?php echo $value['name']; ?></h3><div class="clearfix"></div></div>
<div class="rm_options">
<?php break;
case "sub-section":
?>
<div class="sub-section" id="<?php echo $value['id']; ?>">
<div class="subsection_title"><h3><?php echo $value['name']; ?></h3><div class="clearfix"></div></div>
<?php
break;
case "close-sub-section":
?>
<div class="clear"></div>
</div>
<?php 
break;
case "close":
?>
</div>
<span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" class="button-primary" />
</span>
</div>
<?php break;
    }
  }
}
?>