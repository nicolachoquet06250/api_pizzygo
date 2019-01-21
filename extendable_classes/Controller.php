<?php

class Controller extends Base {
	private $method;
	protected $params;

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
	}

	/**
	 * @return array
	 */
	public function run() {
		$method = $this->method;
		return $this->$method();
	}

	protected function get($key) {
		return isset($this->params[$key]) ? $this->params[$key] : null;
	}

	protected function post($key) {
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	protected function files($key) {
		return isset($_FILES[$key]) ? $_FILES[$key] : null;
	}
}