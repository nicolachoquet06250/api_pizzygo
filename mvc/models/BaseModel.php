<?php

class BaseModel extends Model {
	private $mysql;
	public function __construct() {
		$mysql_conf = new Conf('mysql');
		$this->mysql = new mysqli(
			$mysql_conf->get('host'),
			$mysql_conf->get('user'),
			$mysql_conf->get('password'),
			$mysql_conf->get('database')
		);
	}

	protected function get_mysql() {
		return $this->mysql;
	}
}