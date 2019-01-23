<?php

class LoginModel extends BaseModel {
	/** @var SessionService $session_service */
	protected $session_service;
	protected $session_key = 'user';
	public function __construct() {
		parent::__construct();
		$this->session_service = $this->get_service('session');
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
		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		$user = $user_dao->getByEmailAndPassword($email, $password);
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