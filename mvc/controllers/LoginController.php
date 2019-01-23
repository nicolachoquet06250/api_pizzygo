<?php

class LoginController extends Controller {
	/**
	 * @throws Exception
	 */
	public function index() {
		return $this->login();
	}

	/**
	 * @throws Exception
	 */
	public function login() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		$user = $model->login($this->get('email'), $this->get('password'));
		if($user) {
			if (is_object($user) && $model->register_session($user)) {
				return $user->toArrayForJson();
			}
		}
		return $user;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function logged() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		return [
			'logged' => $model->isLogged()
		];
	}

	/**
	 * @throws Exception
	 */
	public function disconnect() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');

		return [
			'disconnected' => $model->delete_session()
		];
	}
}