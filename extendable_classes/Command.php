<?php

class Command extends Base {
	private static $default_action = 'index';
	public static function clean_args($args) {
		unset($args[0]);
		$_args = [];
		foreach ($args as $arg) {
			$_args[] = $arg;
		}
		return $_args;
	}

	public static function create($args) {
		if(strstr($args[0], ':')) {
			$args[0] = explode(':', $args[0]);
		}
		else {
			$args[0] = [
				$args[0],
			];
		}
		$controller = $args[0][0];
		$action = isset($args[0][1]) ? $args[0][1] : self::$default_action;
		$args = self::clean_args($args);
		require_once __DIR__.'/../commands/'.$controller.'.php';
		/** @var cmd $cmd */
		$cmd = new $controller($args);
		$cmd->run($action);
	}
}