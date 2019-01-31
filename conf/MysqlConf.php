<?php

class mysqlConf extends Conf {
	public function __construct() {
		$cnf = file_get_contents(__DIR__.'/mysql.json');
		$this->conf = json_decode($cnf, true);
	}
}