<?php

class HomeController extends Controller {
	/**
	 * @return array
	 * @throws Exception
	 */
	public function index() {
		return [
			'controller' => 'home',
			'get' => $this->get_service('http')->get(),
			'session' => $this->get_service('http')->session()
		];
	}
}