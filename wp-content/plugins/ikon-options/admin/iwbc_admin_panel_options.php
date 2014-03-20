<?php
$all_page_ids = get_all_page_ids();
$all_page_ids = implode(',',$all_page_ids);
$all_category_ids = get_all_category_ids();
$all_category_ids = implode(',',$all_category_ids);

//Access the WordPress Categories via an Array
$categories = get_categories('hide_empty=0&orderby=name');
$wp_cats = array();
foreach ($categories as $category_list ) {
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($wp_cats, " ");

//Access the WordPress Pages via an Array
$pages = get_pages('sort_column=post_parent,menu_order');
$wp_pages = array();
foreach ($pages as $page_list ) {
	$wp_pages[$page_list->ID] = $page_list->post_name;
}

// Fetch alternate theme stylesheets
$soc_icons_dir = IWBC_ABSPATH . 'social_icons';
$nm_social_icon = array();

if (is_dir($soc_icons_dir)) {
  $icons = scandir($soc_icons_dir);
  foreach ($icons as $icon) {
    if (strpos($icon, '.png')) {
      $nm_social_icon[] = $icon;
    }
  }
}
array_unshift($nm_social_icon, "Select an icon");

$bg_body_style = array('texture1','texture2','texture3','texture4','texture5','texture6','texture7','texture8','texture9','texture10','texture11','texture12','texture13','vintage1','vintage2','vintage3','vintage4','vintage5');
$nm_slider_effects = array('fade','scrollHorz','scrollVert','uncover','zoom','shuffle','none');


$moboptions = array(
array(
	"name" => $mobiname." Options",
	"type" => "title"),
	
array(
	"name" => "General Settings",
	"type" => "section"),
	
array(
	"type" => "open"),

array( 
	"name" => "Logo Image",
	"desc" => "Upload a logo to use for your site.",
	"id" => $shortname . "_logo",
	"std" => " ",
	"type" => "upload"
),	
array(
	"name" => "Favicon Image",
	"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
	"id" => $shortname . "_favicon",
	"std" => get_bloginfo('template_directory')."/images/mgu_icon.png",
	"type" => "upload"
),

array(
	"name" => "Apple Touch Icon",
	"desc" => "Upload a logo to use for your site.",
	"id" => $shortname . "_apple_touch",
	"std" => get_bloginfo('template_directory')."/images/ikolos_icon.png",
	"type" => "upload"
),

array(
	"name" => "Custom CSS",
	"desc" => "Add quicly your CSS to the mobile theme",
	"id" => $shortname . "_custom_css",
	"std" => "",
	"type" => "textarea"
),

array(
	"name" => "Google Analytics Code",
	"desc" => "Paste Your Google Analytics Code Here.",
	"id" => $shortname . "_googleanalytics",
	"std" => "",
	"type" => "textarea"
),

array(
	"name" => "Copyright Footer Text",
	"desc" => "Copyright text to display in footer",
	"id" => $shortname . "_footertext",
	"std" => "",
	"type" => "textarea"
),

array(
	"type" => "close"),


array(
	"name" => "General Typography",
	"type" => "section"),
array(
	"type" => "open"),

array (
	"name" => "Body text size",
	"desc" => "Font size for body text.",
	"id" => $shortname."_general_text",
	"std" => "12",
	"min" => "0",
	"max" => "100",
	"step" => "1",
	"type" => "range"
		),

array(
    "name" => "H 1",
    "desc" => "Font size for Heading 1",
    "id" => $shortname . "_size_heading_one",
    "std" => "22",
	"min" => "22",
	"max" => "100",
	"step" => "1",
    "type" => "range"
  ),  

array(
    "name" => "H 2",
    "desc" => "Font size for Heading 2",
    "id" => $shortname . "_size_heading_two",
    "std" => "18",
	"min" => "18",
	"max" => "100",
	"step" => "1",
    "type" => "range"
  ),  

array(
    "name" => "H 3",
    "desc" => "Font size for Heading 3",
    "id" => $shortname . "_size_heading_three",
    "std" => "16",
	"min" => "16",
	"max" => "100",
	"step" => "1",
    "type" => "range"
  ),  
  
 array(
    "name" => "H 4",
    "desc" => "Font size for Heading 4",
    "id" => $shortname . "_size_heading_four",
    "std" => "12",
	"min" => "12",
	"max" => "100",
	"step" => "1",
    "type" => "range"
  ),  
 
 array(
    "name" => "H 5",
    "desc" => "Font size for Heading 5",
    "id" => $shortname . "_size_heading_five",
    "std" => "12",
	"min" => "12",
	"max" => "100",
	"step" => "1",
    "type" => "range"
  ),    
 		

array(
	"type" => "close"),

array(
	"name" => "Background and Color Schemes",
	"type" => "section"),
array(
	"type" => "open"),

array(
	"name" => "White/Black Color Scheme",
	"desc" => "Please, select one of the color schemes",
	"id" => $shortname."_color_scheme",
	"type" => "radio",
	"std" => "white",
	"options" => array ('white','black'),
	),

array( 
	"name" => "Preinstalled backgrounds:",
	"desc" => "Select a background for theme",
	"id" => $shortname."_bg_body",
	"type" => "select",
	"options" => $bg_body_style,
	"std" => ""),

array( 
	"name" => "Custom background color:",
	"desc" => "Define a backround color",
	"id" => $shortname."_custom_color_body",
	"type" => "colorpicker",
	"std" => ""),

array( 
	"name" => "Custom background image:",
	"desc" => "Upload an image for background (will overwrite the preinstalled background)",
	"id" => $shortname."_custom_bg_body",
	"type" => "upload",
	"std" => ""),

array(
	"name" => "Repeat",
	"desc" => "Select an option for repeat header image",
	"id" => $shortname . "_back_repeat",
	"std" => "no-repeat",
	"type" => "radio",
	"options" => array ('no-repeat','repeat','repeat-x','repeat-y'),
  ),

array(
	"name" => "Position",
	"desc" => "Select a position for image",
	"id" => $shortname . "_back_x",
	"std" => "left",
	"type" => "radio",
	"options" => array ('left','center','right'),
  ),  

array(
	"type" => "close"),


array (
	"name" => "Header Section",
	"type" => "section"
	),

array(
	"type" => "open"),	

array(
	"name" => "Enable/Disable Phone icon",
	"desc" => "Check if you want to show Phone icon in header",
	"id" => $shortname."_header_phone",
	"type" => "checkbox",
	"std" => "true"),

array(
	"name" => "Phone number",
	"desc" => "Type the phone number (Iphone users will be able to call pressing on the Phone icon)",
	"id" => $shortname."_header_phone_numb",
	"type" => "text",
	"std" => " "),



array(
	"name" => "Enable/Disable Rss icon",
	"desc" => "Check if you want to show Rss icon in header",
	"id" => $shortname."_header_rss",
	"type" => "checkbox",
	"std" => "true"),


array(
	"name" => "Enable/Disable Search in header",
	"desc" => "Check if you want to show search field in header",
	"id" => $shortname."_show_search",
	"type" => "checkbox",
	"std" => "true"),


array(
	"name" => "Enable/Disable About icon",
	"desc" => "Check if you want to show About icon in header",
	"id" => $shortname."_header_about",
	"type" => "checkbox",
	"std" => "true"),
  

array(
	"name" => "Section About",
	"desc" => "Insert some description about your site (Html tags allowed)",
	"id" => $shortname . "_about_site",
	"std" => "",
	"type" => "textarea"
),  		
	

array(
	"name" => "Social icons in header",
	"type" => "sub-section",
	"id" => "social-icons",
	),

array(
	"name" => "Enable/Disable Social network icons",
	"desc" => "Check if you want to show social icons in header",
	"id" => $shortname."_show_social_net",
	"type" => "checkbox",
	"std" => "true"),
	
array(
	 "name" => "Icon 1",
	 "desc" => " ",
	 "id" => $shortname."_social_one",
	 "type" => "select-icon",
	 "options" => $nm_social_icon,
	 "std" => "Select an icon"),

array( 
	"name" => "URL 1",
	"desc" => " ",
	"id" => $shortname."_social_one_url",
	"type" => "text-icon",
	"std" => ""),

array(
	 "name" => "Icon 2",
	 "desc" => " ",
	 "id" => $shortname."_social_two",
	 "type" => "select-icon",
	 "options" => $nm_social_icon,
	 "std" => "Select an icon"),

array( 
	"name" => "URL 2",
	"desc" => " ",
	"id" => $shortname."_social_two_url",
	"type" => "text-icon",
	"std" => ""),

array( 
	"name" => "Icon 3",
	"desc" => " ",
	"id" => $shortname."_social_three",
	"type" => "select-icon",
	"options" => $nm_social_icon,
	"std" => "Select an icon"),

array( 
	"name" => "URL 3",
	"desc" => " ",
	"id" => $shortname."_social_three_url",
	"type" => "text-icon",
	"std" => ""),

array( 
	"name" => "Icon 4",
	"desc" => " ",
	"id" => $shortname."_social_four",
	"type" => "select-icon",
	"options" => $nm_social_icon,
	"std" => "Select an icon"),

array( 
	"name" => "URL 4",
	"desc" => " ",
	"id" => $shortname."_social_four_url",
	"type" => "text-icon",
	"std" => ""),

array( 
	"name" => "Icon 5",
	"desc" => " ",
	"id" => $shortname."_social_five",
	"type" => "select-icon",
	"options" => $nm_social_icon,
	"std" => "Select an icon"),

array( 
	"name" => "URL 5",
	"desc" => " ",
	"id" => $shortname."_social_five_url",
	"type" => "text-icon",
	"std" => ""),

array( 
	"name" => "Icon 6",
	"desc" => " ",
	"id" => $shortname."_social_six",
	"type" => "select-icon",
	"options" => $nm_social_icon,
	"std" => "Select an icon"),

array( 
	"name" => "URL 6",
	"desc" => " ",
	"id" => $shortname."_social_six_url",
	"type" => "text-icon",
	"std" => ""),


array (
	"type" => "close-sub-section",	
	),

array(
	"type" => "close"),

array(
	"name" => "Slider Options",
	"type" => "section"),

array(
	"type" => "open"),


array(
	"name" => "Enable / Disable Slider on homepage",
	"desc" => "Check if want to show slider on homepage",
	"id" => $shortname."_show_slider",
	"type" => "checkbox",
	"std" => "true"),
	

array( "name" => "Slider content",
	"desc" => "Select what type of the content you want to display in slider",
	"id" => $shortname."_slider_con",
	"type" => "select",
	"options" => array('Posts', 'Pages'),
	"std" => " "),

array( "name" => "Select slider category",
	"desc" => "<strong>If posts:</strong>Please select a category to populate the slider content (if empty will display recent posts).",
	"id" => $shortname."_slider_cat",
	"type" => "select",
	"options" => $wp_cats,
	"std" => " "),


array(
	"name" => "Effect for slider",
	"desc" => "Please select an effect for you slider",
	"id" => $shortname."_slider_fx",
	"std" => "",
	"type" => "select",
	"options" => $nm_slider_effects,
  ),


array(
    "name" => "Timer for slider",
    "desc" => "Please select how many seconds each item is displayed (0 = no auto slide).",
    "id" => $shortname . "_slider_timer",
    "std" => "1200",
	"min" => "0",
	"max" => "10000",
	"step" => "200",
    "type" => "range"
  ),  


array(
    "name" => "Featured Items",
    "desc" => "Please select how many featured items you want to display in slider",
    "id" => $shortname . "_slider_items",
    "std" => "6",
	"min" => "0",
	"max" => "20",
	"step" => "2",
    "type" => "range"
  ),  
		
array(
	"type" => "close"),

array (
	"name" => "Homepage",
	"type" => "section"
	),

array(
	"type" => "open"),	
		
array(
	"name" => "Front page displays",
	"desc" => "Check what you want to show on front page",
	"id" => $shortname . "_front_page",
	"std" => "Posts",
	"type" => "radio",
	"options" => array ('Posts','Page'),
  ),

array( 
	"name" => "Homepage Content",
	"desc" => "<strong>If Page</strong>:Select a page for frontpage",
	"id" => $shortname."_home_page",
	"type" => "select",
	"options" => $wp_pages,
	"std" => ""),  

array( 
	"name" => "Category of the Posts",
	"desc" => "<strong>If Posts</strong>:Select a category (if empty will display recent posts)",
	"id" => $shortname."_home_cat",
	"type" => "select",
	"options" => $wp_cats,
	"std" => ""),  


array(
	"type" => "close"),


array(
	"name" => "Blog Options",
	"type" => "section"),

array(
	"type" => "open"),

array(
	"name" => "Author bio",
	"desc" => "Check to display author bio in the post",
	"id" => $shortname."_author_info",
	"type" => "checkbox",
	"std" => "true"),

array(
	"name" => "Social Networks in post",
	"desc" => "Check to display social to share post",
	"id" => $shortname."_show_socials",
	"type" => "checkbox",
	"std" => "true"),
	
array(
	"name" => "Show Comments",
	"desc" => "Check to display comments",
	"id" => $shortname."_post_comments",
	"type" => "checkbox",
	"std" => "true"),
	
	
array(
	"type" => "close"),


array( 
	"name" => "Contact Form setup",
	"type" => "section"),

array( 
	"type" => "open"),

array( 
	"name" => "E-mail for contact form",
	"desc" => "",
	"id" => $shortname."_email_account",
	"type" => "text",
	"std" => ""),
	
array( 
	"name" => "The message after the email was send",
	"desc" => "Type your message that the user will see after the message was send",
	"id" => $shortname."_message",
	"type" => "textarea",
	"std" => "Your email was successfully sent. I will be in touch soon."),
		
array( 
	"type" => "close")
	
	);
?>