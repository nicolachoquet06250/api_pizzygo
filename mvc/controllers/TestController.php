<?php

class TestController extends Controller {

	/**
	 * @return array
	 */
	protected function index() {
		return [];
	}

	/**
	 * @throws Exception
	 */
	public function get_user() {
		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		$email = $this->get('email');
		$password = $this->get('password');
//		return $user_dao->getId_Name_SurnameByEmailAndPassword($email, sha1(sha1($password)));
//		return $user_dao->getId_Name_Surname_Email_DescriptionByEmailAndPassword($email, sha1(sha1($password)));
		return $user_dao->getByEmailAndPassword($email, sha1(sha1($password)))->toArrayForJson();
	}
}