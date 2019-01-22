<?php

class LoginController extends Controller {
	/**
	 * @throws Exception
	 */
	public function index() {
		$this->login();
	}

	/**
	 * @throws Exception
	 */
	public function login() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		return [];
	}
}