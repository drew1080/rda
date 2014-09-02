<?php

//SHORTCODES

function full_width_content_shortcode( $atts, $content = null ) {
	return '<div class="full-width-content">' . $content . '</span>';
}
add_shortcode( 'full_width_content', 'full_width_content_shortcode' );

//Custom Theme Settings
add_action('admin_menu', 'add_gcf_interface');

function add_gcf_interface() {
	add_options_page('Global Custom Fields', 'Global Custom Fields', '8', 'functions', 'editglobalcustomfields');
}

function editglobalcustomfields() {
	?>
	<div class='wrap'>
	<h2>Global Custom Fields</h2>
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options') ?>

	<p><strong>Middle Footer</strong><br />
	<textarea class="form-control" rows="4" cols="50" name="rda_middle_footer" id="middle-footer" placeholder="Enter content for the middle of the footer"><?php echo get_option('rda_middle_footer') ? get_option('rda_middle_footer') : ""; ?></textarea></p>

	<p><input type="submit" name="Submit" value="Update Options" /></p>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="rda_middle_footer" />

	</form>
	</div>
	<?php
}


// Add, ADD USER Capacility for Editors
// function add_theme_caps() {
//   // let editor manage users
//   $edit_editor = get_role('editor');
//   $edit_editor->add_cap('edit_user');
//   // 
//   // $edit_editor = get_role('editor'); // Get the user role
//   // $edit_editor->add_cap('list_users'); 
//   // $edit_editor->add_cap('create_users');
//   // $edit_editor->add_cap('delete_users');
// }
// add_action( 'admin_init', 'add_theme_caps');
// 
function mc_admin_users_caps( $caps, $cap, $user_id, $args ){

  foreach( $caps as $key => $capability ){

    if( $capability != 'do_not_allow' )
      continue;

    switch( $cap ) {
      case 'edit_user':
      case 'edit_users':
        $caps[$key] = 'edit_users';
        break;
      case 'delete_user':
      case 'delete_users':
        $caps[$key] = 'delete_users';
        break;
      case 'create_users':
        $caps[$key] = $cap;
        break;
    }
  }

  return $caps;
}
add_filter( 'map_meta_cap', 'mc_admin_users_caps', 1, 4 );
remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');

/**
 * Checks that both the editing user and the user being edited are
 * members of the blog and prevents the super admin being edited.
 */
function mc_edit_permission_check() {
  global $current_user, $profileuser;

  $screen = get_current_screen();

  get_currentuserinfo();

  if( $screen->base == 'user-edit' || $screen->base == 'user-edit-network' ) { // editing a user profile
    if ( ! is_super_admin( $current_user->ID ) && is_super_admin( $profileuser->ID ) ) { // trying to edit a superadmin while less than a superadmin
      wp_die( __( 'You do not have permission to edit this user.' ) );
    } elseif ( ! ( is_user_member_of_blog( $profileuser->ID, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
      wp_die( __( 'You do not have permission to edit this user.' ) );
    }
  }

}
add_filter( 'admin_head', 'mc_edit_permission_check', 1, 4 );

// PREVENT EDITORS FROM ADDING ADMIN's
class JPB_User_Caps {

  // Add our filters
  function JPB_User_Caps(){
    add_filter( 'editable_roles', array(&$this, 'editable_roles'));
    add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
  }

  // Remove 'Administrator' from the list of roles if the current user is not an admin
  function editable_roles( $roles ){
    if( isset( $roles['administrator'] ) && !current_user_can('administrator') ){
      unset( $roles['administrator']);
    }
    return $roles;
  }

  // If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
  function map_meta_cap( $caps, $cap, $user_id, $args ){

    switch( $cap ){
        case 'edit_user':
        case 'remove_user':
        case 'promote_user':
            if( isset($args[0]) && $args[0] == $user_id )
                break;
            elseif( !isset($args[0]) )
                $caps[] = 'do_not_allow';
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        case 'delete_user':
        case 'delete_users':
            if( !isset($args[0]) )
                break;
            $other = new WP_User( absint($args[0]) );
            if( $other->has_cap( 'administrator' ) ){
                if(!current_user_can('administrator')){
                    $caps[] = 'do_not_allow';
                }
            }
            break;
        default:
            break;
    }
    return $caps;
  }

}

$jpb_user_caps = new JPB_User_Caps();


function fruitful_entry_meta_child() {
?>
	<span class="author-link"><a href="<?php print esc_url( get_author_posts_url( get_the_author_meta( 'ID' ))); ?>"><?php print get_the_author(); ?></a></span>
	<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
	<?php
		/* translators: used between list items, there is a space after the comma */
		 $categories_list = get_the_category_list( __( ', ', 'fruitful' ) );
	if ( $categories_list && fruitful_categorized_blog() ) : ?>
		<span class="cat-links">
			<?php printf( __( 'Posted in %1$s', 'fruitful' ), $categories_list ); ?>
		</span>
	<?php endif; // End if categories ?>

	<?php
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'fruitful' ) );
		if ( $tags_list ) :
	?>
		<span class="tag-links">
			<?php printf( __( 'Tagged %1$s', 'fruitful' ), $tags_list ); ?>
		</span>
		<?php endif; // End if $tags_list ?>
  <div class="clear"></div>
  <span class="posted-date">Published on <?php the_date(); ?></span>
	<?php endif; // End if 'post' == get_post_type() ?>
	<?php //edit_post_link( __( 'Edit', 'fruitful' ), '<span class="edit-link">', '</span>' ); ?>
<?php
}

// $edit_editor = get_role('editor'); // Get the user role
// $edit_editor->add_cap('list_users'); 
// $edit_editor->add_cap('create_users');
// $edit_editor->add_cap('delete_users');

 // echo "ADMIN:" . is_super_admin(36);
 // echo "GRANT:" . grant_super_admin(36);
 // $super_admins = get_site_option( 'site_admins' );
 // print_r($super_admins);

// OPTIONAL: Can be used to create metaboxes on the edit post page.
// function ep_eventposts_metaboxes() {
//     add_meta_box( 'ept_event_date_start', 'Start Date and Time', 'ept_event_date', 'post', 'side', 'default', array( 'id' => '_start') );
//     add_meta_box( 'ept_event_date_end', 'End Date and Time', 'ept_event_date', 'post', 'side', 'default', array('id'=>'_end') );
//     add_meta_box( 'ept_event_location', 'Event Location', 'ept_event_location', 'post', 'side', 'default', array('id'=>'_end') );
// }
// add_action( 'admin_init', 'ep_eventposts_metaboxes' );
// // Metabox HTML
// function ept_event_date($post, $args) {
//     $metabox_id = $args['args']['id'];
//     global $post, $wp_locale;
//     // Use nonce for verification
//     wp_nonce_field( plugin_basename( __FILE__ ), 'ep_eventposts_nonce' );
//     $time_adj = current_time( 'timestamp' );
//     $month = get_post_meta( $post->ID, $metabox_id . '_month', true );
//     if ( empty( $month ) ) {
//         $month = gmdate( 'm', $time_adj );
//     }
//     $day = get_post_meta( $post->ID, $metabox_id . '_day', true );
//     if ( empty( $day ) ) {
//         $day = gmdate( 'd', $time_adj );
//     }
//     $year = get_post_meta( $post->ID, $metabox_id . '_year', true );
//     if ( empty( $year ) ) {
//         $year = gmdate( 'Y', $time_adj );
//     }
//     $hour = get_post_meta($post->ID, $metabox_id . '_hour', true);
//     if ( empty($hour) ) {
//         $hour = gmdate( 'H', $time_adj );
//     }
//     $min = get_post_meta($post->ID, $metabox_id . '_minute', true);
//     if ( empty($min) ) {
//         $min = '00';
//     }
//     $month_s = '<select name="' . $metabox_id . '_month">';
//     for ( $i = 1; $i < 13; $i = $i +1 ) {
//         $month_s .= "\t\t\t" . '<option value="' . zeroise( $i, 2 ) . '"';
//         if ( $i == $month )
//             $month_s .= ' selected="selected"';
//         $month_s .= '>' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
//     }
//     $month_s .= '</select>';
//     echo $month_s;
//     echo '<input type="text" name="' . $metabox_id . '_day" value="' . $day  . '" size="2" maxlength="2" />';
//     echo '<input type="text" name="' . $metabox_id . '_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
//     echo '<input type="text" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
//     echo '<input type="text" name="' . $metabox_id . '_minute" value="' . $min . '" size="2" maxlength="2" />';
// }
// function ept_event_location() {
//     global $post;
//     // Use nonce for verification
//     wp_nonce_field( plugin_basename( __FILE__ ), 'ep_eventposts_nonce' );
//     // The metabox HTML
//     $event_location = get_post_meta( $post->ID, '_event_location', true );
//     echo '<label for="_event_location">Location:</label>';
//     echo '<input type="text" name="_event_location" value="' . $event_location  . '" />';
// }
// // Save the Metabox Data
// function ep_eventposts_save_meta( $post_id, $post ) {
//     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
//         return;
//     if ( !isset( $_POST['ep_eventposts_nonce'] ) )
//         return;
//     if ( !wp_verify_nonce( $_POST['ep_eventposts_nonce'], plugin_basename( __FILE__ ) ) )
//         return;
//     // Is the user allowed to edit the post or page?
//     if ( !current_user_can( 'edit_post', $post->ID ) )
//         return;
//     // OK, we're authenticated: we need to find and save the data
//     // We'll put it into an array to make it easier to loop though
//     $metabox_ids = array( '_start', '_end' );
//     foreach ($metabox_ids as $key ) {
//         $events_meta[$key . '_month'] = $_POST[$key . '_month'];
//         $events_meta[$key . '_day'] = $_POST[$key . '_day'];
//             if($_POST[$key . '_hour']<10){
//                  $events_meta[$key . '_hour'] = '0'.$_POST[$key . '_hour'];
//              } else {
//                    $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
//              }
//         $events_meta[$key . '_year'] = $_POST[$key . '_year'];
//         $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
//         $events_meta[$key . '_minute'] = $_POST[$key . '_minute'];
//         $events_meta[$key . '_eventtimestamp'] = $events_meta[$key . '_year'] . $events_meta[$key . '_month'] . $events_meta[$key . '_day'] . $events_meta[$key . '_hour'] . $events_meta[$key . '_minute'];
//     }
//     // Add values of $events_meta as custom fields
//     foreach ( $events_meta as $key => $value ) { // Cycle through the $events_meta array!
//         if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
//         $value = implode( ',', (array)$value ); // If $value is an array, make it a CSV (unlikely)
//         if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
//             update_post_meta( $post->ID, $key, $value );
//         } else { // If the custom field doesn't have a value
//             add_post_meta( $post->ID, $key, $value );
//         }
//         if ( !$value ) delete_post_meta( $post->ID, $key ); // Delete if blank
//     }
// }
// add_action( 'save_post', 'ep_eventposts_save_meta', 1, 2 );
// /**
//  * Helpers to display the date on the front end
//  */
// // Get the Month Abbreviation
// function eventposttype_get_the_month_abbr($month) {
//     global $wp_locale;
//     for ( $i = 1; $i < 13; $i = $i +1 ) {
//                 if ( $i == $month )
//                     $monthabbr = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
//                 }
//     return $monthabbr;
// }
// // Display the date
// function eventposttype_get_the_event_date() {
//     global $post;
//     $eventdate = '';
//     $month = get_post_meta($post->ID, '_month', true);
//     $eventdate = eventposttype_get_the_month_abbr($month);
//     $eventdate .= ' ' . get_post_meta($post->ID, '_day', true) . ',';
//     $eventdate .= ' ' . get_post_meta($post->ID, '_year', true);
//     $eventdate .= ' at ' . get_post_meta($post->ID, '_hour', true);
//     $eventdate .= ':' . get_post_meta($post->ID, '_minute', true);
//     echo $eventdate;
// }