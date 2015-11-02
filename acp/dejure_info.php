<?php
/**
*
* @package phpBB Extension - tas2580 dejure
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\dejure\acp;

class dejure_info
{
	function module()
	{
		return array(
			'filename'		=> '\tas2580\dejure\dejure_module',
			'title'			=> 'ACP_DEJURE_TITLE',
			'version'		=> '0.1.1',
			'modes'		=> array(
				'settings'    => array(
					'title'		=> 'ACP_DEJURE_TITLE',
					'auth'	=> 'ext_tas2580/dejure && acl_a_board',
					'cat'		=> array('ACP_DEJURE_TITLE')
				),
			),
		);
	}
}
