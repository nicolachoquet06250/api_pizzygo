<?php

abstract class Controller extends Base {
	private $method;
	protected $params;
	/** @var HttpService $http_service */
	protected $http_service;
	/** @var ErrorController $http_error */
	protected $http_error;

	/**
	 * Controller constructor.
	 *
	 * @param $action
	 * @param $params
	 * @throws Exception
	 */
	public function __construct($action, $params) {
		$current_class = get_class($this);
		$class_methods = get_class_methods($current_class);
		$this->http_service = $this->get_service('http');

		if(in_array($action, $class_methods)) {
			$this->method = $action;
			$this->params = $params;
		}
		else $this->http_error = $this->get_error_controller(404)
									 ->message('La méthode '.get_class($this).'::'.$action.'() n\'existe pas !');
	}

	/**
	 * @return Response
	 */
	abstract protected function index();

	/**
	 * @return string
	 */
	public function run() {
		$method = $this->method;
		if($this->http_error) {
			return $this->http_error->display();
		}
		/** @var Response $response */
		$response = $this->$method();
		var_dump($response->display());
		return $response->display();
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

	/**
	 * @param int $code
	 * @return ErrorController
	 * @throws Exception
	 */
	protected function get_error_controller(int $code) {
		$error_action = '_'.$code;
		return new ErrorController($error_action, []);
	}
}