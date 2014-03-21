<?php
/*
Plugin Name: TentBlogger Optimize WordPress Database Plugin
Plugin URI: http://tentblogger.com/optimize-wordpress-database
Description: One of the best things you can do for your blog is to consistently optimize and clean your WordPress database. This plugin does all the work for you!
Version: 2.3
Author: TentBlogger
Author URI: http://tentblogger.com
License:

    Copyright 2011 - 2012 TentBlogger (info@tentblogger.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class TentBlogger_Optimize_WordPress_Database {
	 
	/*--------------------------------------------*
	 * Contructors and Filters
	 *---------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
	
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('tentblogger-optimize-wordpress-database', false, dirname(plugin_basename(__FILE__)) . '/lang');
		} // end if
		
		if(function_exists('add_action')) {
			add_action('admin_menu', array($this, 'admin'));
			add_action('init', array($this, 'optimize_database'));
		} // end if

	} // end constructor
	
	/*--------------------------------------------*
	 * Public Functions
	 *---------------------------------------------*/
	
	/**
	 * Adds the administration menu item to the WordPress administration menu.
	 */
	public function admin() {
  
		$this->load_file('tentblogger-optimize-wordpress-database-admin', '/tentblogger-optimize-wordpress-database-plugin/js/admin.js', true);
		
    if(!$this->my_menu_exists('tentblogger-handle')) {
      add_menu_page('TentBlogger', 'TentBlogger', 'administrator', 'tentblogger-handle', array($this, 'display'));
    }
    add_submenu_page('tentblogger-handle', 'TentBlogger', 'Optimize DB', 'administrator', 'tentblogger-db-handle', array($this, 'optimize_db_display'));
    
	} // end admin
	
	/**
	 * Includes the display for the base menu.
	 */
	public function display() {
		if(is_admin()) {
			include_once('tentblogger-optimize-wordpress-database-dashboard.php');
		} // end if
	} // end display
	
  /**
	 * Includes the display for this particular menu.
	 */
	public function optimize_db_display() {
		if(is_admin()) {
			include_once('tentblogger-optimize-wordpress-database-dashboard.php');
		} // end if
	} // end display
  
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/	
	
	/**
	 * The function responsible for actually running the query to optimizing the 
	 * WordPress database.
	 */
	function optimize_database() {
	
		global $wpdb;
		if(!empty($_GET['tbowpdb']) && strtolower($_GET['tbowpdb']) == 'trigger') {

			$query = "SHOW TABLE STATUS FROM " . DB_NAME;
			$results = $wpdb->get_results($query, ARRAY_A);
			
			foreach($results as $result) {
				if(!empty($result['Data_free'])) {
					$query = 'OPTIMIZE TABLE ' . $result['Name'];
					$current_results = $wpdb->query($query); ?>
					<p>
						<?php
						if(isset($current_results)) {
							_e('Database Table', 'tentblogger-optimize-wordpress-database') . $result['name']; _e('has been optimized.', 'tentblogger-optimize-wordpress-database');
						} else {
							_e('Error: Database Table', 'tentblogger-optimize-wordpress-database') . $result['Name'] . _e('could not be optimized.', 'tentblogger-optimize-wordpress-database');
						} // end if 
						?>
					</p>
				<?php } // end if
				_e('Optimization completed.', 'tentblogger-optimize-wordpress-database');
			} // end foreach
			exit;
			
		} // end if/else
	} // end optimize_database
	
	/**
	 * Adjusts the incoming filesize to megabytes,  kilobytes, or bytes.
	 *
	 * @raw_size	The incoming raw file size.
	 */
	function format_size($raw_size) {
	
		$bytes_to_megabyte = 1048576;
		$kbytes_to_megabyte = 1024;
		
		$size = null;
		
		if($raw_size / $bytes_to_megabyte > 1) {
			$size = round($raw_size / $bytes_to_megabyte, 1) . " MB";
		} else if ($raw_size / $kbytes_to_megabyte > 1) {
			$size = round($raw_size / $kbytes_to_megabyte, 1) . " KB";
		} else {
			$size = round($raw_size, 1) . " bytes";
		} // end if/else

		return $size;
		
	} // end format_size
	
	/*--------------------------------------------*
	 * Private Functions
	 *---------------------------------------------*/
	
	/**
	 * Returns the number of bytes that can be freed by running the OPTIMIZE
	 * query in the database.
	 */
	private function size_to_free() {
	
		global $wpdb;
		$should_resize = false;
	
		$query = 'SHOW TABLE STATUS FROM ' . DB_NAME;
		$results = $wpdb->get_results($query, ARRAY_A);
			
		$sum_free = 0;
		foreach($results as $result) {
			$sum_free = $sum_free + $result['Data_free'];
		} // end foreach
		
		return $sum_free;
		
	} // end size_to_free
	
	/**
	 * Displays an HTML view of the WordPress database.
	 */
	private function get_database_table_view() {
			
		global $wpdb;
		
		$query = 'SHOW TABLE STATUS FROM ' . DB_NAME;
		$results = $wpdb->get_results($query, ARRAY_A);
		?>
		
		<table id="tentblogger-database-view" class="widefat">
			<thead>
				<tr>
					<th>
						<?php _e('Table Name', 'tentblogger-optimize-wordpress-database'); ?>
					</th>
					<th>
						<?php _e('Data Stored', 'tentblogger-optimize-wordpress-database'); ?>
					</th>
					<th>
						<?php _e('Overhead', 'tentblogger-optimize-wordpress-database'); ?>
					</th>
				</tr>
			</thead>
			<tbody id="the-list">
				<?php
				$sum = 0;
				foreach ($results as $result) {
					if(!empty($result['Name'])) {
						echo '<tr>';
							echo '<td>' . $result['Name'] . '</td>';
							echo '<td>';
								echo $this->format_size($result['Data_length']);
							echo '</td>';
							echo '<td>' . $this->format_size($result['Data_free']) . '</td>';
						echo '</tr>';
					} // end if
				} // end foreach
				?>
			</tbody>
		</table>
			
	<?php
	} // end get_database_table_view
	
	/**
	 * Helper function for registering and loading scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file($name, $file_path, $is_script = false) {
		$url = WP_PLUGIN_URL . $file_path;
		$file = WP_PLUGIN_DIR . $file_path;
		if(file_exists($file)) {
			if($is_script) {
				wp_register_script($name, $url);
				wp_enqueue_script($name);
			} else {
				wp_register_style($name, $url);
				wp_enqueue_style($name);
			} // end if
		} // end if
	} // end _load_file
	
  /**
   * http://wordpress.stackexchange.com/questions/6311/how-to-check-if-an-admin-submenu-already-exists
   */
  private function my_menu_exists( $handle, $sub = false){
    if( !is_admin() || (defined('DOING_AJAX') && DOING_AJAX) )
      return false;
    global $menu, $submenu;
    $check_menu = $sub ? $submenu : $menu;
    if( empty( $check_menu ) )
      return false;
    foreach( $check_menu as $k => $item ){
      if( $sub ){
        foreach( $item as $sm ){
          if($handle == $sm[2])
            return true;
        }
      } else {
        if( $handle == $item[2] )
          return true;
      }
    }
    return false;
  } // end my_menu_exists
  
} // TentBlogger_FeedBurner
new TentBlogger_Optimize_WordPress_Database();
?>