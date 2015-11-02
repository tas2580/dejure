<?php
/**
*
* @package phpBB Extension - tas2580 dejure
* @copyright (c) 2014 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\dejure\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;
	/* @var \phpbb\db\driver\driver */
	protected $db;
	/** @var string $dejure_table */
	protected $dejure_table;

	/**
	* Constructor
	*
	* @param \phpbb\config\config $config
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, $dejure_table)
	{
		$this->config = $config;
		$this->db = $db;
		$this->dejure_table = $dejure_table;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_post_row'		=> 'viewtopic_modify_post_row',
			'core.page_footer'					=> 'page_footer',
		);
	}

	/**
	* Replace post text with text from dejure.org
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		$row = $event['post_row'];
		$row['MESSAGE'] = $this->link_text($row['MESSAGE']);
		$event['post_row'] = $row;
	}

	/**
	* Delete the cache
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function page_footer($event)
	{
		if ($event['run_cron'])
		{
			$sql = 'DELETE FROM ' . $this->dejure_table . '
				WHERE dejure_time < ' . (int) ($this->config['tas2580_dejure_cache_time'] - time());
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Link text with dejure.org
	 *
	 * @param string $text
	 * @return string
	 */
	private function link_text($text)
	{
		$new_text = $this->read_cache($text);
		if (empty($new_text))
		{
			// No cache get the text
			return $this->get_link($text);
		}
		else
		{
			// return cached text
			return $new_text;
		}
	}


	/**
	 * Get link from dejure.org
	 *
	 * @param string $text
	 * @return string
	 */
	private function get_link($text)
	{
		$text = trim($text);

		$timeout = 3;
		$content = 'Originaltext=' . urlencode($text) .
			'&Anbieterkennung=' . $this->config['server_name'] . '__' . $this->config['board_contact'] .
			'&format=' . $this->config['tas2580_dejure_link_style'] .
			'&class=' . $this->config['tas2580_dejure_class'] .
			'&target= ' . $this->config['tas2580_dejure_target'] .
			'&buzer=' . $this->config['tas2580_dejure_buzer'];

		// generate the post header
		$header = 'POST /dienste/vernetzung/vernetzen HTTP/1.1'."\r\n";
		$header .= 'User-Agent: ' . $this->config['server_name'] . ' (phpBB3-Vernetzung 0.1.1)'."\r\n";
		$header .= 'Content-type: application/x-www-form-urlencoded'."\r\n";
		$header .= 'Content-length: ' . strlen($content) . "\r\n";
		$header .= 'Host: rechtsnetz.dejure.org'."\r\n";
		$header .= 'Connection: close'."\r\n";
		$header .= "\r\n";

		$fp = @fsockopen('rechtsnetz.dejure.org', 80, $errno, $errstr, $timeout);
		if (!$fp)
		{
			// No connection, return original text
			return $text;
		}
		else
		{
			stream_set_timeout($fp, $timeout, 0);
			stream_set_blocking($fp, true);
			fputs($fp, $header . $content);
			$socket_eof = $socket_timeout = false;
			$return = '';
			while (!$socket_eof && !$socket_timeout)
			{
				$return .= fgets($fp, 1024);
				$data = stream_get_meta_data($fp);
				$socket_eof = $data['eof'];
				$socket_timeout = $data['timed_out'];
			}
			fclose($fp);

			if (!preg_match("/^(.*?)\r?\n\r?\n\r?\n?(.*)/s", $return, $rueckgabeARR))
			{
				// something went wrong, return original text
				return $text;
			}
			else if (strpos($rueckgabeARR[1], '200 OK') === false)
			{
				// something went wrong, return original text
				return $text;
			}
			else
			{
				$return = $rueckgabeARR[2];
				if (strlen($return) < strlen($text))
				{
					// something went wrong, return original text
					return $text;
				}
				return $this->check($text, $return);
			}
		}
	}

	/**
	 * Check if data is valide
	 *
	 * @param string $original
	 * @param string $new_text
	 * @return string
	 */
	private function check($original, $new_text)
	{
		$original		= trim($original);
		$new_text	= trim($new_text);
		if (preg_replace("/<a href=\"http:\/\/dejure.org\/[^>]*>([^<]*)<\/a>/i", "\\1", $original) == preg_replace("/<a href=\"http:\/\/dejure.org\/[^>]*>([^<]*)<\/a>/i", "\\1", $new_text))
		{
			$this->write_cache($original, $new_text);
			return $new_text;
		}
		else
		{
			return $original;
		}
	}

	/**
	 * Read text from cache
	 *
	 * @param string $text
	 * @return string
	 */
	private function read_cache($text)
	{
		$sql = 'SELECT *
			FROM ' . $this->dejure_table . "
			WHERE dejure_key = '" . md5($text) . "'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row['dejure_new_text'];
	}

	/**
	 * Write data to cache
	 *
	 * @param string $original
	 * @param string $new_text
	 */
	private function write_cache($original, $new_text)
	{
		$sql_data = array(
			'dejure_key'			=> md5($original),
			'dejure_time'			=> time(),
			'dejure_text'			=> $original,
			'dejure_new_text'		=> $new_text,
		);
		$sql = 'INSERT INTO ' . $this->dejure_table . '
			' . $this->db->sql_build_array('INSERT', $sql_data);
		$this->db->sql_query($sql);
	}
}
