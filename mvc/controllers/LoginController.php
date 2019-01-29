<?php

class LoginController extends Controller {
	/**
	 * @inheritdoc
	 * @throws Exception
	 */
	protected function index() {
		return $this->login();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function login() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		$user = $model->login($this->get('email'), $this->get('password'));
		if($user) {
			if (is_object($user) && $model->register_session($user)) {
				return $this->get_response(
					[
						'status' => true,
						'user' => $user->toArrayForJson(),
					]
				);
			}
		}
		return $this->get_response($user);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function logged() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		return $this->get_response(
			[
				'logged' => $model->isLogged()
			]
		);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function disconnect() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');

		return $this->get_response(
			[
				'disconnected' => $model->delete_session()
			]
		);
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function logged_user() {
		/** @var SessionService $session_service */
		$session_service = $this->get_service('session');

		return $this->get_response(($session_service->has_key('user') ? [
			'status' => true,
			'user' => $session_service->get('user')
		] : [
			'status' => false
		]));
	}
}