<?php

class RegisterController extends Controller {

	/**
	 * @inheritdoc
	 * @param string $name
	 * @param string $surname
	 * @param string $email
	 * @param string $phone
	 * @param string $address
	 * @param string $password
	 * @param string $description
	 * @param string $profil_img
	 * @param string $pseudo
	 * @param string $website
	 * @http_verb post
	 * @alias_method register
	 * @not_in_doc
	 * @throws Exception
	 */
	protected function index(RegisterModel $model = null, HttpService $httpService = null) {
		return $this->register($model, $httpService);
	}

	/**
	 * @title REGISTER USER
	 * @describe Enregistre un utilisateur et affiche l'utilisateur créé si il y a succes.
	 * @param string $name
	 * @param string $surname
	 * @param string $email
	 * @param string $phone
	 * @param string $address
	 * @param string $password
	 * @param string $description
	 * @param string $profil_img
	 * @param string $pseudo
	 * @param string $website
	 * @http_verb post
	 * @return Response
	 * @throws Exception
	 */
	protected function register(RegisterModel $model, HttpService $httpService) {
		$infos = [];
		foreach ($_POST as $key => $value) {
			if($key === 'describe') {
				$infos['description'] = $value;
			}
			else {
				$infos[$key] = $value;
			}
		}
		$user = $model->register_user($infos);
		return $this->get_response($user);
	}
}