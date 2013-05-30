<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.9.1 2008/02/19 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/
class acp_medals_info
{
	var $u_action;

    function module()
    {
        return array(
            'filename'		=> 'acp_medals',
            'title'			=> 'ACP_MEDALS_INDEX',
            'version'		=> '0.11.0',
            'modes'			=> array(
				'config'		=> array(
					'title' 		=> 'ACP_MEDALS_SETTINGS',
					'auth' 			=> 'acl_a_board',
					'cat' 			=> array('ACP_CAT_USERS'),
				),
                'management'	=> array(
					'title'			=> 'ACP_MEDALS_TITLE',
					'auth'			=> 'acl_a_manage_medals',
					'cat' 			=> array('ACP_CAT_USERS'),
				),
			),
        );
    }

    function install()
    {
	}

    function uninstall()
    {
    }

}

?>