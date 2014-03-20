<?php /*
Plugin Name: WP Post Thumbnail
Plugin URI: http://www.seoadsensethemes.com/wp-post-thumbnail-wordpress-plugin/
Description: WP Post Thumbnail enable bloggers to crop and save post thumbnails without manually copy-n-paste custom field values.
Version: 0.2 Beta 2
Author: Stanley Yeoh
Author URI: http://www.seoadsensethemes.com/wordpress-wp-post-thumbnail-plugin/

Copyright (C) 2008 Stanley Yeoh

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/> */





if ( !function_exists('json_encode') )
	require_once( dirname(__FILE__) . '/includes/json.php' );

if ( !defined('WPPT_VERSION') ) define( 'WPPT_VERSION', 0.2 );

/**
* Define wp-content and plugin urls/paths
*/
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );





if ( !class_exists( "Wppt" ) ) {

	class Wppt {

		/**
		* @var string
		*/
		var $wppt_options;

		/**
		* @var string
		*/ 
		var $wppt_preset;

		/**
		* @var string
		*/ 
		var $wppt_db_table;

		/**
		* @var string
		*/
		var $wppt_thumbnail_db_table;

		/**
		* @var array
		*/
		var $wppt_error_msgs;





		/**
		* Class constructor
		*/
		function __construct() {
			global $wpdb;

			/* initiate variables */
			$this->wppt_db = $wpdb->prefix . "wppt";
			$this->wppt_preset_db = $wpdb->prefix . "wppt_preset";
			$this->wppt_default_jpeg_quality = 88;
			$this->wppt_error_msgs = array( "smallSourceWarningMsg" =>
				__("The source image is smaller than thumbnail's preset width or height", "Wppt") . ".\n\n"
				.__("Therefore, the thumbnail generated from this image will contain enlarged pixels", "Wppt")  . ".\n\n"
				.__("No minimum crop size restrain will be set","Wppt") . "." );

			/* Language localization */
			load_plugin_textdomain('Wppt',
				'wp-content/plugins/wp-post-thumbnail/languages', 'wp-post-thumbnail/languages');

			register_activation_hook( __FILE__, array(&$this, 'install') );

			add_action('init', array(&$this, 'init') );

			if ( is_admin() ) {
				add_action( 'admin_print_styles', array(&$this, 'admin_header_css') );
				add_action( 'admin_print_scripts', array(&$this, 'admin_header_js') );
			}

			add_action( 'admin_menu', array(&$this, 'add_form_template') );
			add_action( 'admin_menu', array(&$this, 'add_options_template') );

			/* add AJAX actions */
			add_action( 'wp_ajax_list_thumbs', array(&$this, 'list_thumbs') );
			add_action( 'wp_ajax_get_src_image_attachment', array(&$this, 'get_src_image_attachment') );
			add_action( 'wp_ajax_save_options', array(&$this, 'save_options') );
			add_action( 'wp_ajax_buildThumbTabs', array(&$this, 'buildThumbTabs') );
			add_action( 'wp_ajax_getSelectedThumb', array(&$this, 'getSelectedThumb') );
			add_action( 'wp_ajax_saveThumbnail', array(&$this, 'saveThumbnail') );
			add_action( 'wp_ajax_fullDeleteThumbnail', array(&$this, 'fullDeleteThumbnail') );
			add_action( 'wp_ajax_saveThumbnailFilename', array(&$this, 'saveThumbnailFilename') );
			add_action( 'wp_ajax_saveThumbnailAlt', array(&$this, 'saveThumbnailAlt') );
			add_action( 'wp_ajax_saveThumbnailTitle', array(&$this, 'saveThumbnailTitle') );
			add_action( 'wp_ajax_saveThumbnailPlacement', array(&$this, 'saveThumbnailPlacement') );
			add_action( 'wp_ajax_saveThumbCustomKey', array(&$this, 'saveThumbCustomKey') );
			add_action( 'wp_ajax_saveThumbSize', array(&$this, 'saveThumbSize') );

			add_action( 'wp_head', array(&$this, 'wppt_header_css') );
			add_filter( 'the_content', array(&$this, 'insert_default_thumbnail') );
		}





		/**
		* Add wp-post-thumbnail css to page header
		*/
		function wppt_header_css() {
			$cssURL = WP_PLUGIN_URL . '/wp-post-thumbnail/css'; ?>
			<link rel="stylesheet" href="<?php echo $cssURL; ?>/wppt.css" type="text/css" media="all" />
		<?php }





		/**
		* Insert default thumbnail to post
		*/
		function insert_default_thumbnail( $content ) {
			if ( $this->wppt_options['default_thumbnail'] == -1 ) return $content;

			if ( is_single() ) return $content;

			global $post;
			$preset_ID = $this->wppt_options['default_thumbnail'];
			$row = $this->getRecordRow( $post->ID, $preset_ID );
			if ( !$row ) return $content;

			list( $w, $h, $type ) = getimagesize( $this->url_to_path( $row->wppt_guid ) );

			switch( intval( $row->wppt_placement) ) {
				case 0: $placement = "wppt_float_left"; break;
				case 1: $placement = "wppt_float_right"; break;
				case 2: $placement = "wppt_center"; break;
			}

			return sprintf( '<a href="%s" title="Link to %s"><img class="%s" src="%s" alt="%s" title="%s" width="%s" height="%s" /></a>',
				get_permalink($post->ID), $post->post_title, $placement, $row->wppt_guid,
				$row->wppt_alt, $row->wppt_title, $w, $h ) . $content;
		}





		/**
		* Get post thumbnail information
		*/
		function get_post_thumbnail( $post_ID, $custom_key ) {
			foreach( $this->wppt_preset as $preset )
				if ( $custom_key == $preset->wppt_preset_meta_key ) {
					$row = $this->getRecordRow( $post_ID, $preset->wppt_preset_id );
					if ( !empty( $row ) ) {
						list( $w, $h, $type ) = getimagesize( $this->url_to_path( $row->wppt_guid ) );
						return array( 'url' => $row->wppt_guid, 'alt' => $row->wppt_alt,
							'title' => $row->wppt_title, 'width' => $w, 'height' => $h );
					}
				}

			return false;
		}





		/**
		* Add wp-post-thumbnail admin css to page header
		*/
		function admin_header_css() {
			global $pagenow;
			
			if ( $pagenow == 'post.php' || $pagenow == 'options-general.php' || $pagenow == 'post-new.php' ) {
				wp_register_style( 'wppt-admin-css', WP_PLUGIN_URL . '/wp-post-thumbnail/css/wppt_admin.css' );
				wp_enqueue_style( 'wppt-admin-css');
			}

			if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				wp_register_style( 'wppt_jcrop_css', WP_PLUGIN_URL . '/wp-post-thumbnail/css/jquery.Jcrop.css' );
				wp_enqueue_style( 'wppt_jcrop_css');
			}
		}





		/**
		* Add wp-post-thumbnail javascripts to page header
		*/
		function admin_header_js() {
			global $pagenow;
			$jsURL = WP_PLUGIN_URL . '/wp-post-thumbnail/js';

			if ( $pagenow == 'post.php' || $pagenow == 'options-general.php' || $pagenow == 'post-new.php' ) {
				wp_enqueue_script( 'wppt-json2', $jsURL . '/json2.min.js' );
				wp_enqueue_script( 'wppt-js', $jsURL . '/wppt.js', array( 'jquery' ), '0.2' );
			}
			
			if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
				wp_enqueue_script( 'wppt-jcrop', $jsURL . '/jquery.Jcrop.min.js', array('jquery'), '0.9.8' );
			}
		}





		/**
		* Returns default upload path where thumbnails are stored:
		* '/public_html/myblog/wp-content/uploads/wp-post-thumbnail'
		* is the default directory if you have not changed it.
		*
		* @return array
		*/
		function getUploadPath() {
			/* Append "/wp-post-thumbnail" to the default upload path. This is the directory
			where all thumbnails generated by WP Post Thumbnail plugin will be stored */
			return ABSPATH . str_replace( ABSPATH, '', trim( get_option( 'upload_path' ) ) ) . '/wp-content/uploads/wp-post-thumbnail';
		}





		/**
		* Convert a thumbnail's URL to absolute file path
		*
		* @return string
		*/
		function url_to_path( $url ) {
			return $this->getUploadPath() . '/' . basename( $url );
		}





		/**
		* Convert a file's absolute path to URL location
		*
		* @return string
		*/
		function path_to_url( $path ) {
			return get_option('siteurl') . '/' . trim( str_replace( ABSPATH, '', $path ), '/' );
		}





		/**
		* Create 'wp-post-thumbnail' directory within default upload directory
		* if not found.
		*
		* Create plugin's database tables.
		*/
		function install() {
			/* Get this plugin's default upload directory. Default is
			"/wp-content/uploads/wp-post-thumbnail/" */
			$upload_dir = $this->getUploadPath();

			/* Make '/wp-content/uploads/wp-post-thumbnail/' if not found */
			if ( !file_exists( $upload_dir ) ) {
				if ( wp_mkdir_p( $upload_dir ) ) { chmod( $upload_dir, 0777 ); }
				else { wp_die( sprintf ( __("Cannot create '%s' directory","Wppt"), $upload_dir ) ); }
			}

			if ( file_exists( $upload_dir ) ) { $this->createDB(); }
		}





		/**
		* Create 'wp-post-thumbnail' database tables if not exists.
		* Run when plugin is activated
		*/
		function createDB() {
			global $wpdb;

			$create_wppt_table_sql = "CREATE TABLE IF NOT EXISTS $this->wppt_db ( " .
					"`wppt_id` bigint(20) NOT NULL auto_increment, " .
					"`wppt_post_id` bigint(20) NOT NULL default '0', " .
					"`wppt_preset_id` text NOT NULL, " .
					"`wppt_meta_id` bigint(20) NOT NULL default '0', " .
					"`wppt_guid` text NOT NULL, " .
					"`wppt_src_id` bigint(20) default '0', " .
					"`wppt_x` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_y` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_x2` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_y2` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_orig_x` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_orig_y` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_orig_x2` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_orig_y2` SMALLINT UNSIGNED NOT NULL default '0', " .
					"`wppt_placement` TINYINT UNSIGNED NOT NULL default '0', " .
					"`wppt_alt` text, " .
					"`wppt_title` text, " .
					"PRIMARY KEY (`wppt_id`) " . ");";

			$create_wppt_thumbnail_table_sql =
					"CREATE TABLE IF NOT EXISTS $this->wppt_preset_db ( " .
					"`wppt_preset_id` SMALLINT NOT NULL, " .
					"`wppt_preset_name` text NOT NULL, " .
					"`wppt_preset_desc` text, " .
					"`wppt_preset_width` SMALLINT UNSIGNED NOT NULL default '200', " .
					"`wppt_preset_height` SMALLINT UNSIGNED NOT NULL default '150', " .
					"`wppt_preset_meta_key` text NOT NULL, " .
					"PRIMARY KEY (`wppt_preset_id`) " . ");";

			require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );
			$wpdb->query("DROP TABLE IF EXISTS $this->wppt_db, $this->wppt_preset_db;");
			dbDelta( $create_wppt_table_sql );
			dbDelta( $create_wppt_thumbnail_table_sql );

			$wpdb->query( "INSERT INTO $this->wppt_preset_db
				( wppt_preset_id, wppt_preset_name, wppt_preset_desc, wppt_preset_width,
					wppt_preset_height, wppt_preset_meta_key ) VALUES
				( $wpdb->escape('0'), $wpdb->escape('preset1'), $wpdb->escape('preset1 description'), $wpdb->escape('200'),
					$wpdb->escape('150'), $wpdb->escape('wppt_preset1') ),
				( $wpdb->escape('1'), $wpdb->escape('preset2'), $wpdb->escape('preset2 description'), $wpdb->escape('220'),
					$wpdb->escape('120'), $wpdb->escape('wppt_preset2') ),
				( $wpdb->escape('2'), $wpdb->escape('preset3'), $wpdb->escape('preset3 description'), $wpdb->escape('125'),
					$wpdb->escape('125'), $wpdb->escape('wppt_preset3') )" );
		}





		/**
		* Get presets from "wppt_preset" database table
		*
		* @return array
		*/
		function getPreset() {
			global $wpdb;
			return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->wppt_preset_db" ) );
		}





		/**
		* Initialise plugin. Read or create options if not found.
		*/
		function init() {
			global $wpdb;

			/* Load wppt_options. Generate default values if not found */
			$this->wppt_options = get_option( 'wppt_options' );

			if ( empty( $this->wppt_options ) ) {
				$this->wppt_options = array();
				$this->wppt_options['default_jpeg_quality'] = 88;
				$this->wppt_options['default_thumbnail'] = -1;
				update_option( 'wppt_options', $this->wppt_options, 'WP Post Thumbnail options' );
			}

			/* Retrieve thumbnails configuration from database */
			$this->wppt_preset = $this->getPreset();
		}





		/**
		* Add template to plugin's options page
		*/
		function add_options_template() {
			if ( function_exists( 'add_options_page' ) ) {
				add_options_page( __( 'WP-Post-Thumbnail', 'Wppt' ),
					__( 'WP-Post-Thumbnail', 'Wppt' ), 'manage_options',
					__FILE__, array( &$this, 'options_template' ) );
			}
		}





		/**
		* Options page template
		*/
		function options_template() { ?>
			<div class="wrap wp-post-thumbnail-options-page">
				<?php screen_icon(); ?>
				<h2><?php _e('WP Post Thumbnail options', 'Wppt'); ?></h2>
				<p><?php _e('WP Post Thumbnail enable bloggers to crop and save post thumbnails without manually copy image URLs and paste into custom field values. Configure WP Post Thumbnail settings below.', 'Wppt'); ?></p>

				<form method="post" onsubmit="return false;">
					<h3><?php _e('Common settings', 'Wppt'); ?></h3>
					<fieldset class="wppt-settings-fieldset">
					<table class="form-table">

						<tr>
							<th scope="row" valign="top"><?php _e('JPEG quality parameter', 'Wppt'); ?></th>
							<td>
	
							<select name="wppt-jpeg-quality" id="wppt-jpeg-quality-option">
							<?php for ( $i=100; $i>=40; $i-- ) {
								echo '<option value="' . $i .'" ';
								if ( $i == $this->wppt_options['default_jpeg_quality'] ) echo 'selected ';
								echo '>&nbsp;' . $i . '&nbsp;</option>'."\n";
							} ?>
							</select>

							<span class="setting-description">&nbsp;<?php _e('The higher the number, the better the quality of JPEG generated for your thumbnails (40 - 100)', 'Wppt'); ?>.</span></td>
						</tr>

						<tr>
							<th scope="row" valign="top"><?php _e('Default thumbnail', 'Wppt'); ?></th>
							<td>

							<select name="wppt-default-thumbnail" id="wppt-default-thumbnail">
								<option value="-1">&nbsp;No default&nbsp;&nbsp;</option>
								<?php for ( $i = 0; $i < sizeOf( $this->wppt_preset ); $i++ ) {
									echo '<option value="' . $i .'" ';
									if ( $i == $this->wppt_options['default_thumbnail'] ) echo 'selected ';
									echo '>&nbsp;' . $this->wppt_preset[$i]->wppt_preset_name . '&nbsp;&nbsp;</option>'."\n";
								} ?>
							</select>

							<span class="setting-description">&nbsp;<?php _e('For themes that does not use thumbnails, eg. WordPress\'s default Kubrick theme', 'Wppt'); ?>.</span></td>
						</tr>

					</table><br />
					<br />
					<input id="wp-post-thumbnail-save-options" type="button" value="Save options" class="button-primary" />
					<div class="wppt-loading-msg"><?php _e('Saving changes, please wait ...', 'Wppt'); ?></div>
					</fieldset>
				</form><br />

				<h3><?php echo _e('Custom thumbnail settings', 'Wppt'); ?></h3>
				<ul class="wppt_options_thumbnail_tabs_ul">
					<?php $selectFirst=true; $i=0;
					foreach ( $this->wppt_preset as $thumbnail ) { ?>
						<li id="wppt-options-thumbnail-tabs-<?php echo $i++; ?>" <?php echo ($selectFirst) ? "class=\"wppt-options-selected-thumbnail\"" : ""; ?> ><?php echo $thumbnail->wppt_preset_name; ?></li>
					<?php $selectFirst = false; } ?>
				</ul>
				<ul class="wppt_options_thumbnail_ul">
					<?php $selectFirst=true;
					foreach ( $this->wppt_preset as $thumbnail ) { ?>
						<li <?php echo ($selectFirst) ? "style=\"display:block;\"" : ""; ?> >
							<fieldset>
							<table class="form-table">
								<!--<tr>
									<th scope="row" valign="top"><?php _e('Name', 'Wppt'); ?>:</th>
									<td>-->
										<input type="hidden" name="wppt-custom-thumbnail-id" value="<?php echo $thumbnail->wppt_preset_id; ?>" />
										<!--<input value="<?php echo $thumbnail->wppt_preset_name; ?>" name="wppt-custom-thumbnail-name" type="text" />
									</td>
								</tr>
								<tr>
									<th scope="row" valign="top"><?php _e('Description', 'Wppt'); ?>:</th>
									<td>
										<input value="<?php echo $thumbnail->wppt_preset_desc; ?>" name="wppt-custom-thumbnail-desc" type="text" />
									</td>
								</tr>-->
								<tr>
									<th scope="row" valign="top"><?php _e('Dimension', 'Wppt'); ?>:</th>
									<td style="background:#eee; border:3px solid #e2e2e2;">
										<input value="<?php echo $thumbnail->wppt_preset_width; ?>" name="wppt-custom-thumbnail-width" type="text" maxlength="3" style="width:4.68em; margin-bottom:0.35em;" /> px <?php _e('width', 'Wppt'); ?><br />
										<input value="<?php echo $thumbnail->wppt_preset_height; ?>" name="wppt-custom-thumbnail-height" type="text" maxlength="3" style="width:4.68em;"/> px <?php _e('height', 'Wppt'); ?> &nbsp;&nbsp;&nbsp;&nbsp; <input type="button" value="<?php _e('Resize', 'Wppt'); ?>" class="wppt-custom-thumbnail-resize button-primary" /> <span class="wppt-loading-msg"><?php _e('Saving changes, please wait', 'Wppt'); ?> ...</span><br style="margin-bottom:1.28em;" />
										<span class="wppt-option-desc" style="margin-top:0.38em;"><?php _e('All thumbnails will be resized to the dimension specified above. If you have hundreds of thumbnails, this process might take up to', 'Wppt'); ?> <span style="background:#ebff7e;"><?php _e('SEVERAL MINUTES', 'Wppt'); ?></span> <?php _e('to complete', 'Wppt'); ?>. <span style="background:#ebff7e;"><?php _e('DO NOT LEAVE THIS PAGE', 'Wppt'); ?></span> <?php _e('or you will interrupt the processing.', 'Wppt'); ?></span>
									</td>
								</tr>
								<tr style="height:0.18em;"><td></td></tr>
								<tr>
									<th scope="row" valign="top"><?php _e('Assign to Custom Key', 'Wppt'); ?>:</th>
									<td style="background:#eee; border:3px solid #e2e2e2; padding-top:1em;" >
										<input value="<?php echo $thumbnail->wppt_preset_meta_key; ?>" name="wppt-custom-thumbnail-meta-key-name" type="text" />&nbsp;&nbsp;
										<input type="button" value="<?php _e('Assign', 'Wppt'); ?>" class="wppt-custom-thumbnail-meta-key-name-set button-primary" /> <span class="wppt-loading-msg"><?php _e('Saving changes, please wait ...', 'Wppt'); ?></span><br style="margin-bottom:1.28em;"/>
										<span class="wppt-option-desc">* <?php _e('Required. Custom key must be unique. No spaces allowed.', 'Wppt'); ?></span>
									</td>
								</tr>
							</table>
							</fieldset>
						</li>
					<?php $selectFirst = false; } ?>
				</ul><br /><br />
			</div>
		<?php }






		/**
		* Save options
		*/
		function save_options() {
			$this->wppt_options['default_jpeg_quality'] = $_POST['default_jpeg_quality'];
			$this->wppt_options['default_thumbnail'] = $_POST['default_thumbnail'];
			update_option( 'wppt_options', $this->wppt_options, 'WP Post Thumbnail options' );
			exit;
		}





		/**
		* Add plugin's main template to WordPress's "Write/Edit Post" page
		*/
		function add_form_template() {
			if (function_exists('add_meta_box'))  {
				add_meta_box( 'wp-post-thumbnail', 'WP Post Thumbnail',
					array( &$this, 'form_template' ), 'post', 'normal' );
			} else {
				add_action( 'dbx_post_advanced',
					array( &$this, 'form_template') );
			}
		}





		/**
		* Plugin's main template for "Write/Edit Post" page
		*/
		function form_template() { ?>
			<div class="wppt-save-draft-before-using-msg"><?php _e('Save new post as draft before making thumbnails', 'Wppt'); ?></div>
			<div class="wppt-thumbnail-tabs-container">
				<strong style="margin:0.68em 0 1.98em 0.88em;display:block;color:#a8a8a8;"><span class="wppt-step">1</span> <?php _e('Select thumbnail','Wppt'); ?>:</strong>
				<div class="wppt_thumbnail_preview">
	
					<div class="wppt-thumbnail-preview-meta"></div>
	
					<div class="wppt-thumbnail-preview-image"></div>
					<p class="wppt-delete-thumbnail-link"><a href="#"><?php _e('Delete thumbnail?', 'Wppt'); ?></a></p>
				</div>
			</div>
			<div class="wppt-save-thumbnail-area">
				<strong style="margin:0;color:#a8a8a8;"><span class="wppt-step">3</span> <span style="line-height:2em"><?php _e('Save thumbnail', 'Wppt'); ?>:</span></strong><br />
				<br />
				<span class="wppt_optional_fields_toggle"><a href="#"><?php _e('Edit optional settings', 'Wppt'); ?></a></span><br />
				<br />
				<div class="wppt_optional_fields">
					<div id="wppt-thumbnail-filename">
						<div class="wppt-optional-field-label"><?php _e('Filename', 'Wppt'); ?>:</div>
						<div class="wppt-optional-field-display">
							<span class="wppt-optional-field-display-empty-msg"><?php _e('Click to edit', 'Wppt'); ?> ...</span>
							<span class="wppt-optional-field-display-data"></span>
						</div>
						<div class="wppt-optional-field-edit">
							<input type="text" class="wppt-optional-text-field" value="" style="font-size:90%" /><br />
							<input type="button" class="wppt-optional-field-save-button" value="<?php _e( 'Set', 'Wppt' ); ?>" /> <?php echo _e('or', 'Wppt'); ?> <input type="button" class="wppt-optional-field-cancel-button" value="<?php _e( 'Cancel', 'Wppt' ); ?>" />
						</div>
						<div style="color:#a8a8a8;font-size:90%;font-style:italic;margin-top:0.18em;">( <?php _e( 'Keywords only', 'Wppt' ); ?> )</div>
						<div class="wppt-loading-msg"><?php _e( 'Saving', 'Wppt' ); ?> ...</div>
					</div>
					<div id="wppt-thumbnail-alt">
						<div class="wppt-optional-field-label"><?php _e( 'Alt', 'Wppt' ); ?>:</div>
						<div class="wppt-optional-field-display">
							<span class="wppt-optional-field-display-empty-msg"><?php _e( 'Click to edit', 'Wppt' ); ?> ...</span>
							<span class="wppt-optional-field-display-data"></span>
						</div>
						<div class="wppt-optional-field-edit">
							<input type="text" class="wppt-optional-text-field" value="" style="font-size:90%" /><br />
							<input type="button" class="wppt-optional-field-save-button" value="<?php echo _e('Set', 'Wppt'); ?>" /> <?php echo _e('or', 'Wppt'); ?> <input type="button" class="wppt-optional-field-cancel-button" value="<?php echo _e('Cancel', 'Wppt'); ?>" />
						</div>
						<div class="wppt-loading-msg"><?php _e( 'Saving', 'Wppt' ); ?> ...</div>
					</div>
					<div id="wppt-thumbnail-title">
						<div class="wppt-optional-field-label"><?php echo __('Title', 'Wppt'); ?>:</div>
						<div class="wppt-optional-field-display">
							<span class="wppt-optional-field-display-empty-msg"><?php echo __('Click to edit', 'Wppt'); ?> ...</span>
							<span class="wppt-optional-field-display-data"></span>
						</div>
						<div class="wppt-optional-field-edit">
							<input type="text" class="wppt-optional-text-field" value="" style="font-size:90%" /><br />
							<input type="button" class="wppt-optional-field-save-button" value="<?php echo _e('Set', 'Wppt'); ?>" /> <?php echo _e('or', 'Wppt'); ?> <input type="button" class="wppt-optional-field-cancel-button" value="<?php echo _e('Cancel', 'Wppt'); ?>" />
						</div>
						<div class="wppt-loading-msg"><?php _e( 'Saving', 'Wppt' ); ?> ...</div>
					</div>
					<fieldset class="wppt-default-placement-fieldset">
						<legend class="wppt-optional-field-label"><?php _e( 'Placement', 'Wppt' ); ?>:</legend>
						<div class="wppt-loading-msg"><?php _e( 'Saving', 'Wppt' ); ?> <?php _e( 'placement', 'Wppt' ); ?> ...</div>
						<input type="radio" name="wppt_default_placement_group" id="wppt_floatleft_belowtitle" value="0" checked="checked" />
						<label for="wppt_floatleft_belowtitle"><img src="<?php echo get_settings('siteurl'); ?>/wp-content/plugins/wp-post-thumbnail/images/floatleft_belowtitle.png" style="vertical-align:text-bottom;" alt="Float left below title" /></label>
						<input type="radio" name="wppt_default_placement_group" id="wppt_floatright_belowtitle" value="1"/>
						<label for="wppt_floatright_belowtitle"><img src="<?php echo get_settings( 'siteurl' ); ?>/wp-content/plugins/wp-post-thumbnail/images/floatright_belowtitle.png" style="vertical-align:text-bottom;" alt="Float right below title" /></label>
						<div style="color:#a8a8a8;font-size:90%;font-style:italic;margin-top:0.18em;"><?php _e( 'For default mode only', 'Wppt' ); ?>.</div>
					</fieldset>
				</div>
				<span id="wppt_save_button_area">
					<input type="button" id="wppt_save_thumbnail_button" class="button" value="<?php _e( 'Save Thumbnail', 'Wppt' ); ?>" /><br />
					<span style="color:#a8a8a8;font-size:90%;font-style:italic;"><?php _e( 'Overwrites existing thumbnail', 'Wppt' ); ?></span>
				</span>
				<span id="wppt_save_button_area_loading" style="display:none;"><?php _e( 'Saving', 'Wppt' ); ?> ...</span>
			</div>
			<br style="clear:both;" />
			<div style="font-weight:bold;margin:0 0 1.18em 0.88em;display:block;color:#a8a8a8;"><span class="wppt-step">2</span> <?php _e( 'Select photo to crop', 'Wppt' ); ?>:</div>
			<div id="wppt-image-library-thumbnails-area">
				<div class="footer">
					<p class="msg"><?php _e( 'Uploaded new images to the media library? Click', 'Wppt' ); ?> <input id="wppt-reload-image-library" type="button" value="Reload" class="button" /> <?php _e( 'to refresh thumbnails', 'Wppt' ); ?>.</p>
					<p class="loading-msg"><?php _e( 'Loading image attachments from media library', 'Wppt' ); ?> ...</p>
				</div>
			</div>
			<div id="wppt-source-image-area">
				<div class="wppt-source-image-loading"><?php _e( 'Loading', 'Wppt' ); ?> ...</div>
			</div>
		<?php }





		/**
		* Generate a random string of numbers and alphabets.
		* 
		* @return string
		*/
		function makeRandomString( $len = 12 ) {
			$chars = "PQO0WI1E2URY3TLA8KS7JD6HF2GVBCNXMZqazwsxedrf4cvty8bngh1uij0kmlpo";
			for ( $p = 0; $p < $len; $p++ ) { $s .= $chars[ mt_rand( 0, strlen( $chars ) ) ]; }
			return $s;
		}





		/**
		* Get selected thumbnail's cropped coordinates.
		*
		* @return array
		*/
		function extractCropCoords( $results ) {
			return array( 'x' => intval( $results->wppt_x ), 'y' => intval( $results->wppt_y ),
					'w' => intval( $results->wppt_x2 ) - intval( $results->wppt_x ),
					'h' => intval( $results->wppt_y2 ) - intval( $results->wppt_y ) );
		}





		/**
		* Retrieve a row of record from "wppt" database
		* where "wppt_post_id" and "wppt_preset_id" matches
		*
		* @return array or null
		*/
		function getRecordRow( $postID, $thumbID ) {
			global $wpdb;
			return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->wppt_db WHERE wppt_post_id = %d
				AND wppt_preset_id = %d", intval( $postID ), intval( $thumbID ) ) );
		}





		/**
		* Called via AJAX whenever a thumbnail tab is clicked.
		*
		* @return JSON
		*/
		function getSelectedThumb() {
			global $wpdb;

			$thumbID = intval( $_POST['thumbID'] );
			$postID = intval( $_POST['postID'] );
			$attachID = attribute_escape( $_POST['srcAttachID'] );

			$data = array( 'wppt_preset_name' => $this->wppt_preset[ $thumbID ]->wppt_preset_name,
				'wppt_preset_desc' => $this->wppt_preset[ $thumbID ]->wppt_preset_desc,
				'wppt_preset_width' => $this->wppt_preset[ $thumbID ]->wppt_preset_width,
				'wppt_preset_height' => $this->wppt_preset[ $thumbID ]->wppt_preset_height,
				'wppt_preset_meta_key' => $this->wppt_preset[ $thumbID ]->wppt_preset_meta_key );

			$data['smallSourceWarningMsg'] = $this->wppt_error_msgs['smallSourceWarningMsg'];
			
			$row = $this->getRecordRow( $postID, $thumbID );
			/* No record of thumbnail found in database. No further information
			required to be sent back to blogger so exit function. */
			if ( !$row ) { echo json_encode( $data ); exit(); }

			/* Get thumbnail url from custom key. At the same time, this checks whether
			custom key exists or not.
			$existing_thumbnail = get_post_meta($postID, $thumbID, true); */
	
			/* If no custom key exists, it could have been accidently deleted by blogger.
			Recreate custom key from thumbnail's DB record data.
			if ( !$existing_thumbnail ) {
				$existing_thumbnail = $this->path_to_url( $results->thumbnail_path );
				$updatedCustomKey = $this->updateCustomKey( $postID, $thumbID,
							$this->path_to_url($results->thumbnail_path) );
				$wpdb->query ( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "wp_post_thumbnail
					SET thumbnail_meta_id = %d WHERE thumbnail_id = %d",
					$updatedCustomKey, $results->thumbnail_id ) );
			} */

			$guid = stripslashes( htmlspecialchars( $row->wppt_guid ) );
			if ( !file_exists( $this->url_to_path( $guid ) ) ) { echo json_encode( $data ); exit(); }
			$data['guid'] = $guid;

			/* a random string to attach to thumbnail's URL to force refresh */
			$data['random_str'] = $this->makeRandomString();

			/* Convert absolute path filename to filename keywords eg.
			From "/home/username/wordpress/wp-content/uploads/wp-post-thumbnail/my-cool-thumbnail-s48Jhe.jpg"
			to "my cool thumbnail" */
			$filename = basename( $data['guid'] ); /* my-cool-thumbnail-s48Jhe.jpg */
			$randStr_and_ext = substr( $filename, strrpos( $filename, "-" ) ); /* -s48Jhe.jpg */
			$filename = str_replace( $randStr_and_ext, "", $filename ); /* my-cool-thumbnail */
			$data['filename'] = str_replace( '-', ' ', $filename ); /* my cool thumbnail */

			$data['placementID'] = $row->wppt_placement;
			$data['alt'] = $row->wppt_alt;
			$data['title'] = $row->wppt_title;

			/* If current source image is selected thumbnail's source, get crop coordinates too */
			if ( ( $row->wppt_src_id == $attachID ) &&
				( file_exists( get_attached_file( $attachID ) ) ) ) {
				$data = array_merge( $data, $this->extractCropCoords( $row ) );
			}

			echo json_encode( $data );
			exit();
		}





		/**
		* Called via AJAX to dynamically build an HTML
		* unordered list of thumbnail tabs.
		*
		* @return string
		*/
		function buildThumbTabs() {
			foreach ( $this->wppt_preset as $thumbnail )
				$li .= '<li id="wppt-thumbnail-id-' . $thumbnail->wppt_preset_id.'">' .
					$thumbnail->wppt_preset_name.'</li>' . "\n";

			echo '<ul class="wppt_thumbnail_tabs_ul">' . "\n" . $li . '</ul>'."\n";
			exit();
		}





		/**
		* Get source image attachment
		*/
		function get_src_image_attachment() {
			global $wpdb;

			$heightLimit = 600;
			$containerWidth = intval( attribute_escape( $_POST['containerWidth'] ) );
			$data['attachID'] = attribute_escape( $_POST['attachID'] );
			$data['warning_smallSrcImg'] = $this->wppt_error_msgs['smallSourceWarningMsg'];

			/* Get attachment image URL, full width, full height */
			$attachImgData = wp_get_attachment_image_src( $data['attachID'], 'full' );

			/* get absolute path source attachment image */
			$data['abspath'] = get_attached_file( $data['attachID'] );
			
			if ( !$attachImgData || !file_exists($data['abspath'] ) ) {
				$data['error'] =
					sprintf( __("[ Error ] : Image attachment: \n\n \" %s \" \n\n ... is not found.", "Wppt"), $data['attachID'] );
				echo json_encode( $data );
				exit;
			}

			$cache_dir = WP_PLUGIN_DIR . '/wp-post-thumbnail/cache';
			if ( !file_exists( $cache_dir ) ) {
				$data['error'] =
					sprintf( __("[ Error ] : WP Post Thumbnail's cache directory: \n\n \" %s \" \n\n ... does not exists.", "Wppt"), $cache_dir );
				echo json_encode( $data );
				exit;
			};

			if ( !is_writable( $cache_dir ) ) {
				$data['error'] =
					sprintf( __("[ Error ] : WP Post Thumbnail's cache directory: \n\n \" %s \" \n\n ... is not writable. \n\n [ Fix ] : CHMOD this directory to 777 mode.", "Wppt"), $cache_dir );
				echo json_encode( $data );
				exit;
			};

			$data = array_merge( $data, $attachImgData );

			/* resize image attachment to fit cropping area if necessary */
			$heightScale = $heightLimit / $data[2];
			$widthScale = $containerWidth / $data[1];
			$data['scaleRatio'] = $widthScale <= $heightScale ? $widthScale : $heightScale;
			$data['scaleRatio'] = min( $data['scaleRatio'], 0.99 );

			$cache_dir = WP_PLUGIN_DIR . '/wp-post-thumbnail/cache';
			array_map( 'unlink', glob( $cache_dir . '/*.*' ) );
			$data['resizedImgUrl'] = $this->path_to_url( image_resize( $data['abspath'],
				$data[1] * $data['scaleRatio'], $data[2] * $data['scaleRatio'], false, "", $cache_dir, 20 ) );

			/* If this is source image for selected thumbnail, get crop coordinates from database */
			$row = $this->getRecordRow( intval( $_POST['postID'] ), intval( $_POST['thumbID'] ) );
			if ( $row->wppt_src_id == $data[ 'attachID' ] )
				$data = array_merge( $data, $this->extractCropCoords( $row ) );

			echo json_encode( $data );
			exit;
		}





		/**
		* Function description
		*
		* @return boolean
		*/
		function list_thumbs() {
			/* get image(s) attached to post */
			$args = array('post_type' => 'attachment', 'numberposts' => -1,
				'post_status' => null, 'post_parent' => $_POST['postID'] );
			$res = '';

			if ($imgattachs = get_posts($args) ) {
				$res .= '<ul class="wppt-image-library-thumbnails">';

				foreach ($imgattachs as $imgattach) {
					/* get thumbnail for image attachment */
					$src = wp_get_attachment_image_src($imgattach->ID, 'thumbnail');
					$thumb = '<img src="' . $src[0] . '" alt="' .
								$imgattach->ID . '" />';
					$res .= "<li>$thumb</li>\n";
				}

				echo $res . "</ul>\n";
			} else {
				echo $res . '<p class="no-image-attachments-found-msg">' .
					__( "No image attachments found. Upload an image file using WordPress built-in media library.",
					'Wppt' ) . "</p>\n";
			}
			
			exit;
		} /* function list_thumbs */





		/**
		* Save cropped image as thumbnail
		*
		* @return string
		*/
		function saveThumbnail() {
			global $wpdb;

			/* cropped thumbnail dimensions */
			$x = intval( $_POST['x'] );     $y = intval( $_POST['y'] );
			$x2 = intval( $_POST['x2'] );   $y2 = intval( $_POST['y2'] );

			$jpeg_quality = intval( $this->wppt_options['default_jpeg_quality'] );
			$thumb_width = $this->wppt_preset[ $_POST['thumbID'] ]->wppt_preset_width;
			$thumb_height = $this->wppt_preset[ $_POST['thumbID'] ]->wppt_preset_height;

			$srcPath = get_attached_file( $_POST['attachID'] ); /* get source image path */

			/* Convert keywords "my cool thumbnail" to "my-cool-thumbnail-4ij2Pu".
			Does not have extension (.jpg, .png) yet. */
			$thumbFilename = $this->makeThumbnailFilename( $_POST['filenameKeywords'] );

			/* Generate and save new thumbnail. Returns "my-cool-thumbnail-4ij2Pu.jpg"
			if thumbnail is a .jpg file. */
			require_once ('includes/gd_image_processing.php');
			$thumb_guid = $this->path_to_url( gdResizeThumbnail( $thumbFilename, $srcPath,
				$x, $y,	$x2 - $x, $y2 - $y, $thumb_width, $thumb_height, $jpeg_quality ) );

			/* Check for and remove old thumbnail if exists */
			$this->deleteThumbnail( $_POST['postID'], $_POST['thumbID'] );

			/* Insert or update a custom key to reference thumbnail's URL */
			$updatedCustomKey = $this->updateCustomKey( $_POST['postID'],
									$_POST['thumbID'], $thumb_guid );

			/* Update "wppt" database table with thumbnail's info */
			$this->updateThumbnailDB( array(
					'wppt_post_id' => $_POST['postID'],
					'wppt_preset_id' => $_POST['thumbID'],
					'wppt_meta_id'=> $updatedCustomKey,
					'wppt_guid' => $thumb_guid,
					'wppt_src_id' => $_POST['attachID'],
					'wppt_x' => $x, 'wppt_y' => $y,
					'wppt_x2' => $x2, 'wppt_y2' => $y2,
					'wppt_placement' => $_POST['placementID'],
					'wppt_alt' => $_POST['alt'],
					'wppt_title' => $_POST['title'] ) );

			exit;
		} /* save_thumbnail */





		/**
		* Insert/Update thumbnail data in "prefix_wppt" database
		*
		* @param array
		* @return boolean
		*/
		function updateThumbnailDB( $args ) {
			extract( $args );
			global $wpdb;

			if ( $this->getRecordRow( $wppt_post_id, $wppt_preset_id ) != NULL ) {
				return $wpdb->query ( $wpdb->prepare(
					"UPDATE $this->wppt_db SET wppt_meta_id = %d, wppt_guid = %s,
						wppt_src_id = %d, wppt_x = %d, wppt_y = %d, wppt_x2 = %d, wppt_y2 = %d,
						wppt_orig_x = %d, wppt_orig_y = %d, wppt_orig_x2 = %d, wppt_orig_y2 = %d,
						wppt_placement = %s, wppt_alt = %s, wppt_title = %s
					WHERE wppt_post_id = %d AND wppt_preset_id = %d",
					intval( $wppt_meta_id ), $wppt_guid, intval( $wppt_src_id ),
					intval( $wppt_x ), intval( $wppt_y ), intval( $wppt_x2 ), intval( $wppt_y2 ),
					intval( $wppt_x ), intval( $wppt_y ), intval( $wppt_x2 ), intval( $wppt_y2 ),
					$wppt_placement, $wppt_alt, $wppt_title,
					intval( $wppt_post_id ), intval( $wppt_preset_id ) ) );
			} else {
				return $wpdb->query( $wpdb->prepare(
					"INSERT INTO $this->wppt_db ( wppt_post_id, wppt_preset_id,
						wppt_meta_id, wppt_guid, wppt_src_id, wppt_x, wppt_y, wppt_x2,
						wppt_y2, wppt_orig_x, wppt_orig_y, wppt_orig_x2, wppt_orig_y2,
						wppt_placement, wppt_alt, wppt_title )
					VALUES ( %d, %d, %d, %s, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %s, %s )",
					intval( $wppt_post_id ), intval( $wppt_preset_id ),
					intval( $wppt_meta_id ), $wppt_guid, intval( $wppt_src_id ),
					intval( $wppt_x ), intval( $wppt_y ), intval( $wppt_x2 ), intval( $wppt_y2 ),
					intval( $wppt_x ), intval( $wppt_y ), intval( $wppt_x2 ), intval( $wppt_y2 ),
					$wppt_placement, $wppt_alt, $wppt_title ) );
			}
		}





		/**
		* Each time blogger saves thumbnail, a custom key is inserted/updated in the database.
		* Returns int ID of inserted/updated custom key when successful.
		* Return int zero when failed to insert/update database or thumbnail custom key is blank.
		*
		* @return int
		*/
		function updateCustomKey( $postID, $thumbID, $thumb_guid ) {
			/* Get thumbnail's custom key name */
			$thumb_meta_key = $this->wppt_preset[ intval( $thumbID ) ]->wppt_preset_meta_key;
			if ( strlen( $thumb_meta_key ) < 1 ) { return 0; } /* Return 0 if blank */

			/* Update custom key. Return int ID of custom key when successful */
			if ( update_post_meta( $postID, $thumb_meta_key, $thumb_guid ) ) {
				global $wpdb;

				return $wpdb->get_var( $wpdb->prepare("SELECT meta_id FROM $wpdb->postmeta
					WHERE post_id = %d AND meta_value = %s", intval( $postID ), $thumb_guid ) );
			} else { return 0; }
		}





		/**
		* Delete thumbnail if exists
		*
		* @return int when successful or boolean if fail
		*/
		function deleteThumbnail( $postID, $thumbID ) {
			$row = $this->getRecordRow( $_POST['postID'], $_POST['thumbID'] );

			if ( !$row ) { return false; }
			$thumbnail_filepath = $this->url_to_path( $row->wppt_guid );

			/* Check if exist and delete it */
			if ( file_exists( $thumbnail_filepath ) ) { unlink( $thumbnail_filepath ); }
			else { return false; }

			return $row->wppt_id;
		}





		/**
		* Called via AJAX by user. Delete thumbnail and removes
		* record row from database.
		*/
		function fullDeleteThumbnail() {
			/* Delete the thumbnail file */
			$deletedThumbID = $this->deleteThumbnail( $_POST['postID'], $_POST['thumbID'] );

			/* Remove thumbnail's record from database */
			if ( $deletedThumbID ) {
				global $wpdb;

				$wpdb->query( $wpdb->prepare( "DELETE FROM $this->wppt_db
					WHERE wppt_id = %d", $deletedThumbID ) );

				$thumb_meta_key = $this->wppt_preset[ intval( $_POST['thumbID'] ) ]->wppt_preset_meta_key;
				delete_post_meta( $_POST['postID'], $thumb_meta_key );
			}

			exit;
		}





		/**
		* Make filename out of keyword(s) entered by user.
		* Append a random string and optional $ext at the end.
		* Prepend $dir (upload path) at the start.
		*
		* Turns "nice thumbnail" keywords
		*    to "/public_html/myblog/wp-content/uploads/wp-post-thumbnail/nice-thumbnail-rj8E2hDb" 
		*
		* @return string
		*/
		function makeThumbnailFilename( $keywords, $dir = '', $ext = '' ) {
			if ( $dir == '' ) { $dir = $this->getUploadPath() . '/'; }
			if ( $ext != '' ) { $ext = '.' . $ext; }

			$filename = strtolower( str_replace( ' ', '-', $keywords ) );

			$hasExt = strrpos( $filename, '.' );
			if ( $hasExt ) { $filename = substr( $filename, 0, $hasExt ); }

			if ( strlen( $filename ) > 0 ) { $filename .= '-'; }
			$filename .= $this->makeRandomString( 6 );

			return "$dir$filename$ext";
		}






		/**
		* Called via AJAX. Change thumbnail filename.
		*
		* @return string
		*/
		function saveThumbnailFilename() {
			global $wpdb;

			/* Check if thumbnail record exists. Exit if not found. */
			$row = $this->getRecordRow( $_POST['postID'], $_POST['thumbID'] );
			if ( !$row ) {
				echo $_POST['fieldValue'];
				exit;
			}

			/* Rename thumbnail file */
			$ext = substr( strrchr( $row->wppt_guid, '.' ), 1 );
			$filename = $this->makeThumbnailFilename( $_POST['fieldValue'], '', $ext );
			$guid = $this->path_to_url( $filename );
			rename( $this->url_to_path( $row->wppt_guid ), $filename );

			/* Update "wppt" database table with new thumbnail filename */
			$wpdb->query( $wpdb->prepare( "UPDATE $this->wppt_db SET wppt_guid = %s
				WHERE wppt_id = %d", $guid, $row->wppt_id ) );

			/* Update "postmeta" database table with new thumbnail filename */
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s
				WHERE meta_id = %d", $guid, $row->wppt_meta_id ) );

			echo $_POST['fieldValue'];
			exit;
		} /* function saveThumbnailFilename */





		/**
		* Save thumbnail 'ALT' attribute
		*
		* @return string
		*/
		function saveThumbnailAlt() {
			global $wpdb;

			$row = $this->getRecordRow( $_POST['postID'], $_POST['thumbID'] );
			if ( !$row ) {
				echo $_POST['fieldValue'];
				exit;
			}

			$wpdb->query ( $wpdb->prepare( "UPDATE $this->wppt_db
				SET wppt_alt = %s WHERE wppt_id = %d",
				$_POST['fieldValue'], $row->wppt_id ) );

			echo $_POST['fieldValue'];
			exit;
		}





		/**
		* Save thumbnail 'TITLE' attribute
		*
		* @return string
		*/
		function saveThumbnailTitle() {
			global $wpdb;

			$row = $this->getRecordRow( $_POST['postID'], $_POST['thumbID'] );
			if ( !$row ) {
				echo $_POST['fieldValue'];
				exit;
			}

			$wpdb->query ( $wpdb->prepare( "UPDATE $this->wppt_db
				SET wppt_title = %s WHERE wppt_id = %d",
				$_POST['fieldValue'], $row->wppt_id ) );

			echo $_POST['fieldValue'];
			exit;
		}





		/**
		* Save thumbnail's placement
		*
		* @return int
		*/
		function saveThumbnailPlacement() {
			global $wpdb;

			$row = $this->getRecordRow( $_POST['postID'], $_POST['thumbID'] );
			if ( !$row ) {
				echo $_POST['fieldValue'];
				exit;
			}

			$wpdb->query ( $wpdb->prepare( "UPDATE $this->wppt_db
					SET wppt_placement = %d WHERE wppt_id = %d",
					intval( $_POST['fieldValue'] ), $row->wppt_id ) );

			echo $_POST['fieldValue'];
			exit;
		}





		/**
		* Options page. Change thumbnail's custom key name.
		*/
		function saveThumbCustomKey() {
			global $wpdb;

			$clicked_thumbnail_id = intval( $_POST["clicked_thumbnail_id"] );
			$new_meta_key = $_POST["custom_key"];
			$old_meta_key = $this->wppt_preset[ $clicked_thumbnail_id ]->wppt_preset_meta_key;

			/* If new custom key name is blank, delete all custom keys. */
			if ( strlen( $new_meta_key ) <= 0 ) { exit; }

			/* If new custom key exists */
			foreach ( $this->wppt_preset as $preset ) {
				if ( $new_meta_key == $preset->wppt_preset_meta_key ) { exit; }
			}

			/* Update custom keys ($wpdb->postmeta) table */
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_key = %s WHERE meta_id IN
					( SELECT wppt_meta_id FROM $this->wppt_db WHERE wppt_preset_id = %d )",
					$new_meta_key, $clicked_thumbnail_id ) );

			/* Update "wppt_preset" table with new meta_key */
			$wpdb->query( $wpdb->prepare( "UPDATE $this->wppt_preset_db
				SET wppt_preset_meta_key = %s WHERE wppt_preset_id = %d",
				$new_meta_key, $clicked_thumbnail_id ) );

			/* Reload "wppt_preset" option array from updated database table */
			$this->wppt_preset = $this->getPreset();
			exit;
		}





		/**
		* Options page. Resizing thumbnail's dimension.
		*/
		function saveThumbSize() {
			global $wpdb;

			$clicked_thumb_id = intval( $_POST["clicked_thumbnail_id"] );
			$new_thumb_width = intval( $_POST["width"] ); /* new user entered width */
			$new_thumb_height = intval( $_POST["height"] ); /* new user entered height */
			$new_thumb_aspect_ratio = $new_thumb_width / $new_thumb_height;

			/* Update "wppt-thumbnail" database with new width and height of the clicked thumbnail */ 
			$wpdb->query( $wpdb->prepare( "UPDATE $this->wppt_preset_db
					SET wppt_preset_width = %d, wppt_preset_height = %d
					WHERE wppt_preset_id = %d", $new_thumb_width,
					$new_thumb_height, $clicked_thumb_id ) );

			/* Update "wppt_thumbnails" array with new width and height */
			$this->wppt_preset = $this->getPreset();

			/* Get rows of thumbnails in "wppt" database table where it's ID matches clicked thumbnail id. */
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT wppt_id, wppt_guid, wppt_orig_x,
				wppt_orig_y, wppt_orig_x2, wppt_orig_y2, wppt_src_id FROM $this->wppt_db
				WHERE wppt_preset_id = %d", $clicked_thumb_id ) );

			/* If none, exit */
			if ( sizeof( $results ) <= 0 ) {
				_e( "New width and height saved", "Wppt" );
				exit();
			}

			/* Start timer */
			$timerStart = explode( ' ', microtime() );
			$timerStart = $timerStart[1] + $timerStart[0];

			require_once( 'includes/gd_image_processing.php' );

			/* For each row, resize (or recrop if necessary) thumbnail while maintaining aspect ratio. */
			for( $i = 0, $size = sizeof( $results ); $i < $size; ++$i ) {
				set_time_limit( 30 );

				$srcPath = get_attached_file( $results[$i]->wppt_src_id ); /* get source image path */
				list( $actual_src_width, $actual_src_height, $src_type ) = getimagesize( $srcPath );

				$thumb_path = $this->url_to_path( $results[$i]->wppt_guid );
				$thumb_path = substr( $thumb_path, 0, strrpos( $thumb_path, '.' ) );

				$src_cropped_width = intval( $results[$i]->wppt_orig_x2 ) - intval( $results[$i]->wppt_orig_x );
				$src_cropped_height = intval( $results[$i]->wppt_orig_y2 ) - intval( $results[$i]->wppt_orig_y );

				$cx = intval( $results[$i]->wppt_orig_x ) + ( $src_cropped_width / 2 );
				$cy = intval( $results[$i]->wppt_orig_y ) + ( $src_cropped_height / 2 );

				$src_cropped_width = $src_cropped_width > $new_thumb_width ? $src_cropped_width : $new_thumb_width;
				$src_cropped_height = $src_cropped_height > $new_thumb_height ? $src_cropped_height : $new_thumb_height;

				$src_aspect_ratio = $src_cropped_width / $src_cropped_height;

					/* adjust and scale source's width and height to new thumbnail's aspect ratio */
					$new_src_width = ( $src_cropped_height * $new_thumb_aspect_ratio ) - $src_cropped_width;
					$new_src_width = $src_cropped_width + round( $new_src_width / 2 );

					$new_src_height = ( $src_cropped_width / $new_thumb_aspect_ratio ) - $src_cropped_height;
					$new_src_height = $src_cropped_height + round( $new_src_height / 2 );

					$new_thumb_x = max( round( $cx - ( $new_src_width / 2 ) ), 0 );
					$new_thumb_y = max( round( $cy - ( $new_src_height / 2 ) ), 0 );

					$new_thumb_x2 = min( $new_thumb_x + $new_src_width, $actual_src_width );
					$new_thumb_y2 = min( $new_thumb_y + $new_src_height, $actual_src_height );

					$new_src_width = $new_thumb_x2 - $new_thumb_x;
					$new_src_height = $new_thumb_y2 - $new_thumb_y;

					if ( $new_src_width == $actual_src_width ) {
						$new_src_height = $new_src_width / $new_thumb_aspect_ratio;
						$new_thumb_y = round( $cy - ( $new_src_height / 2 ) );
						$new_thumb_y2 = $new_thumb_y + $new_src_height;
					} else if ( $new_src_height == $actual_src_height ) {
						$new_src_width = $new_src_height * $new_thumb_aspect_ratio;
						$new_thumb_x = round( $cx - ( $new_src_width / 2 ) );
						$new_thumb_x2 = $new_thumb_x + $new_src_width;
					}

				$resized_thumbnail_guid = $this->path_to_url( gdResizeThumbnail( $thumb_path, $srcPath, $new_thumb_x,
							$new_thumb_y, $new_src_width, $new_src_height, $new_thumb_width,
							$new_thumb_height, intval( $this->wppt_options['default_jpeg_quality'] ) ) );

				$wpdb->query( $wpdb->prepare( "UPDATE $this->wppt_db SET wppt_guid = %s,
					wppt_x = %d, wppt_y = %d, wppt_x2 = %d, wppt_y2 = %d WHERE wppt_id = %d",
					$resized_thumbnail_guid, $new_thumb_x, $new_thumb_y, $new_thumb_x2,
					$new_thumb_y2, $results[$i]->wppt_id ) );
			} /* for-loop */

			/* Stop timer. Output total time taken in seconds. */
			$timerStop = explode(' ', microtime() );
			$timerStop = $timerStop[1] + $timerStop[0];
			$rt_timetotal = number_format_i18n( $rt_endtime - $rt_starttime, 3 );
			echo sprintf( __( 'Done! All thumbnails are resized in %s seconds', 'Wppt' ),
								number_format_i18n( $timerStop - $timerStart, 3 ) ) . ".";
			exit;
		}

	} /* class Wppt */

} /* if ( !class_exists( "Wppt" ) ) */





/* instantiate class */
if ( class_exists( "Wppt" ) ) { $Wppt = new Wppt(); }