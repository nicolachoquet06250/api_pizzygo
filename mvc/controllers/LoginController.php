<?php

class LoginController extends Controller {
	/**
	 * @inheritdoc
	 * @param string $email
	 * @param string $password
	 * @alias_method login
	 * @http_verb get
	 * @throws Exception
	 */
	protected function index() {
		return $this->login();
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function login() {
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
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function logged() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');
		return $this->get_response(
			[
				'logged' => $model->isLogged()
			]
		);
	}

	/**
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function disconnect() {
		/** @var LoginModel $model */
		$model = $this->get_model('login');

		return $this->get_response(
			[
				'disconnected' => $model->delete_session()
			]
		);
	}

	/**
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function logged_user() {
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