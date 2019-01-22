<?php

class Conf {
	private $conf;
	public function __construct($conf) {
		$this->conf = require_once __DIR__.'/../conf/'.$conf.'.php';
	}

	public function get($key) {
		return isset($this->conf[$key]) ? $this->conf[$key] : null;
	}
}