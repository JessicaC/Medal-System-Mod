<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/23 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
// pms
	'PM_MESSAGE'					=> '%1$s' . "\n" . '[b]You have been awarded the medal "%2$s" by %3$s.' . "\n" . '%3$s has also sent you the following message:[/b]' . "\n\n",
	'PM_MESSAGE_POINTS_EARN'		=> '<br />You have earned %1$s point%2$s.' . "\n\n",
	'PM_MESSAGE_POINTS_DEDUCT'		=> '<br />%1$s point%2$s have been deducted.' . "\n\n",
	'PM_MESSAGE_NOMINATED'			=> '%1$s' . "\n" . '[b]You have been awarded the medal "%2$s" by %3$s after being nominated for it by %4$s.' . "\n" . '%3$s has also sent you the following message:[/b]' . "\n\n",
	'PM_MSG_SUBJECT'				=> '%s has awarded you a medal!',

// medals awarding
	'AWARDED_BY'					=> 'Awarded by',
	'AWARDED_MEDAL'					=> 'Awarded Medals',
	'AWARDED_MEDAL_TO'				=> 'Awarded Medals of',
	'AWARD_MEDAL'					=> 'Award Medal',
	'AWARD_TIME'					=> 'Award Time',
	'AWARD_TO'						=> 'Award Medal to',
	'MEDAL_AWARD_GOOD'				=> 'Medal awarded successfully!',
	'NOT_MEDALS_AWARDED'			=> 'Medal was not awarded!',
	'MEDAL_REMOVE_GOOD'				=> 'Medal removed successfully!',
	'MEDAL_REMOVE_CONFIRM'			=> 'You are about to remove a user\'s medal! Are you sure you wish to carry out this operation?',
	'MEDAL_REMOVE_NO'				=> 'No Medal deleted',
	'MEDAL_EDIT'					=> 'Edit',
	'NO_USER_SELECTED'				=> 'No username was entered. You will be redirected momentarily',

// medals nominate
	'APPROVE'						=> 'Approve',
	'USER_NOMINATED'				=> 'User Nominated',
	'USER_IS_NOMINATED'				=> ' [<a href="%s" title="This user has been nominated for a medal!">!</a>]',
	'MEDAL_NOMINATE_GOOD'			=> 'Medal nominated successfully!',
	'NOMINATABLE'					=> '[Nominatable]',
	'NOMINATE'						=> 'Nominate Medal',
	'NOMINATE_FOR'					=> 'Nominate Medal for',
	'NOMINATE_MEDAL'				=> 'Manage Nominations',
	'NOMINATE_MESSAGE'				=> '<b>%1$s nominates this user for the medal "%2$s" for the following reason:</b>' . "\n\n",
	'NOMINATE_USER_LOG'				=> 'Manage Nominations for %s',
	'NOMINATED_BY'					=> '[Nominated by %s]',
	'NOMINATED_EXPLAIN'				=> 'Can users nominate other users for this medal?',
	'NOMINATED_TITLE'				=> 'Medal Nominations',
	'NO_MEDALS_NOMINATED'			=> 'Medal Not Nominated',
	'NOMINATIONS_REMOVE_GOOD'		=> 'Nominations removed successfully!',

// Images
	'IMAGE_PREVIEW'					=> 'Preview',
	'MEDAL_IMG'						=> 'Image',

// medals view
	'MEDAL'							=> 'Medal',
	'MEDALS'						=> 'Medals',
	'MEDALS_VIEW_BUTTON'			=> 'Award Details',
	'MEDALS_VIEW'					=> 'Medals',
	'MEDAL_DETAIL'					=> 'Medal Detail',
	'MEDAL_DESCRIPTION'				=> 'Medal Description',
	'MEDAL_DESC'					=> 'Description',
	'MEDAL_AWARDED'					=> 'Recipients',
	'MEDAL_AWARDED_EXPLAIN'			=> '<br>Click on the username to administer their medal(s)',
	'MEDAL_AWARD_REASON'			=> 'Award Reason',
	'MEDAL_AWARD_REASON_EXPLAIN'	=> '<br>Enter the reason for awarding this medal',
	'MEDAL_NOMINATE_REASON'			=> 'Nominate Reason',
	'MEDAL_NOMINATE_REASON_EXPLAIN'	=> '<br>Enter the reason for nominating this medal',
	'MEDAL_AWARD_USER_EXPLAIN'		=> '<br>Enter the users to be awarded this medal (each name on a separate line)',
	'MEDAL_INFORMATION'				=> 'Medal Information',
	'MEDAL_INFO'					=> 'Information',
	'MEDAL_MOD'						=> 'Award',
	'MEDAL_NAME'					=> 'Name',
	'NO_MEDALS_ISSUED'				=> 'Medal Not Issued',
	'MEDAL_CP'						=> 'Medals Control Panel',
	'MEDAL_AWARD_PANEL'						=> 'Medals Award Panel',
	'MEDAL_NOM_BY'					=> 'Nominated by',
	'MEDAL_AMOUNT'					=> 'Amount',
	'MEDALS_VIEW_ONLINE'         => 'Viewing medals page',

// Error messages
	'CANNOT_AWARD_MULTIPLE'			=> 'This user has been awarded the maximum amount assigned to this medal',
	'IMAGE_ERROR'					=> 'You cannot select this as a medal to award',
	'IMAGE_ERROR_NOM'				=> 'You cannot select this as a medal to nominate',
	'NO_CAT_ID'						=> 'No Category ID was specified.',
	'NO_CATS'						=> 'No Categories',
	'NO_GOOD_PERMS'					=> 'You dont have the necessary permissions to access this page.<br /><br /><a href="index.php">Return to the Board Index</a>',
	'NO_MEDAL_ID'					=> 'No medal was selected or none are available. You will be redirected momentarily',
	'NO_MEDAL_MSG'					=> 'The message field was blank.<br /><br /><a href="%s">Return to the previous page</a>',
	'NO_MEDALS'						=> 'No Available Medals',
	'NO_MEDALS_TO_NOMINATE'			=> 'There are no medals available to nominate to this user<br /><br /><a href="%s">Return to the previous page</a>',
	'NO_USER_ID'					=> 'No User ID was specified',
	'NO_USER_MEDALS'				=> 'This user hasn\'t been awarded any medals',
	'NO_USER_NOMINATIONS'			=> 'This user hasn\'t been nominated for any medals',
	'NO_SWAP_ID'					=> 'No Swap ID was specified',
	'NOT_SELF'						=> 'You cannot nominate yourself',

));

?>
