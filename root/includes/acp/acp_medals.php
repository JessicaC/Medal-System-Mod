<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/14 Gremlinn$
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
* @package acp
*/
class acp_medals
{
	var $u_action;
	var $new_config;
	
	function main($id, $mode)
    {
	
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;

		include($phpbb_root_path . 'includes/acp/info/acp_medals.' . $phpEx);

		$user->add_lang('acp/modules');

		$action	= request_var('action', '');
		$submode = request_var('submode', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		switch($mode)
		{
			case 'config':
				$display_vars = array(
					'title'	=> 'ACP_MEDALS_INDEX',
					'vars'	=> array(
						'legend1'				=> 'ACP_MEDALS_CONF_SETTINGS',
						'medals_active' 		=> array('lang' => 'ACP_MEDALS_ACTIVATE',		'validate' => 'int',	'type' => 'radio:yes_no', 'explain' => false),
						'medal_small_img_width' => array('lang' => 'ACP_MEDALS_SM_IMG_WIDTH',	'validate' => 'int',	'type' => 'text:3:3', 'explain' => true),
						'medal_small_img_ht'	=> array('lang' => 'ACP_MEDALS_SM_IMG_HT',		'validate' => 'int',	'type' => 'text:3:3', 'explain' => true),
						'medal_profile_across'	=> array('lang' => 'ACP_MEDALS_PROFILE_ACROSS', 'validate' => 'int',	'type' => 'text:2:2', 'explain' => true),
						'legend2'				=> 'ACP_MEDALS_VT_SETTINGS',
						'medal_display_topic'	=> array('lang' => 'ACP_MEDALS_TOPIC_DISPLAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),
						'medal_topic_row' 		=> array('lang' => 'ACP_MEDALS_TOPIC_ROW',		'validate' => 'int',	'type' => 'text:2:2', 'explain' => true),
						'medal_topic_col'		=> array('lang' => 'ACP_MEDALS_TOPIC_COL',		'validate' => 'int',	'type' => 'text:1:1', 'explain' => true),
					)
				);
				if (isset($display_vars['lang']))
				{
					$user->add_lang($display_vars['lang']);
				}
				$this->new_config = $config;
				$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
				$error = array();

				// We validate the complete config if whished
				validate_config_vars($display_vars['vars'], $cfg_array, $error);

				// Do not write values if there is an error
				if (sizeof($error))
				{
					$submit = false;
				}

				// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
				foreach ($display_vars['vars'] as $config_name => $null)
				{
					if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

					if ($submit)
					{
						set_config($config_name, $config_value);
					}
				}

				if ($submit)
				{
					trigger_error(sprintf($user->lang['ACP_MEDALS_CONF_SAVED'], append_sid('index.php?i=' . $id . '&mode=config')));
					break ;
				}
				$this->tpl_name = 'acp_medals_config';
                $this->page_title = $user->lang['ACP_MEDALS_INDEX'];

				$template->assign_vars(array(
					'L_TITLE'			=> $user->lang[$display_vars['title']],
					'L_TITLE_EXPLAIN'	=> $user->lang[$display_vars['title'] . '_EXPLAIN'],

					'S_ERROR'			=> (sizeof($error)) ? true : false,
					'ERROR_MSG'			=> implode('<br />', $error),

					'U_ACTION'			=> $this->u_action,
				));

				// Output relevant page
				foreach ($display_vars['vars'] as $config_key => $vars)
				{
					if (!is_array($vars) && strpos($config_key, 'legend') === false)
					{
						continue;
					}

					if (strpos($config_key, 'legend') !== false)
					{
						$template->assign_block_vars('options', array(
							'S_LEGEND'		=> true,
							'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
						);

						continue;
					}

					$type = explode(':', $vars['type']);

					$l_explain = '';
					if ($vars['explain'] && isset($vars['lang_explain']))
					{
						$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
					}
					else if ($vars['explain'])
					{
						$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
					}

					$template->assign_block_vars('options', array(
						'KEY'			=> $config_key,
						'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
						'S_EXPLAIN'		=> $vars['explain'],
						'TITLE_EXPLAIN'	=> $l_explain,
						'CONTENT'		=> build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars),
					));
				
					unset($display_vars['vars'][$config_key]);
				}
				break;

			case 'management':
				$sql = 'SELECT *
					FROM ' . MEDALS_TABLE . '
					ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$medals = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$medals[$row['id']] = array( 
						'name' 		=> $row['name'], 
						'description' => $row['description'], 
						'image'	 	=> $row['image'], 
						'device' 	=> $row['device'], 
						'dynamic'	=> $row['dynamic'],
						'number'	=> $row['number'],
						'points'	=> $row['points'],
						'parent' 	=> $row['parent'], 
						'id'		=> $row['id'],
						'nominated'	=> $row['nominated'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT *
					FROM ' . MEDALS_CATS_TABLE . '
					ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$cats = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$cats[$row['id']] = array( 
						'name' 		=> $row['name'], 
						'id'		=> $row['id'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
				$cat_id = request_var('catid', -1);
				$medal_id = request_var('medalid', -1);
				$move_id = request_var('moveid', -1);
				$move_type = request_var('movetype', -1);
				$submode = request_var('submode', '');
				if (isset($_POST['addcat']))
				{
					$submode = 'addcat';
				}
				else if (isset($_POST['addmedal']))
				{
					$submode = 'addmedal';
				}
				else if (isset($_POST['cancelcat']))
				{
					$submode = '';
				}
				else if (isset($_POST['cancelmedal']))
				{
					$submode = 'catview';
				}
				break;
		}

		switch($submode)
		{
			case 'move':
				if ($move_type)
				{
					$swap_diff = 1;
				}
				else
				{
					$swap_diff = -1;
				}
				$sql = 'UPDATE ' . MEDALS_TABLE . '
							SET order_id = ' . $medals[$move_id]['order_id'] . '
							WHERE order_id = ' . $medals[$move_id]['order_id'] . '+' . $swap_diff . '
								AND parent = ' . $cat_id;
				$db->sql_query($sql);
				$sql = 'UPDATE ' . MEDALS_TABLE . '
							SET order_id = ' . $medals[$move_id]['order_id'] . '+' . $swap_diff . '
							WHERE id = ' . $move_id;
				$db->sql_query($sql);
				$sql = 'SELECT *
						FROM ' . MEDALS_TABLE . '
						ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$medals = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$medals[$row['id']] = array( 
						'name' 		=> $row['name'], 
						'image'	 	=> $row['image'], 
						'device'	=> $row['device'], 
						'dynamic'	=> $row['dynamic'],
						'number'	=> $row['number'],
						'points'	=> $row['points'],
						'parent' 	=> $row['parent'], 
						'id'		=> $row['id'],
						'nominated'	=> $row['nominated'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
			case 'catview':
			
				if ($cat_id < 0)
				{
					trigger_error('NO_CAT_ID');
				}
				
                $this->tpl_name = 'acp_medals_cat';
                $this->page_title = $user->lang['ACP_MEDALS_INDEX'];
				foreach($medals as $key => $value)
				{
					if ($value['parent'] != $cat_id)
					{
						continue;
					}
					$template->assign_block_vars('medals', array(
						'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editmedal&medalid=' . $value['id'] . '&catid=' . $cat_id),
						'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletemedal&medalid=' . $value['id'] . '&catid=' . $cat_id),
						'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=move&moveid=' . $value['id'] . '&movetype=0&catid=' . $cat_id),
						'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=move&moveid=' . $value['id'] . '&movetype=1&catid=' . $cat_id),
						'MEDAL_NOMINATED'	=> ($value['nominated']) ? $user->lang['YES'] : $user->lang['NO'],
						'MEDAL_NUMBER'		=> $value['number'],
						'MEDAL_IMAGE'		=> '<img src="' . $phpbb_root_path . 'images/medals/' . $value['image'] . '" title="' . $value['name'] . '" style="max-width: 60px; max-height: 60px;"/>',
						'MEDAL_TITLE'		=> $value['name'],
						'S_IS_MEDAL'		=> true,
					));
				}
                $template->assign_vars(array(
					'CAT_TITLE' => $cats[$cat_id]['name'],
				));
                break;
			
			case 'deletemedal':
				if (!isset($_POST['confirm']))
				{
					trigger_error('ACP_CONFIRM_MSG_1');
				}
				if ($medal_id < 0)
				{
					trigger_error('ACP_NO_MEDAL_ID');
				}
				if ($cat_id < 0)
				{
					trigger_error('ACP_NO_CAT_ID');
				}
				$sql = 'DELETE FROM ' . MEDALS_TABLE . ' WHERE id = ' . $medal_id;
				$db->sql_query($sql);
				$sql = 'DELETE FROM ' . MEDALS_AWARDED_TABLE . ' WHERE medal_id = ' . $medal_id;
				$db->sql_query($sql);
				trigger_error(sprintf($user->lang['ACP_MEDAL_DELETE_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $cat_id)));
				break;
			
			case 'editmedal':
				
				if ($medal_id < 0)
				{
					trigger_error('NO_MEDAL_ID');
				}
				if ($cat_id < 0)
				{
					trigger_error('NO_CAT_ID');
				}
				
                $this->tpl_name = 'acp_medals_new';
                $this->page_title = $user->lang['ACP_MEDALS_INDEX'];
				
				$dir = $phpbb_root_path . 'images/medals/';
				$options = '<option value=""></option>';
				if ($dh = opendir($dir))
				{
					while (($file = readdir($dh)) !== false)
					{
						if (strlen($file) >= 3 && ( strpos($file, '.gif',1) || strpos($file, '.jpg',1) || strpos($file, '.png',1) ))
						{
							if ($medals[$medal_id]['image'] == $file)
							{
								$options .= '<option value="' . $file . '" selected="selected">' . $file . '</option>';
							}
							else
							{
								$options .= '<option value="' . $file . '">' . $file . '</option>';
							}
						}
					}
					closedir($dh);
				}
				
				$options2 = '';
				foreach($cats as $key => $value)
				{
					if ($medals[$medal_id]['parent'] == $value['id'])
					{
						$options2 .= '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
					}
					else
					{
						$options2 .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
					}
				}

                $template->assign_vars(array(
					'MEDAL_TITLE'			=> $user->lang['ACP_MEDAL_TITLE_EDIT'],
					'MEDAL_TEXT'			=> $user->lang['ACP_MEDAL_TEXT_EDIT'],
					'NAME_VALUE'			=> $medals[$medal_id]['name'],
					'DESC_VALUE'			=> $medals[$medal_id]['description'],
					'MEDAL_IMAGE'			=> '<br /><img src="' . $phpbb_root_path . 'images/medals/' . $medals[$medal_id]['image'] . '" alt="" style="max-width: 60px; max-height: 60px;" />',
					'IMAGE_OPTIONS'			=> $options,
					'DYNAMIC_CHECKED_NO'	=> ($medals[$medal_id]['dynamic']) ? '' : 'checked="checked"',
					'DYNAMIC_CHECKED_YES'	=> ($medals[$medal_id]['dynamic']) ? 'checked="checked"' : '',
					'DEVICE_VALUE'			=> $medals[$medal_id]['device'],
					'NUMBER_VALUE'			=> $medals[$medal_id]['number'],
					'POINTS_VALUE'			=> $medals[$medal_id]['points'],
					'PARENT_OPTIONS'		=> $options2,
					'NOMINATED_CHECKED_NO'	=> ($medals[$medal_id]['nominated']) ? '' : 'checked="checked"',
					'NOMINATED_CHECKED_YES'	=> ($medals[$medal_id]['nominated']) ? 'checked="checked"' : '',
					'MEDAL_ACTION'			=> 'changemedal',
					'MEDAL_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editmedalsql&medalid=' . $medal_id . '&catid=' . $cat_id),
					'PHPBB_ROOT_PATH'		=> $phpbb_root_path,
				));
				break;
			
			case 'editmedalsql':
				
				$this_id = $medals[$medal_id]['order_id'];
				if ($medals[$medal_id]['parent'] != request_var('parent', ''))
				{
					$this_id = 1;
					foreach ($medals as $key => $value)
					{
						if ($value['parent'] == request_var('parent', ''))
						{
							$this_id = $value['order_id'] + 1;
						}
					}
				}
				$sql = 'UPDATE ' . MEDALS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', array(
					'name'		=> utf8_normalize_nfc(request_var('name', '', true)),
					'image'		=> request_var('image', '', true),
					'device'	=> utf8_normalize_nfc(request_var('device', '', true)),
					'dynamic'	=> request_var('dynamic', '', true),
					'number'	=> request_var('number', '', true),
					'parent'	=> request_var('parent', '', true),
					'nominated'	=> request_var('nominated', '', true),
					'order_id'	=> $this_id,
					'description' => utf8_normalize_nfc(request_var('description', '', true)),
					'points'	=> request_var('points', '', true),
				)) . ' WHERE id = ' . $medal_id;
				$db->sql_query($sql);
				foreach ($cats as $key => $value)
				{
					if ($value['id'] == request_var('parent', ''))
					{
						$newcat = $key;
						break;
					}
				}	
				trigger_error(sprintf($user->lang['ACP_MEDAL_EDIT_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $newcat)));
				break;
				
			case 'addcat':
			
				$cat_name = utf8_normalize_nfc(request_var('medal_catname', '', true));

				if (empty($cat_name))
				{
					trigger_error(sprintf($user->lang['ACP_CAT_ADD_FAIL'], append_sid('index.php?i=' . $id . '&mode=management')));
				}
				$this_id = 1;
				foreach ($cats as $key => $value)
				{
					$this_id++;
				}
				$sql = 'INSERT INTO ' . MEDALS_CATS_TABLE . ' ' .$db->sql_build_array('INSERT', array(
					'name'		=> utf8_normalize_nfc(request_var('medal_catname', '', true)),
					'order_id'	=> $this_id,
				));
				$db->sql_query($sql);
				trigger_error(sprintf($user->lang['ACP_CAT_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management')));
				break;
				
			case 'addmedalsql':
				
				$this_id = 1;
				foreach ($medals as $key => $value)
				{
					if ($value['parent'] == request_var('parent', ''))
					{
						$this_id = $value['order_id'] + 1;
					}
				}
				$sql = 'INSERT INTO ' . MEDALS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
					'name'		=> utf8_normalize_nfc(request_var('name', '', true)),
					'image'		=> request_var('image', '', true),
					'device'	=> utf8_normalize_nfc(request_var('device', '', true)),
					'dynamic'	=> request_var('dynamic', '', true),
					'number'	=> request_var('number', '', true),
					'parent'	=> request_var('parent', '', true),
					'nominated'	=> request_var('nominated', '', true),
					'order_id'	=> $this_id,
					'description' => utf8_normalize_nfc(request_var('description', '', true)),
					'points'	=> request_var('points', '', true),
				));
				$db->sql_query($sql);
				foreach ($cats as $key => $value)
				{
					if ($value['id'] == request_var('parent', ''))
					{
						$newcat = $key;
						break;
					}
				}
				trigger_error(sprintf($user->lang['ACP_MEDAL_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $newcat)));
				break;
				
			case 'addmedal':
			
				if ($cat_id < 0)
				{
					trigger_error('ACP_NO_CAT_ID');
				}

                $this->tpl_name = 'acp_medals_new';
                $this->page_title = $user->lang['ACP_MEDALS_INDEX'];
				
				$dir = $phpbb_root_path . 'images/medals/';
				$options = '<option value=""></option>';
				if ($dh = opendir($dir))
				{
					while (($file = readdir($dh)) !== false)
					{
						if (strlen($file) >= 3 && ( strpos($file, '.gif',1) || strpos($file, '.jpg',1) || strpos($file, '.png',1) ))
						{
							$options .= '<option value="' . $file . '">' . $file . '</option>';
						}
					}
					closedir($dh);
				}
				
				$options2 = '';
				foreach($cats as $key => $value)
				{
					if ($key == $cat_id)
					{
						$options2 .= '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
					}
					else
					{
						$options2 .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
					}
				}

                $template->assign_vars(array(
					'MEDAL_TITLE'			=> $user->lang['ACP_MEDAL_TITLE_ADD'],
					'MEDAL_TEXT'			=> $user->lang['ACP_MEDAL_TEXT_ADD'],
					'NAME_VALUE'			=> utf8_normalize_nfc((isset($_POST['medal_name'])) ? request_var('medal_name', '', true) : ''),
					'IMAGE_OPTIONS'			=> $options,
					'PARENT_OPTIONS'		=> $options2,
					'DYNAMIC_CHECKED_NO'	=> 'checked="checked"',
					'DEVICE_VALUE'			=> 'device',
					'NUMBER_VALUE'			=> 1,
					'POINTS_VALUE'			=> 0,
					'NOMINATED_CHECKED_NO'	=> 'checked="checked"',
					'MEDAL_ACTION'			=> 'newmedal',
					'MEDAL_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=addmedalsql&catid=' . $cat_id),
					'PHPBB_ROOT_PATH'		=> $phpbb_root_path,
				));
				break;
			
			case 'movecat':
			
				if ($move_type)
				{
					$swap_diff = 1;
				}
				else
				{
					$swap_diff = -1;
				}
				$sql = 'UPDATE ' . MEDALS_CATS_TABLE . '
							SET order_id = ' . $cats[$move_id]['order_id'] . '
							WHERE order_id = ' . $cats[$move_id]['order_id'] . '+' . $swap_diff;
				$db->sql_query($sql);
				$sql = 'UPDATE ' . MEDALS_CATS_TABLE . '
							SET order_id = ' . $cats[$move_id]['order_id'] . '+' . $swap_diff . '
							WHERE id = ' . $cats[$move_id]['id'];
				$db->sql_query($sql);
				$submode = '';
				$sql = 'SELECT *
							FROM ' . MEDALS_CATS_TABLE . '
							ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$cats = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$cats[$row['id']] = array( 
						'name' 		=> $row['name'], 
						'id'		=> $row['id'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				break;
			
			case 'editcat':
				
                $this->tpl_name = 'acp_medals_cats_edit';
                $this->page_title = $user->lang['ACP_MEDALS_INDEX'];

                $template->assign_vars(array(
					'MEDAL_TITLE'			=> $user->lang['ACP_MEDAL_TITLE_CAT'],
					'MEDAL_TEXT'			=> $user->lang['ACP_MEDAL_TEXT_CAT'],
					'MEDAL_LEGEND'			=> $user->lang['ACP_MEDAL_LEGEND_CAT'],
					'NAME_VALUE'			=> $cats[$cat_id]['name'],
					'NAME_TITLE'			=> $user->lang['ACP_NAME_TITLE_CAT'],
					'MEDAL_ACTION'			=> 'changecat',
					'MEDAL_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editcatsql&catid=' . $cat_id),
				));
				break;
			
			case 'editcatsql':
				$sql = 'UPDATE ' . MEDALS_CATS_TABLE . ' 
						SET ' . $db->sql_build_array('UPDATE', array(
									'name'		=> utf8_normalize_nfc(request_var('name', '', true)),
								)) . '
						WHERE id = ' . $cats[$cat_id]['id'];
				$db->sql_query($sql);
				trigger_error(sprintf($user->lang['ACP_CAT_EDIT_GOOD'], append_sid('index.php?i=' . $id . '&mode=management')));
				break;
			
			case 'deletecat':
				if (!isset($_POST['deleteall']) && !isset($_POST['moveall']))
				{
					$options2 = '';
					foreach($cats as $key => $value)
					{
						if ($value['id'] != $cat_id)
						{
							$options2 .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
						}
					}
					if (!empty($options2))
					{
						trigger_error(sprintf($user->lang['ACP_CAT_DELETE_CONFIRM'], $options2));
					}
					else
					{
						trigger_error($user->lang['ACP_CAT_DELETE_CONFIRM_ELSE']);
					}
				}
				else if (isset($_POST['moveall']))
				{
					$sql = 'DELETE FROM ' . MEDALS_CATS_TABLE . ' WHERE id = ' . $cat_id;
					$db->sql_query($sql);
					$i = 1;
					foreach ($medals as $key => $value)
					{
						if ($value['parent'] == request_var('newcat', ''))
						{
							$i = $value['order_id'] + 1;
						}
					}
					foreach ($medals as $key => $value)
					{
						if ($value['parent'] == $cat_id)
						{
							$sql = 'UPDATE ' . MEDALS_TABLE . '
									SET ' . $db->sql_build_array('UPDATE', array(
												'parent'	=> request_var('newcat', ''),
												'order_id'	=> $i,
											)) . '
									WHERE id = ' . $value['id'];
							$db->sql_query($sql);
							$i++;
						}
					}
					foreach ($cats as $key => $value)
					{
						if ($value['id'] == request_var('newcat', ''))
						{
							$newname = $value['name'];
							break;
						}
					}
					trigger_error(sprintf($user->lang['ACP_CAT_DELETE_MOVE_GOOD'], $cats[$cat_id]['name'], $newname, append_sid('index.php?i=' . $id . '&mode=management')));
				}
				else if (isset($_POST['deleteall']))
				{
					$sql = 'DELETE FROM ' . MEDALS_CATS_TABLE . ' WHERE id = ' . $cat_id;
					$db->sql_query($sql);
					$sql = 'DELETE FROM ' . MEDALS_TABLE . ' WHERE parent = ' . $cat_id;
					$db->sql_query($sql);
					foreach ($medals as $key => $value)
					{
						if ($value['parent'] == $cat_id)
						{
							$sql = 'DELETE FROM ' . MEDALS_AWARDED_TABLE . ' WHERE medal_id = ' . $value['id'];
							$db->sql_query($sql);
						}
					}
					trigger_error(sprintf($user->lang['ACP_CAT_DELETE_GOOD'], append_sid('index.php?i=' . $id . '&mode=management')));
				}
				break;
		}
		if (empty($submode))
		{
			switch($mode)
			{
				case 'config':
					$this->tpl_name = 'acp_medals_config';
					$this->page_title = $user->lang['ACP_MEDALS_INDEX'];
					break;

				case 'management':
					$this->tpl_name = 'acp_medals';
					$this->page_title = $user->lang['ACP_MEDALS_INDEX'];
					foreach($cats as $key2 => $value2)
					{
						$template->assign_block_vars('medals', array(
							'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editcat&catid=' . $value2['id']),
							'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletecat&catid=' . $value2['id']),
							'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=movecat&movetype=0&moveid=' . $value2['id']),
							'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=movecat&movetype=1&moveid=' . $value2['id']),
							'MEDAL_IMAGE'		=> '<img src="images/icon_subfolder.gif" alt="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" title="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" />',
							'MEDAL_TITLE'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $value2['id']) . '" class="title">' . $value2['name'] . '</a>',
						));
					}
					break;

					default:
						trigger_error('NO_MODE', E_USER_ERROR);
					break;
			}
		}
		$template->assign_vars(array(
			'U_MEDALS_CONFIG' => append_sid('index.php?i=' . $id . '&mode=config'),
			'U_MEDALS_INDEX'  => append_sid('index.php?i=' . $id . '&mode=management'),
		));
    }
}
?>