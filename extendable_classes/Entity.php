<?php

class Entity {
	private $fields = [];
	protected $primary_key;
	private $mysql;
	protected $updated = false;
	protected $table_name;

	/**
	 * Entity constructor.
	 *
	 * @param null|mysqli $mysql
	 * @throws ReflectionException
	 */
	public function __construct($mysql = null) {
		$this->init_fields();
		$this->mysql = $mysql;
		$this->table_name = strtolower(str_replace('Entity', '', get_class($this)));
	}

	protected function get_mysql() {
		return $this->mysql;
	}

	public function set_mysql(mysqli $mysqli) {
		$this->mysql = $mysqli;
	}

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
				$doc_comment = str_replace(['/**'."\n", '*/', "\t", ' * '], '', $doc_comment);
				$doc_comment = substr($doc_comment, 0, strlen($doc_comment)-2);
				$doc_comment = explode("\n", $doc_comment);
				$this->fields[$prop->getName()] = [];
				$this->fields[$prop->getName()]['value'] = null;
				foreach ($doc_comment as $doc_line) {
					if($doc_line === '@primary') {
						$this->primary_key = $prop->getName();
						$this->fields[$prop->getName()]['key'] = 'primary';
					}
					if(strstr($doc_line, '@var ') !== false) {
						$this->fields[$prop->getName()]['type'] = explode(' ', $doc_line)[1];
					}
					if($doc_line === '@text') {
						$this->fields[$prop->getName()]['sql']['type'] = 'TEXT';
					}
					if(strstr($doc_line, '@size')) {
						$this->fields[$prop->getName()]['sql']['type'] = 'VARCHAR';
						$this->fields[$prop->getName()]['sql']['size'] = (int)str_replace(['@size(', ')'], '', $doc_line);
					}
				}
			}
		}
	}

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

	public function save($exists = true) {
		if($exists) {
			$request = 'UPDATE '.$this->table_name.' SET ';
			$i = 0;
			foreach ($this->get_fields(['id']) as $field => $details) {
				if ($i > 0) {
					$request .= ', ';
				}
				$value   = $this->get($field);
				$request .= '`'.$field.'`='.(gettype($value) === 'string' ? '"'.$value.'"' : $value);
				$i++;
			}
			$request .= 'WHERE `id`='.$this->get('id');
		}
		else {
			$request = 'INSERT INTO '.$this->table_name.' SET ';
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
				$request .= '`'.$field.'`='.$value;
				$i++;
			}
		}
		$result = $this->get_mysql()->query($request);
		if(!$exists) {
			$req = $this->get_mysql()->query('SELECT id FROM '.$this->table_name.' ORDER BY id ASC LIMIT 1');
			while (list($id) = $req->fetch_array()) {
				$this->set('id', $id);
			}
		}
		return $result ? $this : false;
	}

	public function delete() {
		$this->get_mysql()->query('DELETE FROM '.$this->table_name.' WHERE `id`='.$this->get('id'));
	}

	/**
	 * @param $prop
	 * @return null
	 */
	public function get($prop) {
		return isset($this->$prop) && $this->$prop !== null ? $this->$prop : null;
	}

	/**
	 * @param $prop
	 * @param $value
	 * @param bool $update
	 */
	public function set($prop, $value, $update = false) {
		if(isset($this->$prop) && $this->$prop !== null) {
			$this->$prop = $value;
			$this->fields[$prop]['value'] = $value;
			if($update) $this->updated = $update;
		}
	}
}