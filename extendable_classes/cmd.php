<?php

class cmd extends Base {
	private $args;
	public function __construct($args) {
		$this->args = $args;
	}

	protected function get_arg($key) {
		return isset($this->args[$key]) ? $this->args[$key] : null;
	}

	public function run($method) {
		if(in_array($method, get_class_methods(get_class($this)))) {
			$this->$method();
		}
	}
}