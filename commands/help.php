<?php

class help extends cmd {
	protected function index() {
		echo " ## HELP FOR COMMANDS ##\n";
	}

	/**
	 * @throws Exception
	 */
	protected function create_user_with_role() {
		Command::create(['install:db', 'prefix=test_']);

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
	}

	/**
	 * @throws Exception
	 */
	protected function to_array_for_json() {
		$user = $this->get_entity('user');
		$user->initFromArray(
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
		return $user->toArrayForJson();
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function conf() {
		return [
			$this->get_conf('mysql')->get('host'),
			$this->get_conf('mysql')->get('user'),
			$this->get_conf('mysql')->get('password'),
			$this->get_conf('mysql')->get('database'),
		];
	}
}