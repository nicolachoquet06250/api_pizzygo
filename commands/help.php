<?php

class workerThread extends Thread {
	private $i;
	public function __construct($i){
		$this->i = $i;
	}

	public function run(){
		while(true){
			echo $this->i;
			sleep(1);
		}
	}
}

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

	/**
	 * @throws Exception
	 */
	protected function get_roles_by_user_id() {
		/** @var RoleDao $dao */
		$dao = $this->get_dao('role');
		/** @var RoleEntity $roles */
		$roles = $dao->getBy('user_id', 2);
		/** @var RoleEntity $role */
		foreach ($roles as $i => $role) {
			$roles[$i] = $role->toArrayForJson();
		}
		return $roles;
	}

	protected function threads() {
		for($i = 0; $i < 50; $i++) {
			/** @var workerThread[] $workers */
			$workers[$i] = new workerThread($i);
			$workers[$i]->start();
		}
	}
}