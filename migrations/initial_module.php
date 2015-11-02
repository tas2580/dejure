<?php
/**
*
* @package phpBB Extension - dejure
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\dejure\migrations;

class initial_module extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('tas2580_dejure_link_style', 'weit')),
			array('config.add', array('tas2580_dejure_class', '')),
			array('config.add', array('tas2580_dejure_buzer', '0')),
			array('config.add', array('tas2580_dejure_cache_time', '4')),
			array('config.add', array('tas2580_dejure_target', '')),

			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DEJURE_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_DEJURE_TITLE',
				array(
					'module_basename'		=> '\tas2580\dejure\acp\dejure_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'dejure'	=> array(
					'COLUMNS'	=> array(
						'dejure_id'				=> array('UINT', null, 'auto_increment'),
						'dejure_key'			=> array('VCHAR:255', ''),
						'dejure_time'			=> array('VCHAR:255', ''),
						'dejure_text'			=> array('MTEXT_UNI', ''),
						'dejure_new_text'		=> array('MTEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'dejure_id',
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'dejure',
			),
		);
	}
}
