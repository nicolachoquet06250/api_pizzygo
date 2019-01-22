<?php

class Router extends Base {
	/** @var HttpService $http */
	private $uri, $http;

	/**
	 * Router constructor.
	 *
	 * @param $uri
	 * @throws Exception
	 */
	private function __construct($uri) {
		$this->uri = $uri;
		$this->http = $this->get_service('http');
	}

	/**
	 * @param string $uri
	 * @param callable $callback
	 * @param callable $catch
	 * @return mixed
	 */
	public static function create(string $uri, callable $callback, callable $catch) {
		try {
			$router = new Router($uri);
			return $router->execute($callback);
		}
		catch (Exception $e) {
			$catch($e);
		}
		return false;
	}

	private function parse_uri() {
		if(is_null($this->http->get('controller'))) {
			$params = explode('?', $this->uri);
			$mvc = explode('/', $params[0]);
			$_GET['controller'] = $mvc[1];
			if(isset($mvc[2])) {
				$_GET['action'] = $mvc[2];
			}
		}
		$this->http->initialize_after_injection();
	}

	/**
	 * @param callable $callback
	 * @return mixed
	 * @throws Exception
	 */
	private function execute(callable $callback) {
		$this->parse_uri();
		if(is_null($this->http->get('controller')))
			throw new Exception('Vous devez définir un controlleur !');
		return $callback($this->http->get('controller'), $this->http);
	}
}