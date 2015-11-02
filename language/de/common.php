<?php
/**
*
* @package phpBB Extension - tas2580 dejure
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_DEJURE_TITLE'			=> 'dejure.org',
	'ACP_DEJURE_EXPLAIN'		=> '',
	'ACP_LINK_STYLE'			=> 'Link Stil',
	'ACP_LINK_STYLE_EXPLAIN'	=> 'schmal: nur die Nummern der Vorschriften verlinken.<br>weit: möglichst lange Verlinkung',
	'ACP_CLASS'				=> 'CSS Klasse',
	'ACP_TARGET'				=> 'Link Target',
	'ACP_TARGET_EXPLAIN'		=> 'Target für die Links, zum Beispiel für öffnen in neuem Fenster: "_blank"',
	'ACP_CLASS_EXPLAIN'		=> 'Falls die Links zu dejure.org anders gestaltet werden sollen.',
	'ACP_BUZER'				=> 'buzer',
	'ACP_BUZER_EXPLAIN'		=> 'Zu http://buzer.de verlinken für Gesetze, die bei dejure.org nicht vorhanden sind.',
	'ACP_CACHE_TIME'			=> 'Cache Zeit',
	'ACP_CACHE_TIME_EXPLAIN'	=> 'In Tagen. Wie lange die Texte im Cache vorgehalten werden soll, bevor sie erneut vernetzt werden. 4 Tage reichen in der Regel.',
	'LINK_STYLE_WEIT'			=> 'weit',
	'LINK_STYLE_SCHMAL'		=> 'schmal',
	'ACP_SUBMIT'				=> 'Einstellungen speichern',
	'ACP_SAVED'				=> 'Die Einstellungen wurden geändert',
));
