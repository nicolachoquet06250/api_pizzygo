<?php

class Entity {
	private $fields = [];
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
		$ref = new ReflectionClass(get_class($this));
		$this->fields = $ref->getProperties();
		$this->mysql = $mysql;
		$this->table_name = strtolower(str_replace('Entity', '', get_class($this)));
	}

	protected function get_mysql() {
		return $this->mysql;
	}

	public function isUpdated() {
		return $this->updated;
	}

	public function get_fields($except = []) {
		$fields = [
			'id',
			'name',
			'surname',
			'address',
			'email',
			'phone',
		];
		$_fields = [];
		foreach ($fields as $field) {
			if(!in_array($field, $except)) {
				$_fields[] = $field;
			}
		}
		return $_fields;
	}

	public function save() {
		$request = 'UPDATE '.$this->table_name.' SET ';
		foreach ($this->get_fields(['id']) as $i => $field) {
			if($i > 0) {
				$request .= ', ';
			}
			$value = $this->get($field);
			$request .= '`'.$field.'`='.(gettype($value) === 'string' ? '"'.$value.'"' : $value);
		}
		$request .= 'WHERE `id`='.$this->get('id');
		$this->get_mysql()->query('UPDATE '.$this->table_name.' SET ``="" WHERE `id`='.$this->get('id'));
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
	 * @param $key
	 */
	public function set($prop, $key) {
		if(isset($this->$prop) && $this->$prop !== null) {
			$this->$prop = $key;
		}
	}
}