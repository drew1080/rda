<?php
/*
UpdraftPlus Addon: autobackup:Automatic Backups
Description: Save time and worry by automatically create backups before updating WordPress components
Version: 1.5
Shop: /shop/autobackup/
Latest Change: 1.9.27
*/

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

if (defined('UPDRAFTPLUS_NOAUTOBACKUPS') && UPDRAFTPLUS_NOAUTOBACKUPS) return;

$updraftplus_addon_autobackup = new UpdraftPlus_Addon_Autobackup;

class UpdraftPlus_Addon_Autobackup {

	public function __construct() {
		add_filter('updraftplus_autobackup_blurb', array($this, 'updraftplus_autobackup_blurb'));
		add_action('admin_action_update-selected',  array($this, 'admin_action_update_selected'));
		add_action('admin_action_update-selected-themes', array($this, 'admin_action_update_selected_themes'));
		add_action('admin_action_do-plugin-upgrade', array($this, 'admin_action_do_plugin_upgrade'));
		add_action('admin_action_do-theme-upgrade', array($this, 'admin_action_do_theme_upgrade'));
		add_action('admin_action_do-theme-upgrade', array($this, 'admin_action_do_theme_upgrade'));
		add_action('admin_action_upgrade-plugin', array($this, 'admin_action_upgrade_plugin'));
		add_action('admin_action_upgrade-theme', array($this, 'admin_action_upgrade_theme'));
		add_action('admin_action_do-core-upgrade', array($this, 'admin_action_do_core_upgrade'));
		add_action('admin_action_do-core-reinstall', array($this, 'admin_action_do_core_upgrade'));
	}

	# This appears on the page listing several updates
	public function updraftplus_autobackup_blurb() {
		$ret = '<input '.((UpdraftPlus_Options::get_updraft_option('updraft_autobackup_default', true)) ? 'checked="checked"' : '').' type="checkbox" id="updraft_autobackup" value="doit" name="updraft_autobackup"> <label for="updraft_autobackup">'.__('Automatically backup (where relevant) plugins, themes and the WordPress database with UpdraftPlus before updating', 'updraftplus').'</label><br><input checked="checked" type="checkbox" value="set" name="updraft_autobackup_setdefault" id="updraft_autobackup_sdefault"> <label for="updraft_autobackup_sdefault">'.__('Remember this choice for next time (you will still have the chance to change it)', 'updraftplus').'</label><br><em><a href="http://updraftplus.com/automatic-backups/">'.__('Read more about how this works...','updraftplus').'</a></em>';
		add_action('admin_footer', array($this, 'admin_footer_insertintoform'));
		return $ret;
	}

	public function admin_footer_insertintoform() {
		$def = UpdraftPlus_Options::get_updraft_option('updraft_autobackup_default', true);
		$godef = ($def) ? 'yes' : 'no';
		echo <<<ENDHERE
		<script>
		jQuery(document).ready(function() {
			jQuery('form.upgrade').append('<input type="hidden" name="updraft_autobackup" class="updraft_autobackup_go" value="$godef">');
			jQuery('form.upgrade').append('<input type="hidden" name="updraft_autobackup_setdefault" class="updraft_autobackup_setdefault" value="yes">');
			jQuery('#updraft_autobackup').click(function() {
				var doauto = jQuery(this).attr('checked');
				if ('checked' == doauto) {
					jQuery('.updraft_autobackup_go').attr('value', 'yes');
				} else {
					jQuery('.updraft_autobackup_go').attr('value', 'no');
				}
			});
			jQuery('#updraft_autobackup_sdefault').click(function() {
				var sdef = jQuery(this).attr('checked');
				if ('checked' == sdef) {
					jQuery('.updraft_autobackup_setdefault').attr('value', 'yes');
				} else {
					jQuery('.updraft_autobackup_setdefault').attr('value', 'no');
				}
			});
		});
		</script>
ENDHERE;
	}

	public function admin_footer() {
		$creating = esc_js(sprintf(__('Creating %s and database backup with UpdraftPlus...', 'updraftplus'), $this->type).' '.__('(logs can be found in the UpdraftPlus settings page as normal)...', 'updraftplus'));
		$lastlog = esc_js(__('Last log message', 'updraftplus')).':';
		$updraft_credentialtest_nonce = wp_create_nonce('updraftplus-credentialtest-nonce');
		global $updraftplus;
		$updraftplus->log(__('Starting automatic backup...','updraftplus'));

		$unexpected_response = esc_js(__('Unexpected response:','updraftplus'));

		echo <<<ENDHERE
			<script>
				jQuery('h2:first').after('<p>$creating</p><p>$lastlog <span id="updraft_lastlogcontainer"></span></p><div id="updraft_activejobs"></div>');
				var lastlog_sdata = {
					action: 'updraft_ajax',
					subaction: 'activejobs_list',
					oneshot: 'yes'
				};
				setInterval(function(){updraft_autobackup_showlastlog(true);}, 3000);
				function updraft_autobackup_showlastlog(repeat){
					lastlog_sdata.nonce = '$updraft_credentialtest_nonce';
					jQuery.get(ajaxurl, lastlog_sdata, function(response) {
						try {
							resp = jQuery.parseJSON(response);
							if (resp.l != null) { jQuery('#updraft_lastlogcontainer').html(resp.l); }
							if (resp.j != null && resp.j != '') {
								jQuery('#updraft_activejobs').html(resp.j);
							} else {
								if (!jQuery('#updraft_activejobs').is(':hidden')) {
									jQuery('#updraft_activejobs').hide();
								}
							}
						} catch(err) {
							console.log('$unexpected_response '+response);
						}
					});
				}
			</script>
ENDHERE;
	}

	private function process_form() {
		# We use 0 instead of false, because false is the default for get_option(), and thus setting an unset value to false with update_option() actually sets nothing (since update_option() first checks for the existing value) - which is unhelpful if you want to call get_option() with a different default (as we do)
		$autobackup = (isset($_POST['updraft_autobackup']) && $_POST['updraft_autobackup'] == 'yes') ? 1 : 0;
		UpdraftPlus_Options::update_updraft_option('updraft_autobackup_go', $autobackup);
		if ($autobackup) add_action('admin_footer', array($this, 'admin_footer'));
		if (!empty($_POST['updraft_autobackup_setdefault']) && 'yes' == $_POST['updraft_autobackup_setdefault']) UpdraftPlus_Options::update_updraft_option('updraft_autobackup_default', $autobackup);
	}

	# The initial form submission from the updates page
	public function admin_action_do_plugin_upgrade() {
		$this->process_form();
		$this->type = __('plugins', 'updraftplus');
	}

	public function admin_action_do_theme_upgrade() {
		$this->process_form();
		$this->type = __('themes', 'updraftplus');
	}

	# Into the updating iframe...
	public function admin_action_update_selected() {
		if ( ! current_user_can('update_plugins') ) return;
		$autobackup = UpdraftPlus_Options::get_updraft_option('updraft_autobackup_go');
		if ($autobackup) $this->autobackup_go('plugins');
	}

	public function admin_action_update_selected_themes() {
		if ( ! current_user_can('update_themes') ) return;
		$autobackup = UpdraftPlus_Options::get_updraft_option('updraft_autobackup_go');
		if ($autobackup) $this->autobackup_go('themes');
	}

	public function admin_action_do_core_upgrade() {

		if (!isset($_POST['upgrade'])) return;

		if (!current_user_can('update_core')) wp_die( __( 'You do not have sufficient permissions to update this site.' ) );

		check_admin_referer('upgrade-core');
		# It is important to not use (bool)false here, as that conflicts with using get_option() with a non-false default value
		$autobackup = (isset($_POST['updraft_autobackup']) && $_POST['updraft_autobackup'] == 'yes') ? 1 : 0;

		if (!empty($_POST['updraft_autobackup_setdefault']) && 'yes' == $_POST['updraft_autobackup_setdefault']) UpdraftPlus_Options::update_updraft_option('updraft_autobackup_default', $autobackup);

		if ($autobackup) {
			require_once(ABSPATH . 'wp-admin/admin-header.php');

			$creating = __('Creating database backup with UpdraftPlus...', 'updraftplus').' '.__('(logs can be found in the UpdraftPlus settings page as normal)...', 'updraftplus');

			$lastlog = __('Last log message', 'updraftplus').':';
			$updraft_credentialtest_nonce = wp_create_nonce('updraftplus-credentialtest-nonce');
			$unexpected_response = esc_js(__('Unexpected response:','updraftplus'));

			global $updraftplus;
			$updraftplus->log(__('Starting automatic backup...','updraftplus'));

			echo '<div class="wrap"><h2>'.__('Automatic Backup','updraftplus').'</h2>';

			echo "<p>$creating</p><p>$lastlog <span id=\"updraft_lastlogcontainer\"></span></p><div id=\"updraft_activejobs\" style=\"clear:both;\"></div>";

			echo <<<ENDHERE
				<script>
					var lastlog_sdata = {
						action: 'updraft_ajax',
						subaction: 'activejobs_list',
						oneshot: 'yes'
					};
					setInterval(function(){updraft_autobackup_showlastlog(true);}, 3000);
					function updraft_autobackup_showlastlog(repeat){
						lastlog_sdata.nonce = '$updraft_credentialtest_nonce';
						jQuery.get(ajaxurl, lastlog_sdata, function(response) {
							try {
								resp = jQuery.parseJSON(response);
								if (resp.l != null) { jQuery('#updraft_lastlogcontainer').html(resp.l); }
								if (resp.j != null && resp.j != '') {
									jQuery('#updraft_activejobs').html(resp.j);
								} else {
									if (!jQuery('#updraft_activejobs').is(':hidden')) {
										jQuery('#updraft_activejobs').hide();
									}
								}
							} catch(err) {
								console.log('$unexpected_response '+response);
							}
						});
					}
				</script>
ENDHERE;

			$this->type = 'core';
			$this->autobackup_go('core', true);
			echo '</div>';
		}

	}

	private function autobackup_go($entity, $jquery = false) {
		define('UPDRAFTPLUS_BROWSERLOG', true);
		echo '<p style="clear:left; padding-top:6px;">'.__('Creating backup with UpdraftPlus...', 'updraftplus')."</p>";
		@ob_end_flush();
		echo '<pre id="updraftplus-autobackup-log">';
		global $updraftplus;
		$updraftplus->boot_backup(true, true, array($entity), true);
		echo '</pre>';
		if ($updraftplus->error_count() >0) {
			echo '<h2>'.__("Errors have occurred:", 'updraftplus').'</h2>';
			$updraftplus->list_errors();
			if ($jquery) include(ABSPATH . 'wp-admin/admin-footer.php');
			die;
		}
		$this->autobackup_finish($jquery);
	}

	private function autobackup_finish($jquery = false) {

		global $wpdb;
		if (method_exists($wpdb, 'check_connection') && !$wpdb->check_connection(false)) {
			$updraftplus->log("It seems the database went away, and could not be reconnected to");
			die;
		}

		echo "<script>var h = document.getElementById('updraftplus-autobackup-log'); h.style.display='none';</script>";

		if ($jquery) {
			echo '<p>'.__('Backup succeeded', 'updraftplus').' <a href="#updraftplus-autobackup-log" onclick="jQuery(\'#updraftplus-autobackup-log\').slideToggle();">'.__('(view log...)', 'updraftplus').'</a> - '.__('now proceeding with the updates...', 'updraftplus').'</p>';
		} else {
			echo '<p>'.__('Backup succeeded', 'updraftplus').' <a href="#updraftplus-autobackup-log" onclick="var s = document.getElementById(\'updraftplus-autobackup-log\'); s.style.display = \'block\';">'.__('(view log...)', 'updraftplus').'</a> - '.__('now proceeding with the updates...', 'updraftplus').'</p>';
		}

	}

	public function admin_action_upgrade_plugin() {
		if ( ! current_user_can('update_plugins') ) return;

		if (!empty($_REQUEST['updraftplus_noautobackup'])) return;

		$plugin = isset($_REQUEST['plugin']) ? trim($_REQUEST['plugin']) : '';
		check_admin_referer('upgrade-plugin_' . $plugin);

		$title = __('Update Plugin');
		$parent_file = 'plugins.php';
		$submenu_file = 'plugins.php';
		require_once(ABSPATH . 'wp-admin/admin-header.php');

		# Did the user get the opportunity to indicate whether they wanted a backup?
		if (!isset($_POST['updraft_autobackup_answer'])) $this->auto_backup_form_and_die();

		# Do not use bools here - conflicts with get_option() with a non-default value
		$autobackup = (isset($_POST['updraft_autobackup']) && $_POST['updraft_autobackup'] == 'yes') ? 1 : 0;

		if (!empty($_POST['updraft_autobackup_setdefault']) && 'yes' == $_POST['updraft_autobackup_setdefault']) UpdraftPlus_Options::update_updraft_option('updraft_autobackup_default', $autobackup);

		if ($autobackup) {
			echo '<div class="wrap"><h2>'.__('Automatic Backup','updraftplus').'</h2>';
			$this->autobackup_go('plugins', true);
			echo '</div>';
		}

		# Now, the backup is (if chosen) done... but the upgrade may not directly proceed. If WP needed filesystem credentials, then it may put up an intermediate screen, which we need to insert a field in to prevent an endless circle
		add_filter('request_filesystem_credentials', array($this, 'request_filesystem_credentials'));

	}

	public function request_filesystem_credentials($input) {
		echo <<<ENDHERE
<script>
	jQuery(document).ready(function(){
		jQuery('#upgrade').before('<input type="hidden" name="updraft_autobackup_answer" value="1">');
	});
</script>
ENDHERE;
		return $input;
	}

	public function admin_action_upgrade_theme() {

		if ( ! current_user_can('update_themes') ) return;
		$theme = isset($_REQUEST['theme']) ? urldecode($_REQUEST['theme']) : '';
		check_admin_referer('upgrade-theme_' . $theme);

		$title = __('Update Theme');
		$parent_file = 'themes.php';
		$submenu_file = 'themes.php';
		require_once(ABSPATH.'wp-admin/admin-header.php');

		# Did the user get the opportunity to indicate whether they wanted a backup?
		if (!isset($_POST['updraft_autobackup_answer'])) $this->auto_backup_form_and_die();

		# Do not use bools here - conflicts with get_option() with a non-default value
		$autobackup = (isset($_POST['updraft_autobackup']) && $_POST['updraft_autobackup'] == 'yes') ? 1 : 0;
		if (!empty($_POST['updraft_autobackup_setdefault']) && 'yes' == $_POST['updraft_autobackup_setdefault']) UpdraftPlus_Options::update_updraft_option('updraft_autobackup_default', $autobackup);

		if ($autobackup) {
			echo '<div class="wrap"><h2>'.__('Automatic Backup','updraftplus').'</h2>';
			$this->autobackup_go('themes', true);
			echo '</div>';
		}

		# Now, the backup is (if chosen) done... but the upgrade may not directly proceed. If WP needed filesystem credentials, then it may put up an intermediate screen, which we need to insert a field in to prevent an endless circle
		add_filter('request_filesystem_credentials', array($this, 'request_filesystem_credentials'));

	}

	private function auto_backup_form_and_die() {
		?>
		<h2><?php echo __('UpdraftPlus Automatic Backups', 'updraftplus');?></h2>
		<form method="post">
		<div id="updraft-autobackup" class="updated" style="border: 1px dotted; padding: 6px; margin:8px 0px; max-width: 540px;">
			<h3 style="margin-top: 0px;"><?php _e('Be safe with an automatic backup','updraftplus');?></h3>
			<input <?php if (UpdraftPlus_Options::get_updraft_option('updraft_autobackup_default', true)) echo 'checked="checked"';?> type="checkbox" id="updraft_autobackup" value="yes" name="updraft_autobackup"> <label for="updraft_autobackup"><?php echo __('Automatically backup (where relevant) plugins, themes and the WordPress database with UpdraftPlus before updating', 'updraftplus');?></label><br><input checked="checked" type="checkbox" value="yes" name="updraft_autobackup_setdefault" id="updraft_autobackup_setdefault"> <label for="updraft_autobackup_setdefault"><?php _e('Remember this choice for next time (you will still have the chance to change it)', 'updraftplus');?></label><br><em><a href="http://updraftplus.com/automatic-backups/"><?php _e('Read more about how this works...','updraftplus'); ?></a></em>
			<p><em><?php _e('Do not abort after pressing Proceed below - wait for the backup to complete.', 'updraftplus'); ?></em></p>
			<input style="clear:left; margin-top: 6px;" name="updraft_autobackup_answer" type="submit" value="<?php _e('Proceed with update', 'updraftplus');?>">
		</form>
		<?php
		// Prevent rest of the page - unnecessary since we die() anyway
		// unset($_GET['action']);
		include(ABSPATH . 'wp-admin/admin-footer.php');
		die;
	}

}
