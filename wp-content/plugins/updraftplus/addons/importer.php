<?php
/*
UpdraftPlus Addon: importer:Import a WordPress backup made by another backup plugin
Description: Import a backup made by other supported WordPress backup plugins (see shop page for a list of supported plugins)
Version: 2.1
Shop: /shop/importer/
Latest Change: 1.9.2
*/

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

$updraftplus_addons_importer = new UpdraftPlus_Addons_Importer;

class UpdraftPlus_Addons_Importer {

	public function __construct() {
		add_filter('updraftplus_accept_archivename', array($this, 'accept_archivename'));
		add_filter('updraftplus_accept_archivename_js', array($this, 'accept_archivename_js'));
		add_filter('updraftplus_accept_foreign', array($this, 'accept_foreign'), 10, 2);
		add_filter('updraftplus_importforeign_backupable_plus_db', array($this, 'importforeign_backupable_plus_db'), 10, 5);
		add_filter('updraftplus_foreign_gettime', array($this, 'foreign_gettime'), 10, 3);
		add_filter('updraftplus_foreign_separatedbname', array($this, 'foreign_separatedbname'), 10, 4);
	}

	# Given a backup type and filename, get the time
	public function foreign_gettime($btime, $accepted_foreign, $entry) {
		$plugins = $this->accept_archivename(array());
		if (empty($plugins[$accepted_foreign])) return $btime;
		# mktime(): H, M, S, M, D, Y
		switch ($accepted_foreign) {
			case 'backupwordpress':
			# e.g. example-com-default-1-complete-2014-03-10-11-44-57.zip
			if (preg_match('/(([0-9]{4})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2})-([0-9]{2}))\.zip$/i', $entry, $tmatch)) {
				return mktime($tmatch[5], $tmatch[6], $tmatch[7], $tmatch[3], $tmatch[4], $tmatch[2]);
			}
			break;
			case 'simple_backup':
			# e.g. db_backup_2014-03-15_133344.sql.gz | backup-2014-03-15-133345.zip
			# Note that a backup of both files and DB started at the same time may not have the same timestamp on both entities
			# Can also do tar and tar.gz and tar.bz2
			if (preg_match('/^(db_)?backup.([0-9]{4})-([0-9]{2})-([0-9]{2}).([0-9]{2})([0-9]{2})([0-9]{2})\.(zip|tar(\.(bz2|gz))?|sql(\.(gz))?)$/i', $entry, $tmatch)) {
				$btime = mktime($tmatch[5], $tmatch[6], $tmatch[7], $tmatch[3], $tmatch[4], $tmatch[2]);
				return $btime - ($btime % 60);
			}
			case 'backwpup':
			# e.g. backwpup_430908_2014-03-30_11-41-05.tar
			if (preg_match('/^backwpup_[0-9a-f]+_([0-9]{4})-([0-9]{2})-([0-9]{2})_([0-9]{2})-([0-9]{2})-([0-9]{2})\.(zip|tar|tar\.gz|tar\.bz2)/i', $entry, $tmatch)) {
				return mktime($tmatch[4], $tmatch[5], $tmatch[6], $tmatch[2], $tmatch[3], $tmatch[1]);
			}
			break;
		}
		return $btime;
	}

	public function foreign_separatedbname($db_basename, $fsource, $backupinfo, $working_dir_localpath) {
		if ('backwpup' == $fsource) {
			if (is_file($working_dir_localpath.'/manifest.json')) {
				$manifest = file_get_contents($working_dir_localpath.'/manifest.json');
				if (false != $manifest) {
					$decode = json_decode($manifest);
					if (!empty($decode) && is_object($decode) && is_object($decode->job_settings)) {
						$js = $decode->job_settings;
						if (!empty($js->dbdumptype) && 'sql' == $js->dbdumptype && !empty($js->dbdumpfile) && file_exists($working_dir_localpath.'/'.$js->dbdumpfile.'.sql')) return $js->dbdumpfile.'.sql';
					}
				}
			}
			return false;
		} else {
			$db_basename = $this->ud_backup_info['wpcore'];
			if (is_array($db_basename)) $db_basename = array_shift($db_basename);
			$db_basename = basename($db_basename, '.zip').'.sql';
		}
		return $db_basename;
	}

	public function importforeign_backupable_plus_db($backupable_plus_db, $foinfo, $mess, $warn, $err) {
		$mess[] = sprintf(__('Backup created by: %s.', 'updraftplus'), $foinfo['desc']);
		return array('wpcore');
	}

	# Scan filename and see if we recognise its pattern
	public function accept_foreign($accepted_foreign, $entry) {
		$accept = $this->accept_archivename(array());
		foreach ($accept as $fsource => $acc) {
			if (preg_match('/'.$acc['pattern'].'/i', $entry)) $accepted_foreign = $fsource;
		}
		return $accepted_foreign;
	}

	# Return array of supported backup types
	public function accept_archivename($x) {
		if (!is_array($x)) return $x;

		$x['backupwordpress'] = array(
			'desc' => 'BackUpWordPress',
			'pattern' => 'complete-[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}\\.zip$',
			'separatedb' => false
		);

		$x['simple_backup'] = array(
			'desc' => 'Simple Backup',
			'pattern' => '^(db_)?backup.([0-9]{4})-([0-9]{2})-([0-9]{2}).([0-9]{2})([0-9]{2})([0-9]{2})\\.(zip|tar(\\.(bz2|gz))?|sql(\\.(gz))?)$',
			'separatedb' => true
		);

		$x['backwpup'] = array(
			'desc' => 'BackWPup',
			'pattern' => '^backwpup_[0-9a-f]+_([0-9]{4})-([0-9]{2})-([0-9]{2})_([0-9]{2})-([0-9]{2})-([0-9]{2})\\.(zip|tar(\\.(gz|bz2))?)$',
			'separatedb' => false
		);

		return $x;
	}

	# Return JavaScript array of supported backup types
	public function accept_archivename_js($x) {
		#backup_([\-0-9]{15})_.*_([0-9a-f]{12})-[\-a-z]+([0-9]+(of[0-9]+)?)?\.(zip|gz|gz\.crypt)
		$accepted = $this->accept_archivename(array());
		$x = '[ ';
		$ind = 0;
		foreach ($accepted as $acc) {
			if ($ind>0) $x .= ', ';
			$x .= "/".esc_js($acc['pattern'])."/i";
			$ind++;
		}
		return $x.' ]';
	}

}