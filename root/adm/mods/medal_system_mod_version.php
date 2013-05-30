<?php
/**
*
* @package acp
* @version $Id: medal_system_mod_version.php 01 2011-10-16 15:11:00Z oddfish $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package medal_system_mod
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class medal_system_mod_version
{
	function version()
	{
		global $config;
		return array(
			'author'	=> 'Jessica',
			'title'		=> 'Medal System Mod for phpbb3',
			'tag'		=> 'mod_version_check',
			'version'	=> '0.21.0',
			'file'		=> array('http://chenschool.elementfx.com/phpBB3.index.php', 'mods', 'medals.xml'),
		);
	}
}

?>
