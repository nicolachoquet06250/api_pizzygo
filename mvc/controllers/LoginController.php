<?php

class LoginController extends Controller {
	/**
	 * @inheritdoc
	 * @title LOGIN USER
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
	 * @return Response|ErrorController
	 * @throws Exception
	 */
	protected function login() {
		/** @var SessionService $session_service */
		$session_service = $this->get_service('session');
		/** @var LoginModel $model */
		if(!$session_service->has_key('user')) {
			if($this->get('email') && $this->get('password')) {
				$model = $this->get_model('login');
				$user  = $model->login($this->get('email'), $this->get('password'));
				if ($user) {
					if (is_object($user) && $model->register_session($user)) {
						return $this->get_response(
							[
								'status' => true,
								'user'   => $user->toArrayForJson(),
							]
						);
					}
				}
				return $this->get_response($user);
			}
			return $this->get_error_controller(403)->message('you must fill in your email and your password');
		}
		return $this->get_error_controller(501)->message('You are already login');
	}

	/**
	 * @title USER LOGGED
	 * @describe Renvoie status=true si l'utilisateur est loggé
	 * et status=false si'l ne l'est pas
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
	 * @title DISCONNECT USER
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
	 * @title LOGGED USER
	 * @describe Renvoie l'utilisateur actuellement connecté
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