<?php

class Repository extends Base {
	private $entity_class;
	private $mysql;
	protected $table_name;
	private $result = [];
	protected $mysql_conf;

	/**
	 * Repository constructor.
	 *
	 * @throws Exception
	 */
	public function __construct() {
		$this->entity_class = get_class($this);
		$this->entity_class = explode("\\", $this->entity_class)[count(explode("\\", $this->entity_class))-1];
		$this->entity_class = str_replace(
			[
				'Repository',
				'Dao'
			], 'Entity', $this->entity_class);
		$this->table_name = strtolower(str_replace('Entity', '', $this->entity_class));
		require_once __DIR__.'/../entities/'.$this->entity_class.'.php';
		/** @var MysqlService $mysql_service */
		$this->mysql_conf = $this->get_conf('mysql');
		$mysql_service = $this->get_service('mysql');
		$this->mysql = $mysql_service->get_connector();
	}

	/**
	 * @return mysqli
	 */
	protected function get_mysql() {
		return $this->mysql;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAll() {
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->get_table_name());
		$entities = [];
		$entity_class = $this->entity_class;
		while ($entity = $query->fetch_assoc()) {
			/** @var Entity $_entity */
			$_entity = new $entity_class();
			foreach ($entity as $key => $value) {
				$_entity->set($key, $value);
			}
			$entities[] = $_entity;
		}
		$this->result = $entities;
		return $entities;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAllDesc() {
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->get_table_name().' ORDER BY `id` DESC');
		$entities = [];
		$entity_class = $this->entity_class;
		while ($entity = $query->fetch_assoc()) {
			/** @var Entity $_entity */
			$_entity = new $entity_class();
			foreach ($entity as $key => $value) {
				$_entity->set($key, $value);
			}
			$entities[] = $_entity;
		}
		$this->result = $entities;
		return $entities;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAllAsc() {
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->get_table_name().' ORDER BY `id` ASC');
		$entities = [];
		$entity_class = $this->entity_class;
		while ($entity = $query->fetch_assoc()) {
			/** @var Entity $_entity */
			$_entity = new $entity_class();
			foreach ($entity as $key => $value) {
				$_entity->set($key, $value);
			}
			$entities[] = $_entity;
		}
		$this->result = $entities;
		return $entities;
	}

	/**
	 * @param $field
	 * @param $value
	 * @return array|bool
	 * @throws Exception
	 */
	public function getBy($field, $value) {
		$query = $this->get_mysql()
					  ->query('SELECT * FROM '.$this->get_table_name().' WHERE `'.$field.'`='
							  .(gettype($value) === 'string' ? '"'.$value.'"' : $value));
		$entities = [];
		$entity_class = $this->entity_class;
		if($query) {
			while ($entity = $query->fetch_assoc()) {
				/** @var Entity $_entity */
				$_entity = new $entity_class();
				foreach ($entity as $key => $value) {
					$_entity->set($key, $value);
				}
				$entities[] = $_entity;
			}
			$this->result = $entities;
			return $entities;
		}
		return false;
	}

	/**
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getById($id) {
		return $this->getBy('id', $id);
	}

	/**
	 * @throws Exception
	 */
	public function save() {
		/** @var Entity $entity */
		foreach ($this->result as $entity) {
			if($entity->isUpdated()) {
				$entity->save();
			}
		}
	}

	/** @param Entity|callable $entity
	 * @return bool|Entity
	 * @throws Exception
	 */
	public function create($entity) {
		if(get_class($entity) === 'Closure') {
			$entity = $entity(new Base());
		}
		return $entity->save(false);
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	public function deleteFromId($id) {
		$this->getAll();
		$nb = count($this->result);
		/** @var Entity $entity */
		foreach ($this->result as $entity) {
			if($entity->get('id') === $id) {
				$entity->delete();
			}
		}
		$new_nb = count($this->result);
		if($nb === $new_nb) {
			return false;
		}
		return true;
	}

	/**
	 * @param bool $for_insert
	 * @return string
	 */
	protected function get_table_name($for_insert = true) {
		$prefix = '';
		if($this->mysql_conf->has_property('table-prefix')) {
			$prefix = $this->mysql_conf->get('table-prefix');
		}
		$table_name = '';
		if($for_insert) {
			$table_name .= '`';
		}
		$table_name .= $prefix.$this->table_name;
		if($for_insert) {
			$table_name .= '`';
		}
		return $table_name;
	}

	/**
	 * @param string $column
	 * @return bool
	 */
	private function column_exists(string $column) {
		$query = $this->get_mysql()->query('SHOW COLUMNS FROM '.$this->get_table_name());
		while (list($field,,,,) = $query->fetch_array()) {
			if($column === $field) {
				return true;
			}
		}
		return false;
	}

	public function __call($name, $arguments) {
		$regexs = [
			'`getBy([A-Z][a-z0-9]+)(And|Or)([A-Z][a-z0-9]+)+`' => function($matches, &$arguments) {
				$array = [];
				$_matches = [];
				unset($matches[0]);
				foreach ($matches as $item) {
					$_matches[] = $item;
				}
				$matches = $_matches;

				$i = 0;
				$j = 0;
				foreach ($matches as $match) {
					if($match !== 'And' && $match !== 'Or') {
						$array[strtolower($match)] = [
							'value' => $arguments[$i],
							'operator' => (isset($matches[$j+1]) && ($matches[$j+1] === 'And' || $matches[$j+1] === 'Or') ? strtoupper($matches[$j+1]) : null),
						];
						$i++;
					}
					$j++;
				}
				$request = 'SELECT * FROM '.$this->get_table_name().' WHERE ';
				foreach ($array as $field => $detail) {
					$request .= $field.'='.(is_string($detail['value']) ? '"'.$detail['value'].'"' : $detail['value']).' ';
					if(!is_null($detail['operator'])) {
						$request .= $detail['operator'].' ';
					}
				}
				$query = $this->get_mysql()->query($request);
				$result = [];
				while ($data = $query->fetch_assoc()) {
					$local = [];
					foreach ($this->get_entity($this->table_name)->get_fields() as $field => $detail) {
						$local[$field] = $data[$field];
					}
					$result[] = $this->get_entity($this->table_name)
									 ->initFromArray($local);
				}
				if(count($result) === 1) {
					$result = $result[0];
				}
				elseif (count($result) === 0) {
					return false;
				}
				return $result;
			},
			'`get([A-Za-z0-9\_]+)By([A-Z][a-z0-9]+)(And|Or)([A-Z][a-z0-9]+)+`' => function($matches, &$arguments) {

				$_matches = [];
				unset($matches[0]);
				foreach ($matches as $item) {
					$_matches[] = $item;
				}
				$matches = $_matches;

				$matches[0] = strtolower($matches[0]);
				$fields_selected = explode('_', $matches[0]);
				$_field_selected = [];
				foreach ($fields_selected as $i => $field_selected) {
					if($this->column_exists($field_selected))
						$_field_selected[] = $field_selected;
				}
				$fields_selected = $_field_selected;

				$_matches = [];
				unset($matches[0]);
				foreach ($matches as $item) {
					$_matches[] = $item;
				}
				$matches = $_matches;

				$array = [];
				$i = 0;
				$j = 0;
				foreach ($matches as $match) {
					if($match !== 'And' && $match !== 'Or') {
						$array[strtolower($match)] = [
							'value' => $arguments[$i],
							'operator' => (isset($matches[$j+1]) && ($matches[$j+1] === 'And' || $matches[$j+1] === 'Or') ? strtoupper($matches[$j+1]) : null),
						];
						$i++;
					}
					$j++;
				}
				$request = 'SELECT `'.implode('`, `', $fields_selected).'` FROM '.$this->get_table_name().' WHERE ';
				foreach ($array as $field => $detail) {
					$request .= $field.'='.(is_string($detail['value']) ? '"'.$detail['value'].'"' : $detail['value']).' ';
					if(!is_null($detail['operator'])) {
						$request .= $detail['operator'].' ';
					}
				}

				$query = $this->get_mysql()->query($request);
				$result = [];
				while ($data = $query->fetch_assoc()) {
					$local = [];
					foreach ($this->get_entity($this->table_name)->get_fields() as $field => $detail) {
						if(isset($data[$field])) {
							$local[$field] = $data[$field];
						}
					}
					if(count($local) < $this->get_entity($this->table_name)->get_fields()) {
						$entity = $this->get_entity($this->table_name);
						foreach ($local as $field => $value) {
							$entity->set($field, $value);
						}
					}
					else {
						$entity = $this->get_entity($this->table_name)
										 ->initFromArray($local);
					}
					$result[] = $entity;
				}
				if(count($result) === 1) {
					$result = $result[0];
				}
				elseif (count($result) === 0) {
					return false;
				}
				return $result;
			},
			'`delete([A-Za-z0-9\_]+)Where([A-Za-z0-9\_]+)`' => function($matches, &$arguments) {
				// TODO: create method code here
			},
			'`update([A-Za-z0-9\_]+)Where([A-Za-z0-9\_]+)`' => function($matches, &$arguments) {
				// TODO: create method code here
			},
		];

		$exists = false;
		foreach ($regexs as $regex => $callback) {
			preg_match($regex, $name, $matches);
			if(empty($matches)) {
				continue;
			}
			$exists = [
				'callback' => $callback,
				'matches' => $matches,
			];
			break;
		}
		if($exists) return $exists['callback']($exists['matches'], $arguments);
		else if(in_array($name, get_class_methods(get_class($this)))) {
			$params = '';
			$i = 0;
			foreach ($arguments as $argument) {
				if(is_string($argument)) {
					$params .= '"'.$argument.'"';
				}
				elseif (is_numeric($argument)) {
					$params .= $argument;
				}
				elseif (is_object($argument)) {
					$params .= '$arguments['.$i.']';
				}
				$i++;
			}
			return eval("$this->$name($params);");
		}
		return [];
	}
}