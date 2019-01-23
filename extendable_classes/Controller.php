<?php

abstract class Controller extends Base {
	private $method;
	protected $params;
	/** @var HttpService $http_service */
	protected $http_service;

	/**
	 * Controller constructor.
	 *
	 * @param $action
	 * @param $params
	 * @throws Exception
	 */
	public function __construct($action, $params) {
		// Si ma méthode existe
		$current_class = get_class($this);
		$class_methods = get_class_methods($current_class);
		// Je vérifie l'existance de la méthode qui est la valeur de $action
		if(in_array($action, $class_methods)) {
			// Je valorise mes propriétés
			$this->method = $action;
			$this->params = $params;
		}
		// Sinon je lance une exception
		else {
			throw new Exception('La méthode '.get_class($this).'::'.$action.'() n\'existe pas !');
		}
		$this->http_service = $this->get_service('http');
	}

	abstract protected function index();

	/**
	 * @return array
	 */
	public function run() {
		$method = $this->method;
		return $this->$method();
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	protected function get($key) {
		return $this->http_service->get($key);
	}

	/**
	 * @param $key
	 * @return string|null
	 */
	protected function post($key) {
		return $this->http_service->post($key);
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	protected function files($key) {
		return $this->http_service->files($key);
	}
}