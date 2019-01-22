<?php

require_once __DIR__.'/../autoload.php';

class Main extends Base {
	public function run() {
		try {
			$user_dao = $this->get_repository('user');
			// add user
			$user = $user_dao->create(function(Base $object) {
				$user = $object->get_entity('user');
				return $user->initFromArray(
					[
						'name' => 'Nicolas',
						'surname' => 'Choquet',
						'address' => utf8_decode('1102 ch des primevères, 06250 Mougins'),
						'email' => 'nicolachoquet06250@gmail.com',
						'phone' => '',
						'password' => sha1(sha1('2669NICOLAS21071995')),
						'description' => '',
						'profil_img' => '',
						'premium' => false,
						'active' => true,
						'activate_token' => ''
					]
				);
			});

			// add role linked to the last created user
			$role = $this->get_entity('role');
			$role->initFromArray(
				[
					'role' => 'role_user',
					'user_id' => $user->get('id'),
				]
			);
			$role->save(false);
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}
}

(new Main())->run();
