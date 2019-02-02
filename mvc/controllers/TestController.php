<?php

class TestController extends Controller {

	/**
	 * @not_in_doc
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	public function index() {
		return $this->get_response([]);
	}

	/**
	 * @param string $email
	 * @param string $password
	 * @http_verb get
	 * @return Response
	 * @throws Exception
	 */
	public function get_user(UserDao $user_dao) {
		$email = $this->get('email');
		$password = $this->get('password');
//		$user = $user_dao->getId_Name_SurnameByEmailAndPassword($email, sha1(sha1($password)));
//		$user = $user_dao->getId_Name_Surname_Email_DescriptionByEmailAndPassword($email, sha1(sha1($password)));
		$user = $user_dao->getByEmailAndPassword($email, sha1(sha1($password)))->toArrayForJson();
		return $this->get_response($user);
	}
}