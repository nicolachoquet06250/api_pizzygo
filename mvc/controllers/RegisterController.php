<?php

class RegisterController extends Controller {
	/** @var RegisterModel $model */
	public $model;

	/**
	 * @inheritdoc
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
	 * @param bool $premium
	 * @param bool $active
	 * @http_verb post
	 * @alias_method register
	 * @not_request
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
	 * @param bool $premium
	 * @param bool $active
	 * @http_verb post
	 * @return Response
	 * @throws Exception
	 */
	public function register(): JsonResponse {
		$infos = [];
		foreach ($this->post() as $key => $value) {
			$infos[($key === 'describe' ? 'description' : $key)] = $value;
		}
		$user = $this->model->register_user($infos);
		return $this->get_response($user);
	}
}