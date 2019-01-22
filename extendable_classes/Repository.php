<?php

class Repository extends Base {
	private $entity_class;
	private $mysql;
	private $table_name;
	private $result = [];

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
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->table_name);
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
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->table_name.' ORDER BY `id` DESC');
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
		$query = $this->get_mysql()->query('SELECT * FROM '.$this->table_name.' ORDER BY `id` ASC');
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
	 * @return array
	 * @throws Exception
	 */
	public function getBy($field, $value) {
		$query = $this->get_mysql()
					  ->query('SELECT * FROM '.$this->table_name.' WHERE `'.$field.'`='
							  .(gettype($value) === 'string' ? '"'.$value.'"' : $value));
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
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getById($id) {
		return $this->getBy('id', $id);
	}

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
		$entity->set_mysql($this->mysql);
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
}