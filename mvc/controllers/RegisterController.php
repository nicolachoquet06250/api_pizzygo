<?php

class RegisterController extends Controller {
	/** @var RegisterModel $model */
	public $model;

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
	public function index(): JsonResponse {
		return $this->register();
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
	public function register(): JsonResponse {
		$infos = [];
		foreach ($_POST as $key => $value) {
			if($key === 'describe') {
				$infos['description'] = $value;
			}
			else {
				$infos[$key] = $value;
			}
		}
		$user = $this->model->register_user($infos);
		return $this->get_response($user);
	}
}