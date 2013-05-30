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

// Medals Display
$sql = "SELECT user_id, nominated
	FROM " . MEDALS_AWARDED_TABLE . "
	WHERE user_id = '" . $poster_id . "'";
$m_result = $db->sql_query($sql);
$medals_count   = 0;

$has_perms = $user->data['user_type'] == USER_FOUNDER || $auth->acl_get('u_award_medals');
$nominated_medals = false;
while ($m_row = $db->sql_fetchrow($m_result))
{
	if ($has_perms && $m_row['nominated'])
	{
		$nominated_medals = true;
	}
	else if (!$m_row['nominated'])
	{
		$medals_count++  ;
	}
}

$user->add_lang('mods/info_medals_mod');

$template->assign_block_vars('postrow.medal', array(
	'MEDALS_COUNT'		=> $medals_count,
	'MEDALS_NOMINATED'	=> ($nominated_medals) ? sprintf($user->lang['USER_IS_NOMINATED'], append_sid('medals.php?m=validate&u=' . $poster_id)) : '',
	'S_HAS_MEDALS'		=> ($medals_count) ? true : false,
	'S_HAS_NOMINATIONS'	=> ($nominated_medals) ? true : false,
));

if ( $config['medal_display_topic'] > 0 && $medals_count > 0)
{
	$medal_width	= ( $config['medal_small_img_width'] ) ? ' width="'.$config['medal_small_img_width'].'"' : '';
	$medal_height	= ( $config['medal_small_img_ht'] ) ? ' height="'.$config['medal_small_img_ht'].'"' : '';
	$medal_rows   	= ( $config['medal_topic_col'] ) ? $config['medal_topic_col'] : 1 ;
	$medal_cols		= ( $config['medal_topic_row'] ) ? $config['medal_topic_row'] : 1 ;

	$template->assign_block_vars('postrow.medal', array());

	$sql = "SELECT m.id, m.name, m.image, m.device, m.dynamic, m.parent, ma.time, c.id as cat_id, c.name as cat_name
	FROM " . MEDALS_TABLE . " m, " . MEDALS_AWARDED_TABLE . " ma, " . MEDALS_CATS_TABLE . " c
	WHERE ma.user_id = '" . $poster_id . "'
	AND m.parent = c.id
	AND m.id = ma.medal_id
	AND ma.nominated <> 1
	ORDER BY c.order_id, m.order_id, ma.time";

	if ($result = $db->sql_query($sql))
	{
		$rowset2 = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$rowset2[$row['image']]['name'] = $row['name'];
			if ($rowset2[$row['image']]['name'] == $row['name'])
			{
				if ( isset($rowset2[$row['image']]['count']) )
				{
					$rowset2[$row['image']]['count'] += '1';
				}
				else
				{
					$rowset2[$row['image']]['count'] = '1';
				}
			}
			$rowset2[$row['image']]['dynamic'] = $row['dynamic'];
			$rowset2[$row['image']]['device']  = $row['device'];
		}
		if ( $medals_count > 0 )
		{
			$split_row = $medal_cols - 1;

			$s_colspan = 0;
			$row = 0;
			$col = 0;

			while (list($image, $medal) = @each($rowset2))
			{
				if (!$col)
				{ 
					$template->assign_block_vars('postrow.medal.medal_row', array()); 
				}

				if ($medal['count'] > 1)
				{
					if ( $medal['dynamic'] )
					{
						$device = $phpbb_root_path . 'images/medals/devices/' . $medal['device'] . '-' . ($medal['count'] - 1) . '.gif' ;
						$image = $phpbb_root_path . 'medals.php?m=mi&med=' . $phpbb_root_path . 'images/medals/' . $image . '&' . 'd=' . $device ;
					}
					else
					{
						$cluster = '-' . $medal['count'] ;
						$device_image = substr_replace($image,$cluster, -4) . substr($image, -4) ;
						if ( file_exists($device_image) )
						{
							$image = $device_image ;
						}
						$image = $phpbb_root_path . 'images/medals/' . $image ;
					}
				}
				else
				{
					$image = $phpbb_root_path . 'images/medals/' . $image ;
				}

				$template->assign_block_vars('postrow.medal.medal_row.medal_col', array(
					'MEDAL_IMAGE' => $image,
					'MEDAL_WIDTH' => $medal_width,
					'MEDAL_HEIGHT' => $medal_height,
					'MEDAL_NAME' => $medal['name'],
					'MEDAL_COUNT' => '(' . $medal['count']. ')')
				);

				$s_colspan = max($s_colspan, $col + 1);

				if ($col == $split_row)
				{
					if ($row == $medal_rows - 1) 
					{ 
						break; 
					}
					$col = 0;
					$row++;
				}
				else 
				{ 
					$col++; 
				}
			}
		}
	}
}
?>