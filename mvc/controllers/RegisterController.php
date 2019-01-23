<?php

class RegisterController extends Controller {

	/**
	 * @inheritdoc
	 * @throws Exception
	 */
	protected function index() {
		return $this->register();
	}

	/**
	 * @throws Exception
	 */
	protected function register() {
		/** @var RegisterModel $model */
		$model = $this->get_model('register');
		$this->get_service('http');
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
		if(is_array($user)) {
			return $user;
		}
		return $user->toArrayForJson();
	}
}