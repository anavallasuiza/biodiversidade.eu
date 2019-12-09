<?php
namespace Imaxin\Opentrad;

defined('ANS') or die();

class Opentrad
{
	private $Client;
	private $settings;
	private $languages;

	public function __construct ()
	{
		global $Config;

		if (empty($Config->opentrad)) {
			$Config->load('opentrad.php');
		}

		$this->settings = $Config->opentrad;
		$this->languages = array_keys(array_filter($Config->languages['availables']));

		if ($this->settings['load'] === false) {
			$this->Client = false;
		} else {
			try {
				$this->Client = new \SoapClient($this->settings['service'], array('trace' => 1));
			} catch (\Exception $e) {
				$this->Client = false;
			}
		}

		return $this;
	}

	public function translateQuery ($query, $id)
	{
		if (empty($query['table'])) {
			return $query;
		}

		global $Config, $Db;

		$fields = $Config->tables[getDatabaseConnection()][$query['table']];

		if (empty($fields)) {
			return $query;
		}

		if ($id) {
			$row = $Db->select(array(
				'table' => $query['table'],
				'fields' => '*',
				'language' => 'all',
				'limit' => 1,
				'conditions' => array(
					'id' => $id
				)
			));
		} else {
			$row = array();
		}

		foreach ($this->languages as $language) {
			if ($language === LANGUAGE) {
				continue;
			}

			$original = array();

			$XML = new SimpleXMLExtended('<root/>');

			foreach ($fields as $field => $settings) {
				if (is_string($settings)
				|| empty($settings['languages'])
				|| !in_array($settings['format'], array('varchar', 'text', 'html'))
				|| empty($query['data'][$field])) {
					continue;
				}

				if ($settings['languages'] !== 'all') {
					if (is_string($settings['languages'])) {
						$settings['languages'] = array($settings['languages']);
					}

					if (!in_array($language, $settings['languages'])) {
						continue;
					}
				}

				if ($row && $row[$field][$language]) {
					continue;
				}

				$XML->$field = null;
				$XML->$field->addCData($original[$field] = $query['data'][$field]);
			}

			$translation = $this->translateString($XML->asXML(), LANGUAGE, $language);

			if (preg_match('/^\-?[0-9]+$/', $translation)) {
				foreach ($original as $field => $string) {
					$query['data'][$field.'-'.$language] = $string;
				}

				continue;
			}

			$translation = json_decode(json_encode((array)simplexml_load_string($translation, null, LIBXML_NOCDATA)), 1);

			foreach ($translation as $field => $string) {
				$query['data'][$field.'-'.$language] = $string ?: $original[$field];
			}
		}

		foreach ($original as $field => $string) {
			$query['data'][$field.'-'.LANGUAGE] = $string;
			unset($query['data'][$field]);
		}

		return $query;
	}

	public function translateRow ($row, $table)
	{
		global $Config, $Db, $Vars;

		$fields = $Config->tables[getDatabaseConnection()][$table];

		if (empty($fields)) {
			return $row;
		}

		$languages = $Vars->getLanguages();
		$default = $languages[0];
		$original = array();

		$XML = new SimpleXMLExtended('<root/>');

		foreach ($fields as $field => $settings) {
			if (is_string($settings)
			|| empty($settings['languages'])
			|| !in_array($settings['format'], array('varchar', 'text', 'html'))
			|| !array_key_exists(LANGUAGE, $row[$field])) {
				continue;
			}

			if ($settings['languages'] === 'all') {
				$settings['languages'] = $languages;
			} else {
				if (is_string($settings['languages'])) {
					$settings['languages'] = array($settings['languages']);
				}

				if (!in_array(LANGUAGE, $settings['languages'])) {
					continue;
				}
			}

			if ($row[$field][LANGUAGE]) {
				$row[$field] = $row[$field][LANGUAGE];
				continue;
			}

			$language = $row['idioma'] ?: $default;

			$XML->$field = null;
			$XML->$field->addCData($original[$field] = $row[$field][$language]);
		}

		if (empty($original)) {
			return $row;
		}

		$translation = $this->translateString($XML->asXML(), $language, LANGUAGE);

		$save = array();

		if (preg_match('/^\-?[0-9]+$/', $translation)) {
			$translation = $original;
		} else {
			$translation = json_decode(json_encode((array)simplexml_load_string($translation, null, LIBXML_NOCDATA)), 1);
		}

		foreach ($translation as $field => $string) {
			if (is_string($string) !== true) {
				$row[$field] = '';
				continue;
			}

			$string = trim($string);
			$original[$field] = trim($original[$field]);

			if ($string && ($string !== $original[$field])) {
				$save[$field][LANGUAGE] = $row[$field] = $string;
			} else {
				$row[$field] = $original[$field];
			}
		}

		if ($save) {
			$Db->update(array(
				'table' => $table,
				'data' => $save,
				'limit' => 1,
				'conditions' => array(
					'id' => $row['id']
				)
			));
		}

		return $row;
	}

	public function translateString ($string, $from, $to, $type = 'html')
	{
		if (!is_object($this->Client)) {
			return $string;
		}

		try {
			return $this->Client-> __soapCall('translate', array(
				'i_direction' => ($from.'-'.$to),
				'i_type' => $type,
				'i_string' => $string,
				'i_user' => $this->settings['user_id'],
				'i_key' => $this->settings['user_key']
			));
		} catch (\Expression $e) {
			return $string;
		}
	}
}

class SimpleXMLExtended extends \SimpleXMLElement {
	public function addCData ($cdata_text) {
		$node = dom_import_simplexml($this); 
		$no   = $node->ownerDocument; 
		$node->appendChild($no->createCDATASection($cdata_text)); 
	} 
}
