<?php

class MysqlService extends Service {
	private static $mysql = null;

	public function initialize_after_injection() {
		if(is_null(self::$mysql)) {
			$conf_mysql = new Conf('mysql');
			self::$mysql = new mysqli(
				$conf_mysql->get('host'),
				$conf_mysql->get('user'),
				$conf_mysql->get('password'),
				$conf_mysql->get('database')
			);
		}
	}

	public function get_connector() {
		return self::$mysql;
	}
}