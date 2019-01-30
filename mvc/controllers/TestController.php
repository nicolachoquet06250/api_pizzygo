<?php

class TestController extends Controller {

	/**
	 * @not_in_doc
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function index() {
		return $this->get_response([]);
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	protected function get_user() {
		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		$email = $this->get('email');
		$password = $this->get('password');
//		$user = $user_dao->getId_Name_SurnameByEmailAndPassword($email, sha1(sha1($password)));
//		$user = $user_dao->getId_Name_Surname_Email_DescriptionByEmailAndPassword($email, sha1(sha1($password)));
		$user = $user_dao->getByEmailAndPassword($email, sha1(sha1($password)))->toArrayForJson();
		return $this->get_response($user);
	}
}