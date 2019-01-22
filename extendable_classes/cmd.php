<?php

class cmd extends Base {
	private $args;
	private $mysql;

	/**
	 * cmd constructor.
	 *
	 * @param $args
	 * @throws Exception
	 */
	public function __construct($args) {
		$this->args = $args;
		/** @var MysqlService $mysql_service */
		$mysql_service = $this->get_service('mysql');
		$this->mysql = $mysql_service->get_connector();
	}

	protected function get_arg($key) {
		return isset($this->args[$key]) ? $this->args[$key] : null;
	}

	public function run($method) {
		if(in_array($method, get_class_methods(get_class($this)))) {
			$this->$method();
		}
	}

	protected function get_mysql() {
		return $this->mysql;
	}
}