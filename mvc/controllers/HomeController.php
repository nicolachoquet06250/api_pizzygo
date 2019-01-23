<?php

class HomeController extends Controller {
	/**
	 * @inheritdoc
	 * @throws Exception
	 */
	protected function index() {
		return $this->home();
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function home() {
		/** @var HttpService $http_service */
		$http_service = $this->get_service('http');
		return [
			'controller' => 'home',
			'get' => $http_service->get(),
			'session' => $http_service->session(),
			'server' => $_SERVER['SERVER_SOFTWARE'],
		];
	}
}