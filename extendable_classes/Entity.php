<?php

class Entity extends Base {
	private $fields = [];
	protected $primary_key;
	private $mysql;
	protected $updated = false;
	protected $table_name;
	protected $mysql_conf;

	/**
	 * Entity constructor.
	 *
	 * @param null|mysqli $mysql
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function __construct() {
		$this->init_fields();
		/** @var MysqlService $mysql_service */
		$mysql_service = $this->get_service('mysql');
		$this->mysql = $mysql_service->get_connector();
		$this->table_name = strtolower(str_replace('Entity', '', get_class($this)));
		$this->mysql_conf = $this->get_conf('mysql');
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function create_db() {
		$fields = $this->get_fields();
		$prefix = '';
		if($this->mysql_conf->has_property('table-prefix')) {
			$prefix = $this->mysql_conf->get('table-prefix');
		}
		$request = 'CREATE TABLE IF NOT EXISTS `'.$prefix.$this->table_name.'` (';
		$max = count($fields);
		$i = 0;
		foreach ($fields as $field => $details) {
			$size = 0;
			if($details['type'] === 'int' || $details['type'] === 'float') {
				$size = 11;
			}
			elseif (isset($details['sql']['type'])) {
				if(isset($details['sql']['size'])) {
					$size = $details['sql']['size'];
				}
			}
			else {
				$size = null;
			}
			$request .= $field.' '.strtoupper((isset($details['sql']['type']) ? $details['sql']['type'] : $details['type'])).($size ? '('.$size.')' : '').' '.
						(!$details['sql']['nullable'] ? 'NOT NULL ' : '');
			if(isset($details['key']) && $details['key'] === 'primary') {
				$request .= 'AUTO_INCREMENT PRIMARY KEY';
			}
			$i++;
			if($i < $max) {
				$request .= ',';
			}
		}
		$request .= ')';
		return $this->get_mysql()->query($request);
	}

	/**
	 * @return mysqli|null
	 */
	protected function get_mysql() {
		return $this->mysql;
	}

	/**
	 * @return bool
	 */
	public function isUpdated() {
		return $this->updated;
	}

	/**
	 * @throws ReflectionException
	 */
	private function init_fields() {
		$ref = new ReflectionClass(get_class($this));
		$props = $ref->getProperties();
		foreach ($props as $prop) {
			if($prop->class !== Entity::class) {
				$doc_comment = $prop->getDocComment();
				$doc_comment = str_replace(['/**'."\n", '/**'."\r", '*/', "\t", ' * '], '', $doc_comment);
				$doc_comment = substr($doc_comment, 0, strlen($doc_comment)-2);
				$_doc_comment = explode("\n", $doc_comment);
				if(strlen($_doc_comment[0]) === 0) {
					$doc_comment = explode("\r", $doc_comment);
				}
				else {
					$doc_comment = $_doc_comment;
				}
				$this->fields[$prop->getName()] = [];
				$this->fields[$prop->getName()]['value'] = null;
				$this->fields[$prop->getName()]['sql']['nullable'] = true;
				foreach ($doc_comment as $doc_line) {
					if($doc_line !== '') {
						if(strstr($doc_line, '@JsonExclude')) {
							$this->fields[$prop->getName()]['json_exclude'] = true;
						}
						if (strstr($doc_line, '@primary')) {
							$this->primary_key                     = $prop->getName();
							$this->fields[$prop->getName()]['key'] = 'primary';
						}
						if (strstr($doc_line, '@var ') !== false) {
							$this->fields[$prop->getName()]['type'] = explode(' ', $doc_line)[1];
							if (explode(' ', $doc_line)[1] === 'bool') {
								$this->fields[$prop->getName()]['sql']['type'] = 'boolean';
							}
						}
						if (strstr($doc_line, '@text')) {
							$this->fields[$prop->getName()]['sql']['type'] = 'TEXT';
						}
						if (strstr($doc_line, '@size') && $this->fields[$prop->getName()]['type'] !== 'int' && $this->fields[$prop->getName()]['type'] !== 'float') {
							$this->fields[$prop->getName()]['sql']['type'] = 'VARCHAR';
							$this->fields[$prop->getName()]['sql']['size'] = (int)str_replace(['@size(', ')'], '', $doc_line);
						}
						if (strstr($doc_line, '@not_null')) {
							$this->fields[$prop->getName()]['sql']['nullable'] = false;
						}
						if(strstr($doc_line, '@entity ')) {
							$this->fields[$prop->getName()]['entity'] = [
								'table' => strtolower(str_replace('Entity', '', explode(' ', $doc_line)[1])),
								'searchBy' => explode('_', $prop->getName())[1],
							];
						}
					}
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function get_primary_key() {
		return $this->primary_key;
	}

	/**
	 * @param array $except
	 * @return array
	 */
	public function get_fields($except = []) {
		$fields = [];
		foreach ($this->fields as $field => $details) {
			if(!in_array($field, $except)) {
				$fields[$field] = $details;
			}
		}
		return $fields;
	}

	/**
	 * @param array $array
	 * @return $this
	 * @throws Exception
	 */
	public function initFromArray(array $array) {
		foreach ($array as $key => $value) {
			$this->set($key, $value);
		}
		return $this;
	}

	/**
	 * @param bool $exists
	 * @return bool|Entity
	 * @throws Exception
	 */
	public function save($exists = true) {
		$prefix = '';
		if($this->mysql_conf->has_property('table-prefix')) {
			$prefix = $this->mysql_conf->get('table-prefix');
		}
		if($exists) {
			$request = 'UPDATE '.$prefix.$this->table_name.' SET ';
			$i = 0;
			foreach ($this->get_fields(['id']) as $field => $details) {
				if ($i > 0) {
					$request .= ', ';
				}
				$value   = $this->get($field);
				if(is_object($value) && $value instanceof Entity) {
					$_field = explode('_', $field)[1];
					$value = $value->get($_field);
				}
				elseif (is_string($value)) {
					$value = '"'.$value.'"';
				}
				$request .= '`'.$field.'`='.$value;
				$i++;
			}
			$request .= 'WHERE `id`='.$this->get('id');
		}
		else {
			$request = 'INSERT INTO '.$prefix.$this->table_name.' SET ';
			$i = 0;
			foreach ($this->get_fields(['id']) as $field => $details) {
				if ($i > 0) {
					$request .= ', ';
				}
				$value   = $this->get($field);
				if($details['type'] === 'string') {
					$value = '"'.$value.'"';
				}
				elseif ($details['type'] === 'bool') {
					$value = (int)$value;
				}
				elseif(is_object($value) && $value instanceof Entity) {
					$_field = explode('_', $field)[1];
					$value = $value->get($_field);
				}

				$request .= '`'.$field.'`='.$value;
				$i++;
			}
		}
		$result = $this->get_mysql()->query($request);
		if(!$exists) {
			$req = $this->get_mysql()->query('SELECT '.$this->get_primary_key().' FROM '.$prefix.$this->table_name.' ORDER BY '.$this->get_primary_key().' DESC LIMIT 1');
			while (list($id) = $req->fetch_array()) {
				$id = (int)$id;
				$this->set('id', $id);
			}
		}
		return $result ? $this : false;
	}

	public function delete() {
		$prefix = '';
		if($this->mysql_conf->has_property('table-prefix')) {
			$prefix = $this->mysql_conf->get('table-prefix');
		}
		$this->get_mysql()->query('DELETE FROM '.$prefix.$this->table_name.' WHERE `id`='.$this->get('id'));
	}

	/**
	 * @param $prop
	 * @return null
	 */
	public function get($prop) {
		foreach ($this->fields as $field => $details) {
			if (isset($details['entity']) && explode('_', $field)[0] === $prop) {
				return $this->$field;
			}
		}
		return isset($this->$prop) && $this->$prop !== null ? $this->$prop : null;
	}

	/**
	 * @param $prop
	 * @param $value
	 * @param bool $update
	 * @throws Exception
	 */
	public function set($prop, $value, $update = false) {
		if(isset($this->$prop) && $this->$prop !== null) {
			if(isset($this->fields[$prop]['entity'])) {
				$dao = $this->get_repository($this->fields[$prop]['entity']['table']);
				$_value = $dao->getBy($this->fields[$prop]['entity']['searchBy'], $value);
				$value = empty($_value) ? $value : $_value[0];
			}
			$this->$prop = $value;
			$this->fields[$prop]['value'] = $value;
			if($update) $this->updated = $update;
		}
	}

	public function toArrayForJson() {
		$array = [];
		foreach ($this->get_fields() as $field => $details) {
			$value = $this->get($field);
			if(isset($details['entity'])) {
				/** @var Entity $entity */
				$entity = $this->get($field);
				$value = $entity->get($details['entity']['searchBy']);
			}
			if(!isset($details['json_exclude'])) {
				$array[$field] = $value;
			}
		}
		return $array;
	}
}