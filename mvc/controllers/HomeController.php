<?php

class HomeController extends Controller {
	/**
	 * @return array
	 * @throws Exception
	 */
	public function index() {
		/** @var HttpService $http_service */
		$http_service = $this->get_service('http');
		return [
			'controller' => 'home',
			'get' => $http_service->get(),
			'session' => $http_service->session()
		];
	}
}