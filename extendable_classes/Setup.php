<?php

class Setup extends Base {
	private $controller;
	/** @var JsonService $json_service */
	private $json_service;

	/**
	 * Setup constructor.
	 *
	 * @param $controller
	 * @throws Exception
	 */
	public function __construct($controller) {
		// Si la classe existe
		if(class_exists(ucfirst($controller).'Controller')) {
			// Je valorise ma propriété avec le paramètre
		 	$this->controller = $controller;
		}
		else {
			throw new Exception('Le controlleur '.$controller.' n\'existe pas !');
		}
		$this->json_service = $this->get_service('json');
	}

	/**
	 * @return false|string
	 * @throws Exception
	 */
	public function run() {
		$controller = ucfirst($this->controller).'Controller';
		if(isset($_GET['action'])) {
			$action = $_GET['action'];
			// Supprime de la mémoire les paramètres GET pour récupérer uniquement
			// les paramètres voulus et ne pas avoir le nom de mon controlleur et de ma méthode.
			unset($_GET['action']);
		}
		else {
			$action = 'index';
		}
		unset($_GET['controller']);
		$parzms = $_GET;
		/** @var Controller $ctrl */
		$ctrl = new $controller($action, $parzms);
		return $this->json_service->encode($ctrl->run());
	}
}