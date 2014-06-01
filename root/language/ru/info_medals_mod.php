<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/23 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* перевод к версии 0.21.0 Pthelovod
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
	'PM_MESSAGE'					=> '%1$s' . "\n" . '[b]Пользователь %3$s вручил Вам награду "%2$s".[/b]' . "\n\n" . '%3$s также указал причину награждения: ',
	'PM_MESSAGE_POINTS_EARN'		=> '<br />Вы заработали %1$s очков%2$s.' . "\n\n",
	'PM_MESSAGE_POINTS_DEDUCT'		=> '<br />%1$s очков%2$s было вычтено.' . "\n\n",
	'PM_MESSAGE_NOMINATED'			=> '%1$s' . "\n" . '[b]Пользователь %3$s вручил Вам награду "%2$s" после номинации от %4$s.[/b]' . "\n\n" . '%3$s также указал причину награждения: ',
	'PM_MSG_SUBJECT'				=> '%s утвердил Ваше награждение!',

// medals awarding
	'AWARDED_BY'					=> 'Наградивший',
	'AWARDED_MEDAL'					=> 'Врученные награды',
	'AWARDED_MEDAL_TO'				=> 'Награды, врученные пользователю',
	'AWARD_MEDAL'					=> 'Награждение',
	'AWARD_TIME'					=> 'Дата награждения',
	'AWARD_TO'						=> 'Наградить пользователя',
	'MEDAL_AWARD_GOOD'				=> 'Награда успешно вручена!',
	'NOT_MEDALS_AWARDED'			=> 'Награда не может быть вручена следующим пользователям:<br />%s',
	'MEDAL_REMOVE_GOOD'				=> 'Награда успешно удалена!',
	'MEDAL_REMOVE_CONFIRM'			=> 'Вы собираетесь удалить медаль или номинацию у пользователя! Вы это действительно хотите сделать?',
	'MEDAL_REMOVE_NO'				=> 'Удаление медали или номинации отменено.',
	'MEDAL_EDIT'					=> 'Правка',
	'NO_USER_SELECTED'				=> 'Не введено имя пользователя. Вы будете возвращены на прежнюю страницу.',

// medals nominate
	'APPROVE'						=> 'Одобрить',
	'USER_NOMINATED'				=> 'Пользователь номинирован',
	'USER_IS_NOMINATED'				=> ' [<a href="%s" title="Этот пользователь номинирован на награду!">!</a>]',
	'MEDAL_NOMINATE_GOOD'			=> 'Награда успешно номинирована!',
	'NOMINATABLE'					=> '[Номинация]',
	'NOMINATE'						=> 'Номинировать на награду',
	'NOMINATE_FOR'					=> 'Номинировать на награду пользователя',
	'NOMINATE_MEDAL'				=> 'Управление номинациями',
	'NOMINATE_MESSAGE'				=> '<b>%1$s номинировал этого пользователя на награду "%2$s" по следующей причине:</b>' . "\n\n",
	'NOMINATE_USER_LOG'				=> 'Управление номинациями для пользователя %s',
	'NOMINATED_BY'					=> '[Номинировал %s]',
	'NOMINATED_EXPLAIN'				=> 'Могут ли пользователи номинировать других пользователей на эту награду?',
	'NOMINATED_TITLE'				=> 'Номинирование наград',
	'NO_MEDALS_NOMINATED'			=> 'Награда не номинирована',
	'NOMINATIONS_REMOVE_GOOD'		=> 'Номинация успешно удалена!',

// Images
	'IMAGE_PREVIEW'					=> 'Предпросмотр награды и ее названия',
	'MEDAL_IMG'						=> 'Изображение',

// medals view
	'MEDAL'							=> 'Награда',
	'MEDALS'						=> 'Награды',
	'MEDALS_VIEW_BUTTON'			=> 'Подробно',
	'MEDALS_VIEW'					=> 'Врученные Награды',
	'MEDAL_DETAIL'					=> 'Подробности о врученных пользователю наградах',
	'MEDAL_DESCRIPTION'				=> 'Описание награды',
	'MEDAL_DESC'					=> 'Описание',
	'MEDAL_AWARDED'					=> 'Награждённые',
	'MEDAL_AWARDED_EXPLAIN'			=> '<br>Нажмите на имени пользователя для открытия его профиля',
	'MEDAL_AWARD_REASON'			=> 'Причина награждения или номинации на награду',
	'MEDAL_AWARD_REASON_EXPLAIN'	=> '<br>Введите причину награждения. Она будет показана всем ',
	'MEDAL_NOMINATE_REASON'			=> 'Причина номинирования',
	'MEDAL_NOMINATE_REASON_EXPLAIN'	=> '<br>Укажите причину вашего номинирования на медаль',	
	'MEDAL_AWARD_USER_EXPLAIN'		=> '<br>Для вручения этой награды введите имена пользователей. Вводите каждое имя на новой строке. Все перечисленные пользователи будут награждены.',
	'MEDAL_INFORMATION'				=> 'Информация о награждениях',
	'MEDAL_INFO'					=> 'Информация',
	'MEDAL_MOD'						=> 'Награждение',
	'MEDAL_NAME'					=> 'Имя',
	'NO_MEDALS_ISSUED'				=> 'Награда не выдавалась',
	'MEDAL_CP'						=> 'Наградной раздел',
	'MEDAL_AWARD_PANEL'				=> 'Панель управления наградами',
	'MEDAL_NOM_BY'					=> 'Номинировавший',
	'MEDAL_AMOUNT'					=> 'Количество',
	'MEDALS_VIEW_ONLINE'            => 'Просматривает страницу медалей',

// Error messages
	'CANNOT_AWARD_MULTIPLE'			=> 'Этот пользователь получил максимально возмножное количество наград.',
	'IMAGE_ERROR'					=> 'Вы не можете это выбрать для награждения',
	'IMAGE_ERROR_NOM'				=> 'Вы не можете это выбрать для номинации',
	'NO_CAT_ID'						=> 'Категория с таким ID не определена',
	'NO_CATS'						=> 'Нет категорий',
	'NO_GOOD_PERMS'					=> 'У Вас нет необходимых разрешений для доступа к этой странице. .<br /><br /><a href="index.php">Вернуться на главную страницу</a>',
	'NO_MEDAL_ID'					=> 'Награда с таким ID не определена',
	'NO_MEDAL_MSG'					=> 'Не заполнена причина награждения.<br /><br /><a href="%s">Вернуться на предыдущую страницу</a>',
	'NO_MEDALS'						=> 'Нет доступных наград',
	'NO_MEDALS_TO_NOMINATE'			=> 'Для этого пользователя нет доступных для номинации наград.<br /><br /><a href="%s">Вернутся на предыдущую страницу</a>',
	'NO_USER_ID'					=> 'Пользователь с таким ID не определен',
	'NO_USER_MEDALS'				=> 'Этот пользователь ничем не награжден',
	'NO_USER_NOMINATIONS'			=> 'Этот пользователь не номинирован',
	'NO_SWAP_ID'					=> 'No Swap ID was specified',
	'NOT_SELF'						=> 'Вы не можете номинировать самого себя',

));

?>
