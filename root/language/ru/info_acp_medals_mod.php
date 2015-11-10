<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/14 Gremlinn$
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
	'IMG_ICON_POST_APPROVE'			=> 'Одобрить',
	'ACP_MEDALS_INDEX'				=> 'Награды',
	'ACP_MEDALS_INDEX_EXPLAIN'		=> 'Награды на Вашем ресурсе',
	'ACP_MEDALS_TITLE'				=> 'Управление наградами',
	'ACP_MEDALS_SETTINGS'			=> 'Конфигурация наград',

	'MEDALS_MOD_INSTALLED'			=> 'Мод наград (Medals MOD) версии %s установлен',
	'MEDALS_MOD_UPDATED'			=> 'Мод наград (Medals MOD) обновлен до версии %s',
	'MEDALS_MOD_MANUAL'				=> 'У вас установлена слишком старая версия мода наград (Medals MOD).<br />Вам нужно сначала удалить установленную версию.<br />Но перед этим убедитесь, что вы сделали полный бекап.',

	'acl_u_award_medals'			=>  array('lang' => 'Может выдавать награды', 'cat' => 'misc'),
	'acl_u_nominate_medals'			=>  array('lang' => 'Может номинировать на выдачу наград', 'cat' => 'misc'),
	'acl_a_manage_medals'			=>  array('lang' => 'Может пользоваться модулем управления наградами', 'cat' => 'misc'),

// Medals Management
	'ACP_MEDAL_MGT_TITLE'				=> 'Управление наградами',
	'ACP_MEDAL_MGT_DESC'				=> 'Здесь вы можете просматривать, создавать, модифицировать и удалять категории наград',

	'ACP_MEDALS'						=> 'Награды',
	'ACP_MEDALS_DESC'					=> 'Здесь вы можете просматривать, создавать, модифицировать и удалять награды.',
	'ACP_MULT_TO_USER'					=> 'Количество награждений <br /> этой медалью на весь форум',
	'ACP_USER_NOMINATED'				=> 'Возможность номинирования',
	'ACP_MEDAL_LEGEND'					=> 'Награда',
	'ACP_MEDAL_TITLE_EDIT'				=> 'Редактирование награды',
	'ACP_MEDAL_TEXT_EDIT'				=> 'Форма ниже позволяет вам изменять настройки награды.',
	'ACP_MEDAL_TITLE_ADD'				=> 'Создание награды',
	'ACP_MEDAL_TEXT_ADD'				=> 'Форма ниже позволяет вам создать новую награду.',
	'ACP_MEDAL_DELETE_GOOD'				=> 'Награда успешно удалена.<br /><br /> Нажмите <a href="%s">здесь</a> чтобы вернуться к предыдущей категории',
	'ACP_MEDAL_EDIT_GOOD'				=> 'Награда успешно обновлена.<br /><br /> Нажмите <a href="%s">здесь</a> чтобы вернуться в категорию награды',
	'ACP_MEDAL_ADD_GOOD'				=> 'Награда успешно добавлена.<br /><br /> Нажмите <a href="%s">здесь</a> чтобы вернуться в категорию награды',
	'ACP_CONFIRM_MSG_1'					=> 'Вы уверены, что хотите удалить эту награду? Это действие также удалит эту награду у любых пользователей, которые были награждены ею. <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirm" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelmedal" value="No" /></fieldset></form>',
	'ACP_NAME_TITLE'					=> 'Название награды',
	'ACP_NAME_DESC'						=> 'Описание награды',
	'ACP_IMAGE_TITLE'					=> 'Изображение награды',
	'ACP_IMAGE_EXPLAIN'					=> 'Изображение награды в формате gif внутри папки images/medals/ ',
	'ACP_DEVICE_TITLE'					=> 'Базовое имя',
	'ACP_DEVICE_EXPLAIN'				=> 'Базовое имя gif-изображения внутри папки images/medals/devices, для динамического создания изображения множественных наград.<br /> Пример: изображение device-2.gif, базовое имя device',
	'ACP_PARENT_TITLE'					=> 'Категория наград',
	'ACP_PARENT_EXPLAIN'				=> 'Категория, в которую поместить награду',
	'ACP_DYNAMIC_TITLE'					=> 'Динамическое изображение',
	'ACP_DYNAMIC_EXPLAIN'				=> 'Динамически создавать изображение в случае множественного вручения награды.',
	'ACP_NOMINATED_TITLE'				=> 'Номинирование',
	'ACP_NOMINATED_EXPLAIN'				=> 'Могут ли пользователи выдвигать других пользователей на вручение этой награды?',
	'ACP_CREATE_MEDAL'					=> 'Создать награду',
	'ACP_NO_MEDALS'						=> 'Нет наград',
	'ACP_NUMBER'						=> 'Количество награждений данной медалью',
	'ACP_NUMBER_EXPLAIN'				=> 'Определяет, сколько раз эта награда может быть вручена на форуме.',
	'ACP_POINTS'						=> 'Баллы',
	'ACP_POINTS_EXPLAIN'				=> 'Определяет, как начисляются (или расходуются) баллы при получении этой награды.<br />Используется для совместной работы с Simple Points Mod.',

	'ACP_MEDALS_MGT_INDEX'				=> 'Категории наград',
	'ACP_MEDAL_TITLE_CAT'				=> 'Редактирование категории',
	'ACP_MEDAL_TEXT_CAT'				=> 'Форма ниже позволяет вам изменять настройки существующей категории.',
	'ACP_MEDAL_LEGEND_CAT'				=> 'Категория',
	'ACP_NAME_TITLE_CAT'				=> 'Имя категории',
	'ACP_CREATE_CAT'					=> 'Создать категорию',
	'ACP_CAT_ADD_FAIL'					=> 'Не указано имя категории для добавления.<br /><br /> Нажмите <a href="%s">здесь</a> для возврата к списку категорий.',
	'ACP_CAT_ADD_GOOD'					=> 'Категория успешно добавлена.<br /><br /> Нажмите <a href="%s">здесь</a> для возврата к списку категорий.',
	'ACP_CAT_EDIT_GOOD'					=> 'Категория успешно обновлена.<br /><br /> Нажмите <a href="%s">здесь</a> для возврата к списку категорий.',
	'ACP_CAT_DELETE_CONFIRM'			=> 'В какую категорию перенести медали после удаления этой категории? <br /><form method="post"><fieldset class="submit-buttons"><select name="newcat">%s</select><br /><br /><input class="button1" type="submit" name="moveall" value="Move All Medals" />&nbsp;<input class="button2" type="submit" name="deleteall" value="Delete All Medals" />&nbsp;<input type="submit" class="button2" name="cancelcat" value="Cancel Deletion" /></fieldset></form>',
	'ACP_CAT_DELETE_CONFIRM_ELSE'		=> 'Отсутствуют категории для перемещения наград.<br />Вы уверены, что хотите удалить эту категорию и все содержащиеся в ней награды?<br /><form method="post"><fieldset class="submit-buttons"><br /><input class="button2" type="submit" name="deleteall" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelcat" value="No" /></fieldset></form>',
	'ACP_CAT_DELETE_GOOD'				=> 'Эта категория, все содержащиеся в ней награды и информация о вручениях этих наград успешно удалены.<br /><br /> Нажмите <a href="%s">здесь</a> для возврата к списку категорий.',
	'ACP_CAT_DELETE_MOVE_GOOD'			=> 'Все награды из "%1$s" были перемещены в "%2$s", а исходная категория была успешно удалена.<br /><br /> Нажмите <a href="%3$s">here</a> для возврата к списку категорий.',
	'ACP_NO_CATS'						=> 'Нет категорий',
	'ACP_NO_CAT_ID'						=> 'Нет категорий',

// Medals Configuration
	'ACP_CONFIG_TITLE'					=> 'Основные параметры',
	'ACP_CONFIG_DESC'					=> 'Здесь вы можете установить основные параметры наград',
	'ACP_MEDALS_CONF_SETTINGS'			=> 'Настройки конфигурации наград',
	'ACP_MEDALS_CONF_SAVED'				=> 'Настройки наград сохранены<br /><br /> Нажмите <a href="%s">здесь</a> для возврата на страницу настроек',
	'ACP_MEDALS_SM_IMG_WIDTH'			=> 'Уменьшенная ширина',
	'ACP_MEDALS_SM_IMG_WIDTH_EXPLAIN'	=> 'Ширина (в пикселях) уменьшенного изображения награды для показа в мини-профиле при просмотре темы и в профиле пользователя.<br />Установите 0, чтобы не менять размер изображения.',
	'ACP_MEDALS_SM_IMG_HT'				=> 'Уменьшенная высота',
	'ACP_MEDALS_SM_IMG_HT_EXPLAIN'		=> 'Высота (в пикселях) уменьшенного изображения награды для показа в мини-профиле при просмотре темы и в профиле пользователя.<br />Установите 0, чтобы не менять размер изображения.',
	'ACP_MEDALS_VT_SETTINGS'			=> 'Настройки показа при просмотре темы',
	'ACP_MEDALS_TOPIC_DISPLAY'			=> 'Включить показ наград при просмотре темы',
	'ACP_MEDALS_TOPIC_ROW'				=> 'По горизонтали',
	'ACP_MEDALS_TOPIC_ROW_EXPLAIN'		=> 'Количество наград в одной строке при показе в мини-профиле на странице просмотра темы.',
	'ACP_MEDALS_TOPIC_COL'				=> 'По вертикали',
	'ACP_MEDALS_TOPIC_COL_EXPLAIN'		=> 'Количество строк с наградами при показе в мини-профиле на странице просмотра темы.',
	'ACP_MEDALS_PROFILE_ACROSS'			=> 'Количество наград в профиле',
	'ACP_MEDALS_PROFILE_ACROSS_EXPLAIN'	=> 'Количество наград на одной странице в разделе профиля пользователей "Информация о награждениях" - "Подробно".',
	'ACP_MEDALS_ACTIVATE' 				=> 'Включить мод наград',
));

?>
