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
	'ACP_LINK_STYLE'			=> 'Link style',
	'ACP_LINK_STYLE_EXPLAIN'	=> 'narrow: only the numbers of the regulations link <br> far:. as long as possible linking ',
	'ACP_CLASS'				=> 'CSS Class',
	'ACP_CLASS_EXPLAIN'		=> 'If the links to dejure.org are to be designed different.',
	'ACP_TARGET'				=> 'Link Target',
	'ACP_TARGET_EXPLAIN'		=> 'Target for the links, for example, open a new window: "_blank"',
	'ACP_BUZER'				=> 'buzer',
	'ACP_BUZER_EXPLAIN'		=> 'Link to http://buzer.de for laws that do not exist in dejure.org.',
	'ACP_CACHE_TIME'			=> 'Cache time',
	'ACP_CACHE_TIME_EXPLAIN'	=> 'In days. How long the texts should be kept in the cache before they are linked again. Four days is usually enough.',
	'LINK_STYLE_WEIT'			=> 'far',
	'LINK_STYLE_SCHMAL'		=> 'narrow',
	'ACP_SUBMIT'				=> 'Save settings',
	'ACP_SAVED'				=> 'The settings have been changed',
));
