<?php

/**
*
* @author Gremlinn (Nathan DuPra) mods@dupra.net
* @package umil
* @version $Id install.php 1.0.0 2009-11-24 18:15:00Z Gremlinn $
* @copyright (c) 2009 Gremlinn
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'Medal System';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'medals_mod_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'

$language_file = 'mods/umil_auto_example';
*/
/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
//$logo_img = 'styles/prosilver/imageset/site_logo.gif';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 0.10.2
	'0.10.2'	=> array(
		// Lets add a config and set it to true
		'config_add' => array(
			array('medals_active', '1'),
			array('medal_small_img_width', '0'),
			array('medal_small_img_ht', '0'),
			array('medal_profile_across', '5'),
			array('medal_display_topic', '0'),
			array('medal_topic_row', '1'),
			array('medal_topic_col', '1'),
		),

		// Now to add some permission settings
		'permission_add' => array(
			array('u_award_medals', '1'),
			array('a_manage_medals', '1'),
		),

		// How about we give some default permissions then as well?
		'permission_set' => array(
			// Global Role permissions
			array('ROLE_ADMIN_FULL', 'u_award_medals'),
			array('ROLE_ADMIN_FULL', 'a_manage_medals'),
		),

		// Now to add a table (this uses the layout from develop/create_schema_files.php and from phpbb_db_tools)
		'table_add' => array(
			array('phpbb_medals', array(
					'COLUMNS'		=> array(
						'id'			=> array('UINT:11', NULL, 'auto_increment'),
						'name'			=> array('VCHAR:30', ''),
						'image'			=> array('VCHAR:100', ''),
						'dynamic'		=> array('BOOL', '0'),
						'device'		=> array('VCHAR:32', ''),
						'number'		=> array('UINT:2', '1'),
						'parent'		=> array('UINT:5', '0'),
						'nominated'		=> array('BOOL', '0'),
						'order_id'		=> array('UINT:5', '0'),
						'description'	=> array('VCHAR:256', ''),
						'points'		=> array('INT:4','0'),
					),
					'PRIMARY_KEY'	=> 'id',
					'KEY'			=> 'order_id',
				),
			),
			array('phpbb_medals_awarded', array(
					'COLUMNS'		=> array(
						'id'				=> array('UINT:10', NULL, 'auto_increment'),
						'medal_id'			=> array('BINT', '0'),
						'user_id'			=> array('BINT', '0'),
						'awarder_id'		=> array('BINT', '0'),
						'awarder_un'		=> array('VCHAR:255', ''),
						'awarder_color'		=> array('VCHAR:6', ''),
						'time'				=> array('TIMESTAMP', '0'),
						'nominated'			=> array('BOOL', '0'),
						'nominated_reason'	=> array('TEXT', ''),
						'points'			=> array('INT:4','0'),
						'bbuid'				=> array('VCHAR:255', ''),
						'bitfield'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'id',
					'KEY'			=> 'time',
				),
			),
			array('phpbb_medals_cats', array(
					'COLUMNS'		=> array(
						'id'			=> array('UINT:5', NULL, 'auto_increment'),
						'name'			=> array('VCHAR:30', ''),
						'order_id'		=> array('UINT:5', '0'),
					),
					'PRIMARY_KEY'	=> 'id',
					'KEY'			=> 'order_id',
				),
			),
		),

		// insert into a table.
		'table_row_insert'	=> array(
			array('phpbb_medals_cats', array(
				array(
					'name'			=> 'Sample',
					),
				),
			),
			array('phpbb_styles_imageset_data', array(
				array(
					'image_name'		=> 'icon_post_approve',
					'image_filename'	=> 'icon_post_approve.gif',
					'image_height'		=> '20',
					'image_width'		=> '20',
					'imageset_id'		=> '1',
					),
				),
			),
		),
		// at last, modules to make it happen
		'module_add' => array(
			// First, lets add a new category named Medals Control Panel to ACP_CAT_DOT_MODS
			array('acp', 'ACP_CAT_DOT_MODS', 'Medals Control Panel'),

			// next let's add our modules
			array('acp', 'Medals Control Panel', array(
					'module_basename'	=> 'medals',
					'modes'				=> array('config'),
					'module_auth'		=> 'acl_a_manage_medals',
				),
			),
			array('acp', 'Medals Control Panel', array(
					'module_basename'	=> 'medals',
					'modes'				=> array('management'),
					'module_auth'		=> 'acl_a_manage_medals',
				),
			),
		),
	),
	// Version 0.10.3
	'0.10.3'	=> array(
	),
	// Version 0.11.0.
	'0.11.0'	=> array(
	),
	// Version 0.11.1.
	'0.11.1'	=> array(
		// Now to add some permission settings
		'permission_add' => array(
			array('u_nominate_medals', '1'),
		),
	),
	// Version 0.11.2.
	'0.11.2'	=> array(
	),
	// Version 0.20.0.
	'0.20.0'	=> array(
	),
	// Version 0.21.0.
	'0.21.0'	=> array(
	),
);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/*
* @param string $action The action (install|update|uninstall) will be sent through this.
* @param string $version The version this is being run for will be sent through this.
*/
function medals_table($action, $version)
{
	global $db, $table_prefix, $umil;

	if ($action == 'install')
	{
		// Run this when installing

	}
}

?>
