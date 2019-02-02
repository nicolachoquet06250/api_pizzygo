<?php

class LoginController extends Controller {

	/** @var SessionService $session_service */
	public $session_service;

	/** @var LoginModel $model */
	public $model;

	/**
	 * @inheritdoc
	 * @title LOGIN USER
	 * @param string $email
	 * @param string $password
	 * @alias_method login
	 * @http_verb get
	 * @throws Exception
	 */
	public function index(): JsonResponse {
		return $this->login();
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @http_verb get
	 * @return Response|ErrorController
	 * @throws Exception
	 */
	public function login(): JsonResponse {
		if(!$this->session_service->has_key('user')) {
			if($this->get('email') && $this->get('password')) {
				$user  = $this->model->login($this->get('email'), $this->get('password'));
				if ($user) {
					if (is_object($user) && $this->model->register_session($user)) {
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
	public function logged(): JsonResponse {
		return $this->get_response(
			[
				'logged' => $this->model->isLogged()
			]
		);
	}

	/**
	 * @title DISCONNECT USER
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	public function disconnect(): JsonResponse {
		return $this->get_response(
			[
				'disconnected' => $this->model->delete_session()
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
	public function logged_user(): JsonResponse {
		return $this->get_response(($this->session_service->has_key('user') ? [
			'status' => true,
			'user' => $this->session_service->get('user')
		] : [
			'status' => false
		]));
	}

	/**
	 * @param string $email
	 * @http_verb post
	 * @return ErrorController|JsonResponse|Response
	 * @throws Exception
	 */
	public function forgot_password(UserDao $userDao, EmailService $emailService, TokenService $tokenService, Micro_templatingService $templatingService) {
		if($this->post('email')) {
			/** @var UserEntity[] $user */
			$user = $userDao->getBy('email', $this->post('email'));
			if($user) {
				$user = $user[0];
			}
			$token = $tokenService->generate_token_for_user($user);
			$user->set('activate_token', $token);
			$user->save();
			$templatingService->set_path(__DIR__.'/../views/emails');
			$emailService->html()->charset('utf-8')
								 ->set_from_name('Pizzygo')
								->to($this->post('email'), 'Pizzygo')
								->object('Changement de mot de passe pizzygo')
								->message($templatingService->display('forgot_password', [
									'surname' => $user->get('surname'),
									'token' => $token
								]))
								->send();
			return $this->get_response(
				[
					'status' => true,
					'message' => 'Un email vous à été envoyé',
				]
			);
		}
		elseif ($this->get('token')) {
			/** @var UserEntity $user */
			$user = $userDao->getBy('activate_token', $this->get('token'));
			if($user) {
				$user = $user[0];
			}
			else {
				return $this->get_error_controller(404)->message('No users found');
			}
			return $this->get_response($user);
		}
		return $this->get_error_controller(503)->message('parameter is required');
	}
}