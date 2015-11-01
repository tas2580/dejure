<?php
/**
*
* @package phpBB Extension - tas2580 dejure
* @copyright (c) 2014 tas2580
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\dejure\acp;

class dejure_module
{
	public $u_action;

	public function main($id, $mode)
	{
		global $config, $user, $template, $request, $db, $table_prefix;

		$user->add_lang_ext('tas2580/dejure', 'common');

		$this->tpl_name = 'acp_dejure_body';
		$this->page_title = $user->lang('ACP_DEJURE_TITLE');

		add_form_key('acp_dejure');

		// Form is submitted
		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('acp_dejure'))
			{
				trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Set the new settings to config
			$config->set('tas2580_dejure_link_style', $request->variable('link_style', 'weit'));
			$config->set('tas2580_dejure_class', $request->variable('class', ''));
			$config->set('tas2580_dejure_buzer', $request->variable('buzer', 0));
			$config->set('tas2580_dejure_cache_time', $request->variable('cache_time', 4));

			$sql = 'DELETE FROM ' . $table_prefix . 'dejure';
			$db->sql_query($sql);
			trigger_error($user->lang('ACP_SAVED') . adm_back_link($this->u_action));
		}

		// Send the curent settings to template
		$template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'LINK_STYLE_WEIT'		=> ($config['tas2580_dejure_link_style'] === 'weit') ? ' selected="selected"' : '',
			'LINK_STYLE_SCHMAL'	=> ($config['tas2580_dejure_link_style'] === 'schmal') ? ' selected="selected"' : '',
			'CLASS'				=> $config['tas2580_dejure_class'],
			'BUZER'				=> ($config['tas2580_dejure_buzer'] == 1) ? ' checked="checked"' : '',
			'CACHE_TIME'			=> $config['tas2580_dejure_cache_time'],
		));
	}
}
