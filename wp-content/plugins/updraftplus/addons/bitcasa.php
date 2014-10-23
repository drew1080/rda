<?php
/*
UpdraftPlus Addon: bitcasa:Bitcasa Support
Description: Allows UpdraftPlus to back up to Bitcasa cloud storage
Version: 1.4
Shop: /shop/bitcasa/
Include: includes/PEAR
IncludePHP: methods/addon-base.php
RequiresPHP: 5.3.3
Latest Change: 1.9.20
*/

# TODO: Test on PHP 5.2, and remove the RequiresPHP if it works (can't see why it wouldn't)

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

/*
do_bootstrap($possible_options_array, $connect = true) # Return a WP_Error object if something goes wrong
do_upload($file) # Return true/false
do_listfiles($match)
do_delete($file) - return true/false
do_download($file, $fullpath, $start_offset) - return true/false
do_config_print()
do_config_javascript()
do_credentials_test_parameters() - return an array: keys = required _POST parameters; values = description of each
do_credentials_test($testfile) - return true/false
do_credentials_test_deletefile($testfile)
*/

if (!class_exists('UpdraftPlus_RemoteStorage_Addons_Base')) require_once(UPDRAFTPLUS_DIR.'/methods/addon-base.php');

class UpdraftPlus_Addons_RemoteStorage_bitcasa extends UpdraftPlus_RemoteStorage_Addons_Base {

	private $ids_from_paths;

	public function __construct() {
		# 3rd parameter: chunking? 4th: Test button?
		parent::__construct('bitcasa', 'Bitcasa', false, false);
		add_filter('updraft_bitcasa_action_auth', array($this, 'action_auth'));
	}

	public function do_upload($file, $from) {

		global $updraftplus;
		$message = "Bitcasa user/profile did not return the expected data";
		
		$base_url = $this->storage->get_base_url();

		try {
			$profile = wp_remote_get($base_url.'/user/profile?access_token='.$this->options['token'], array('timeout' => 8));
			if (!is_array($profile) || empty($profile['response']) || empty($profile['response']['code']) || $profile['response']['code'] >= 300 || $profile['response']['code'] < 200 || empty($profile['body']) || (null === ($prof = json_decode($profile['body'])))) {
				# Not as expected
				if (is_array($profile) && !empty($profile['response']) && is_array($profile['response']) && !empty($profile['response']['code']) && 401 == $profile['response']['code']) {
					if (null !== ($prof = json_decode($profile['body'])) && is_object($prof) && !empty($prof->error) && is_object($prof->error) && !empty($prof->error->message)) {
						$message .= " (".$prof->error->message.")";
					} else {
						$message .= ' (401)';
					}
					
				}
			} else {
				$res = $prof->result;
				if (!empty($res->display_name)) {
					$quota_info = $res->storage;
					$total_quota = max($quota_info->total, 0);
					$normal_quota = $quota_info->used;
					$available_quota = ($total_quota > 0 ) ? $total_quota - $normal_quota : PHP_INT_MAX;
					$used_perc = ($total_quota > 0) ? round($normal_quota*100/$total_quota, 1) : 0;
					$message = sprintf('Your %s quota usage: %s %% used, %s available', 'Bitcasa', $used_perc, round($available_quota/1048576, 1).' Mb');
				}
				// We don't actually abort now - there's no harm in letting it try and then fail
				$filesize = filesize($from);
				$offset = 0;
				if (isset($available_quota) && $available_quota != -1 && $available_quota < $filesize) {
					$updraftplus->log("File upload expected to fail: file data remaining to upload ($file) size is ".($filesize-$offset)." b (overall file size; $filesize b), whereas available quota is only $available_quota b");
					$updraftplus->log(sprintf(__("Account full: your %s account has only %d bytes left, but the file to be uploaded has %d bytes remaining (total size: %d bytes)",'updraftplus'),'Bitcasa', $available_quota, $filesize-$offset, $filesize), 'error');
				}
			}
		} catch (Exception $e) {
			$message .= " ".get_class($e).": ".$e->getMessage();
		}
		$updraftplus->log($message);

		$folder = (empty($this->options['folder'])) ? '' : $this->options['folder'];

		$item = $this->item_from_path($folder);

		#uploadFile($path, $filepath, $name = NULL, $exists="rename");
		$upload = $this->storage->uploadFile($item->getPath(), $from, $file, 'overwrite');
		return is_a($upload, 'BitcasaFile');

	}

	public function do_download($file, $fullpath, $start_offset) {

		global $updraftplus;

		$folder = (empty($this->options['folder'])) ? '' : $this->options['folder'];

		$item = $this->item_from_path($folder, false);
		if (false === $item) {
			$updraftplus->log("Bitcasa: $folder: no such folder");
			return false;
		}

		$sub_items = $this->storage->listItem($item);
		$item = BitcasaItem::findByName($file, $sub_items);

		if (!is_a($item, 'BitcasaFile')) {
			$updraftplus->log("Bitcasa: $file: file was not found (".serialize($item).")");
			return false;
		}

		$remote_size = $item->getSize();

		if (false === $remote_size) {
			$updraftplus->log("Bitcasa: $file: Could not access the object");
			$updraftplus->log(sprintf(__('The %s object was not found', 'updraftplus'), 'Bitcasa'), 'error');
			return false;
		}

		return $updraftplus->chunked_download($file, $this, $remote_size, true, $item);

		

	}

	public function chunked_download($file, $headers, $item) {
		$fields = array('path' => $item->getPath());
		return $this->storage->http_get_file("/files/$file", $fields, $headers);
	}

	public function do_delete($file) {
		global $updraftplus;

		$folder = (empty($this->options['folder'])) ? '' : $this->options['folder'];

		$item = $this->item_from_path($folder, false);
		if (false === $item) {
			$updraftplus->log("Bitcasa: $folder: no such folder");
			return false;
		}

		$sub_items = $this->storage->listItem($item);
		$item = BitcasaItem::findByName($file, $sub_items);

		if (!is_a($item, 'BitcasaFile')) {
			$updraftplus->log("Bitcasa: $file: file was not found (".serialize($item).")");
			return false;
		}

		$remove = $this->storage->removeFile($item->getPath());

		return is_a($remove, 'BitcasaFile');

	}

	private function item_from_path($path, $create = true) {

		while ('/' == substr($path, 0, 1)) { $path = substr($path, 1); }

		$cache_key = (empty($path)) ? '/' : $path;

		if (!empty($this->ids_from_paths) && isset($this->ids_from_paths[$cache_key])) return $this->ids_from_paths[$cache_key];

		$current_item = $this->storage->getInfiniteDrive();

		if (!empty($path)) {
			foreach (explode('/', $path) as $i => $element) {
				$found = false;

				#$sub_items = (0 == $i) ?  BitcasaInfiniteDrive::listAll($this->storage) : $this->storage->listItem($current_item); ;
				$sub_items = $this->storage->listItem($current_item);

				$item = BitcasaItem::findByName($element, $sub_items);
				
				if (null === $item) {
					if (!$create) return false;
					$current_item = $this->storage->createFolder($current_item->getPath(), $element);
				} else {
					if ($item->getType() != '1') return false;
					$current_item = $item;
				}
				
			}
		}

		$this->ids_from_paths[$cache_key] = $current_item;

		return $current_item;

	}

	public function do_listfiles($match = 'backup_') {
		
		$path = (empty($this->options['folder'])) ? '/' : $this->options['folder'];

		$item = $this->item_from_path($path);

		$items = $this->storage->listItem($item);

		if (!is_array($items)) return array();
		
		$results = array();

		foreach ($items as $item) {
			$name = $item->getName();
			if (0 === strpos($name, $match)) {
				$results[] = array('name' => $name, 'size' => $item->getSize());
			}
		}

		return $results;

	}

	private function get_app_creds() {
		return apply_filters('updraftplus_appcreds_bitcasa', array($this->options['clientid'], $this->options['secret']));
	}

	public function do_bootstrap($opts, $connect = true) {
		if (!class_exists('BitcasaClient_WP')) require_once(UPDRAFTPLUS_DIR.'/includes/BitcasaClient.php');
		//$base_url = empty($opts['server']) ? '' : 'https://'.$opts['server'].'/v2';
		$base_url = '';
		$bc = new BitcasaClient_WP($base_url);
		if ($connect) {
			$bc->setAccessToken($opts['token']);
		}
		return $bc;
	}

	protected function options_exist($opts) {
		if (is_array($opts) && !empty($opts['token']) && !empty($opts['clientid']) && !empty($opts['secret'])) return true;
		return false;
	}

	public function action_auth() {

		global $updraftplus;
		$this->options = $this->get_opts();

		if (isset($_REQUEST['authorization_code'])) {
			$this->options = $this->get_opts();
			$this->storage = $this->bootstrap(false, false);
			# Now, exchange the authorization code for an access token
			if (is_wp_error($this->storage)) { global $updraftplus; return $updraftplus->log_wp_error($this->storage, false, true); }

			$creds = $this->get_app_creds();
			if (empty($creds[0]) || empty($creds[1])) {
				$updraftplus->log(sprintf(__('You have not yet configured and saved your %s credentials', 'updraftplus'), 'Bitcasa'), 'error');
				return false;
			}

			$authed = $this->storage->authenticate($creds[0], $creds[1]);

			if (true !== $authed) {
				$result = $this->storage->result;
				if (is_array($result) && !empty($result['error'])) {
					$message = (is_string($result['error'])) ? $result['error'] : serialize($result['error']);
				} else {
					$message = __('Unknown server response:','updraftplus').' '.serialize($result);
				}
				$updraftplus->log(sprintf(__("%s error: %s", 'updraftplus'), sprintf(__("%s authentication", 'updraftplus'), 'Bitcasa'), $message), 'error');
				return false;
			}

			$token = $this->storage->getAccessToken();

			if ($token) {
				$this->token = $token;
				$this->options['token'] = $token;
				UpdraftPlus_Options::update_updraft_option('updraft_bitcasa', $this->options);
				add_action('all_admin_notices', array($this, 'show_authed_admin_warning'));
			}

		} elseif (isset($_GET['updraftplus_bitcasaauth'])) {

			// Clear out the existing credentials
			if ('doit' == $_GET['updraftplus_bitcasaauth']) {
				unset($this->options['token']);
				unset($this->options['ownername']);
				UpdraftPlus_Options::update_updraft_option('updraft_bitcasa', $this->options);
			}
			try {
				$this->auth_request();
			} catch (Exception $e) {
				global $updraftplus;
				$updraftplus->log(sprintf(__("%s error: %s", 'updraftplus'), sprintf(__("%s authentication", 'updraftplus'), 'Bitcasa'), $e->getMessage()), 'error');
			}
		}
	}

	public function show_authed_admin_warning() {
		global $updraftplus_admin, $updraftplus;

		$storage = $this->storage;
		if (empty($this->token) || !is_a($storage, 'BitcasaClient_WP')) return false;

		$message = "<strong>".__('Success:', 'updraftplus').'</strong> '.sprintf(__('you have authenticated your %s account', 'updraftplus'),'Bitcasa');
		# We log, because otherwise people get confused by the most recent log message and raise support requests
		$updraftplus->log(__('Success:', 'updraftplus').' '.sprintf(__('you have authenticated your %s account', 'updraftplus'),'Bitcasa'));

		$base_url = $storage->get_base_url();

		# BITCASA_BASE_URL is defined inside the SDK
		$profile = wp_remote_get($base_url.'/user/profile?access_token='.$this->token, array('timeout' => 8));

		if (!is_array($profile) || empty($profile['response']) || empty($profile['response']['code']) || $profile['response']['code'] >= 300 || $profile['response']['code'] < 200 || empty($profile['body']) || (null === ($prof = json_decode($profile['body'])))) {
			$message .= " (".__('though part of the returned information was not as expected - your mileage may vary','updraftplus').") - ".serialize($profile);
		} elseif (is_object($prof) && is_object($prof->result)) {
			
			try {
				$res = $prof->result;
				$opts = $this->get_opts();
				if (!empty($res->display_name)) {
					$opts['ownername'] = $res->display_name;
					UpdraftPlus_Options::update_updraft_option('updraft_bitcasa', $opts);

					$message .= ". <br>".sprintf(__('Your %s account name: %s','updraftplus'), 'Bitcasa', htmlspecialchars($res->display_name));

					$quota_info = $res->storage;
					$total_quota = max($quota_info->total, 0);
					$normal_quota = $quota_info->used;
					$available_quota = ($total_quota > 0 ) ? $total_quota - $normal_quota : PHP_INT_MAX;
					$used_perc = ($total_quota > 0) ? round($normal_quota*100/$total_quota, 1) : 0;
					$message .= ' <br>'.sprintf(__('Your %s quota usage: %s %% used, %s available','updraftplus'), 'Bitcasa', $used_perc, round($available_quota/1048576, 1).' Mb');

				}
			} catch (Exception $e) {
			}

		}
		$updraftplus_admin->show_admin_warning($message);

	}

	private function auth_request() {

		$this->storage = $this->bootstrap(false, false);
		if (is_wp_error($this->storage)) { global $updraftplus; return $updraftplus->log_wp_error($this->storage, false, true); }

		$creds = $this->get_app_creds();

		if (empty($creds[0]) || empty($creds[1])) throw new Exception(sprintf(__('You have not yet configured and saved your %s credentials', 'updraftplus'), 'Bitcasa'));

		$authurl = $this->storage->authorize($creds[0], UpdraftPlus_Options::admin_page_url().'?action=updraftmethod-bitcasa-auth');

		if (!headers_sent()) {
			header('Location: '.$authurl);
			exit;
		} else {
			throw new Exception(sprintf(__('The %s authentication could not go ahead, because something else on your site is breaking it. Try disabling your other plugins and switching to a default theme. (Specifically, you are looking for the component that sends output (most likely PHP warnings/errors) before the page begins. Turning off any debugging settings may also help).', 'updraftplus'), 'Bitcasa'));
		}
	}

	public function do_config_print($opts) {
		global $updraftplus_admin;

		$folder = (empty($this->options['folder'])) ? '' : $this->options['folder'];
		$clientid = (empty($this->options['clientid'])) ? '' : $this->options['clientid'];
		$secret = (empty($this->options['secret'])) ? '' : $this->options['secret'];
		$server = (empty($this->options['server'])) ? '' : $this->options['server'];

		$updraftplus_admin->storagemethod_row(
			'bitcasa',
			'',
			'<img src="'.UPDRAFTPLUS_URL.'/images/bitcasa.png"><br>'.
			'<strong>'.__('Bitcasa has removed its consumer API. You can no longer create new Bitcasa applications. Settings remain here only for the use of pre-existing users.', 'updraftplus').'</strong><br>'.
			'<a href="https://consumerapi.bitcasa.com/admin/applications">'.sprintf(__('To get your credentials, log in at the %s developer portal.', 'updraftplus'), 'Bitcasa').'</a>'.
			' '.__("After logging in, create a sandbox app. You can leave all of the questions for creating an app blank (except for the app's name).", 'updraftplus')
		);

		$updraftplus_admin->storagemethod_row(
			'bitcasa',
			'Bitcasa '.__('Client ID', 'updraftplus'),
			'<input type="text" style="width:442px" name="updraft_bitcasa[clientid]" value="'.esc_attr($clientid).'">'
		);

		$updraftplus_admin->storagemethod_row(
			'bitcasa',
			'Bitcasa '.__('Client Secret', 'updraftplus'),
			'<input type="text" style="width:442px" name="updraft_bitcasa[secret]" value="'.esc_attr($secret).'">'
		);

// 		$updraftplus_admin->storagemethod_row(
// 			'bitcasa',
// 			'Bitcasa '.__('API Server', 'updraftplus'),
// 			'<input type="text" title="'.__('Enter only the server hostname - not a full URL', 'updraftplus').'" style="width:442px" name="updraft_bitcasa[server]" value="'.esc_attr($server).'">'.
// 			'<br><em>'.__('Leave this blank if you are using the old (pre-Cloud FS) Bitcasa consumer API platform', 'updraftplus').'</em>'
// 		);

		$updraftplus_admin->storagemethod_row(
			'bitcasa',
			'Bitcasa '.__('Folder', 'updraftplus'),
			'<input title="'.esc_attr(sprintf(__('Enter the path of the %s folder you wish to use here.', 'updraftplus'), 'Bitcasa').' '.__('If the folder does not already exist, then it will be created.').' '.sprintf(__('e.g. %s', 'updraftplus'), 'MyBackups/WorkWebsite.').' '.sprintf(__('If you leave it blank, then the backup will be placed in the root of your %s', 'updraftplus'), 'Bitcasa')).'" type="text" style="width:442px" name="updraft_bitcasa[folder]" value="'.esc_attr($folder).'">'
		);

		$updraftplus_admin->storagemethod_row(
			'bitcasa', 
			sprintf(__('Authenticate with %s', 'updraftplus'), __('Bitcasa', 'updraftplus')).':',
			'<p>'.(!empty($opts['token']) ? "<strong>".__('(You appear to be already authenticated).', 'updraftplus').'</strong>' : '').
			((!empty($opts['token']) && !empty($opts['ownername'])) ? ' '.sprintf(__("Account holder's name: %s.", 'updraftplus'), htmlspecialchars($opts['ownername'])).' ' : '').
			'</p><p><a href="?page=updraftplus&action=updraftmethod-bitcasa-auth&updraftplus_bitcasaauth=doit">'.sprintf(__('<strong>After</strong> you have saved your settings (by clicking \'Save Changes\' below), then come back here once and click this link to complete authentication with %s.','updraftplus'), __('Bitcasa', 'updraftplus')).'</a></p>'.
			$updraftplus_admin->curl_check('Bitcasa', false, 'bitcasa', false)
		);
	}

}

$updraftplus_addons_bitcasa = new UpdraftPlus_Addons_RemoteStorage_bitcasa;
