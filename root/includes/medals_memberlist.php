<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/23 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

//
// Medals System MOD
//
$user->add_lang('mods/info_medals_mod');	

$s_nominate = false;
if ( $auth->acl_get('u_nominate_medals') && $user_id != $user->data['user_id'] )
{
	$s_nominate = true;
}

$is_mod = ($user->data['user_type'] == USER_FOUNDER || $auth->acl_get('u_award_medals') ) ? true : false;

$uid			= $bitfield			= '';	// will be modified by generate_text_for_storage
$allow_bbcode	= $allow_smilies	= true;
$allow_urls		= false;
$m_flags = '3';  // 1 is bbcode, 2 is smiles, 4 is urls (add together to turn on more than one)
//
// Category
//

$sql = "SELECT id, name
	FROM " . MEDALS_CATS_TABLE . "
	ORDER BY order_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query medal categories list', '', __LINE__, __FILE__, $sql);
}

$category_rows = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$category_rows[] = $row;
}
$db->sql_freeresult($result);

$sql = "SELECT m.medal_id, m.user_id
	FROM " . MEDALS_AWARDED_TABLE . " m
	WHERE m.user_id = {$user_id}
		AND m.nominated = 0";

if($result = $db->sql_query($sql))
{
	$medal_list = $db->sql_fetchrowset($result);
	$medal_count = count($medal_list);

	if ( $medal_count )
	{
		$template->assign_block_vars('switch_display_medal', array());

		$template->assign_block_vars('switch_display_medal.medal', array(
			'MEDAL_BUTTON' => '<input type="button" class="button2" onclick="hdr_toggle(\'toggle_medal\',\'medal_open_close\')" value="' . $user->lang['MEDALS_VIEW_BUTTON'] . '"/>'
		));
	}
}

for ($i = 0; $i < count($category_rows); $i++)
{
	$cat_id = $category_rows[$i]['id'];

	$sql = "SELECT m.id, m.name, m.description, m.image, m.device, m.dynamic, m.parent,
				ma.nominated_reason, ma.time, ma.awarder_id, ma.awarder_un, ma.awarder_color, ma.bbuid, ma.bitfield,
				c.id as cat_id, c.name as cat_name
			FROM " . MEDALS_TABLE . " m, " . MEDALS_AWARDED_TABLE . " ma, " . MEDALS_CATS_TABLE . " c
			WHERE ma.user_id = {$user_id}
				AND m.parent = c.id
				AND m.id = ma.medal_id
				AND ma.nominated = 0
			ORDER BY c.order_id, m.order_id, ma.time";

	if ($result = $db->sql_query($sql))
	{
		$row = array();
		$rowset = array();
		$medal_time = $user->lang['AWARD_TIME'] . ':&nbsp;';
		$medal_reason = $user->lang['MEDAL_AWARD_REASON'] . ':&nbsp;';
		while ($row = $db->sql_fetchrow($result))
		{
			if (empty($rowset[$row['name']]))
			{
				$rowset[$row['name']]['cat_id'] = $row['cat_id'];
				$rowset[$row['name']]['cat_name'] = $row['cat_name'];
				if ( isset($rowset[$row['name']]['description']) )
				{
					$rowset[$row['name']]['description'] .= $row['description'];
				}
				else
				{
					$rowset[$row['name']]['description'] = $row['description'];
				}
				$rowset[$row['name']]['image'] = $phpbb_root_path . 'images/medals/' . $row['image'];
				$rowset[$row['name']]['device'] = $phpbb_root_path . 'images/medals/devices/' . $row['device'];
				$rowset[$row['name']]['dynamic'] = $row['dynamic'];
			}
			$row['nominated_reason'] = ( $row['nominated_reason'] ) ? $row['nominated_reason'] : $lang['Medal_no_reason'];
			$awarder_name = "" ;
			if ( $row['awarder_id'] )
			{
				$awarder_name = "<br />" . $user->lang['AWARDED_BY'] . ": " . get_username_string('full', $row['awarder_id'], $row['awarder_un'], $row['awarder_color'], $row['awarder_un']) ;
			}
			//generate_text_for_storage($row['nominated_reason'], $uid, $bitfield, $m_flags, $allow_bbcode, $allow_urls, $allow_smilies);
			$reason = generate_text_for_display($row['nominated_reason'], $row['bbuid'], $row['bitfield'], $m_flags);
			if ( isset($rowset[$row['name']]['medal_issue']) )
			{
				$rowset[$row['name']]['medal_issue'] .= $medal_time . $user->format_date($row['time']) . $awarder_name . '</td></tr><tr><td>' . $medal_reason . '<div class="content">' . $reason . '</div><hr />';
			}
			else
			{
				$rowset[$row['name']]['medal_issue'] = $medal_time . $user->format_date($row['time']) . $awarder_name . '</td></tr><tr><td>' . $medal_reason . '<div class="content">' . $reason . '</div><hr />';
			}
			if ( isset($rowset[$row['name']]['medal_count']) )
			{
				$rowset[$row['name']]['medal_count'] += '1';
			}
			else
			{
				$rowset[$row['name']]['medal_count'] = '1';
			}
		}

		$medal_width = ( $config['medal_small_img_width'] ) ? ' width="'.$config['medal_small_img_width'].'"' : '';
		$medal_height = ( $config['medal_small_img_ht'] ) ? ' height="'.$config['medal_small_img_ht'].'"' : '';

		$medal_name = array();
		$data = array();

		//
		// Should we display this category/medal set?
		//
		$display_medal = 0;
		$numberofmedals = 0;
		$after_first_cat = 0;
        $newcat = 1;

		while (list($medal_name, $data) = @each($rowset))
		{
			if ( $cat_id == $data['cat_id'] ) { $display_medal = 1; }

			$display_across = $config['medal_profile_across'] ? $config['medal_profile_across'] : 5 ;
			if ( $numberofmedals == $display_across ) {
                $break = '<br \>' ;
                $numberofmedals = 0 ;
            } else {
                $break = '' ;
            }
            if ( !empty($newcat) && !empty($after_first_cat) ) {
                $break = '<hr \>&nbsp;' ;
                $numberofmedals = 0 ;
            }

            $numberofmedals++ ;

			if ( !empty($display_medal) )
			{
	            if ($data['medal_count'] > 1)
				{
					if ( $data['dynamic'] )
					{
						$device = $data['device'] . '-' . ($data['medal_count'] - 1) . '.gif' ;
						$image = '<img src="medals.php?m=mi&med=' .$data['image'] . '&' . 'd=' . $device . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '" />' ;
						$small_image = $break . '<img src="medals.php?m=mi&med=' . $data['image'] . '&' . 'd='. $device . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '"' . $medal_width . $medal_height . ' />' ;
	                }
					else
					{
	                    $cluster = '-' . $data['medal_count'] ;
						$device_image = substr_replace($data['image'],$cluster, -4) . substr($data['image'], -4);
						if ( file_exists($device_image) )
						{
							$data['image'] = $device_image ;
						}
						$image = '<img src="' . $data['image'] . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '" />';
						$small_image = $break . '<img src="' . $data['image'] . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '"' . $medal_width . $medal_height . ' />';
					}
				}
				else
				{
					$image = '<img src="' . $data['image'] . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '" />';
					$small_image = $break . '<img src="' . $data['image'] . '" border="0" alt="' . $medal_name . '" title="' . $medal_name . '"' . $medal_width . $medal_height . ' />';
				}
				
				$template->assign_block_vars('switch_display_medal.details', array(
					'ISMEDAL_CAT' 		=> $newcat,
					'MEDAL_CAT' 		=> $data['cat_name'],
					'MEDAL_NAME' 		=> $medal_name,
					'MEDAL_DESCRIPTION' => $data['description'],
					'MEDAL_IMAGE' 		=> $image,
					'MEDAL_IMAGE_SMALL' => $small_image,
					'MEDAL_ISSUE' 		=> $data['medal_issue'],
					'MEDAL_COUNT' 		=> $user->lang['MEDAL_AMOUNT'] . ': ' . $data['medal_count'],
				));
				$display_medal = 0;
                $newcat = 0 ;
            } else {
                // New category lets put an hr between
                $newcat = 1 ;
				$after_first_cat = 1;
			}
		}
	}
}

?>