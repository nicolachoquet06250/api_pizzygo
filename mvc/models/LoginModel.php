<?php

class LoginModel extends BaseModel {
	/** @var SessionService $session_service */
	protected $session_service;
	/** @var HttpService $http_service */
	protected $http_service;
	protected $session_key = 'user';
	public function __construct() {
		parent::__construct();
		$this->session_service = $this->get_service('session');
		$this->http_service = $this->get_service('http');
	}

	public function isLogged() {
		return $this->session_service->has_key($this->session_key);
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @return array|bool|UserEntity
	 * @throws Exception
	 */
	public function login(string $email, string $password) {
		$this->session_service->set('domain', $this->http_service->get('domain'));
		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		$user = $user_dao->getByEmailAndPassword($email, sha1(sha1($password)));
		return $user ? $user : [ 'status' => false, 'message' => 'account not found' ];
	}

	public function register_session(UserEntity $user) {
		$this->session_service->set($this->session_key, $user->toArrayForJson());
		return $this->session_service->has_key($this->session_key);
	}

	public function delete_session() {
		$this->session_service->remove($this->session_key);
		return !$this->session_service->has_key($this->session_key);
	}
}