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
		if (function_exists('pcntl_fork')) {

			for ($x = 1; $x < 5; $x++) {
				switch ($pid = pcntl_fork()) {
					case -1:
						// @fail
						die('Fork failed');
						break;

					case 0:
						// @child: Include() misbehaving code here
						print "FORK: Child #{$x} preparing to nuke...\n";
						generate_fatal_error(); // Undefined function
						break;

					default:
						// @parent
						print "FORK: Parent, letting the child run amok...\n";
						pcntl_waitpid($pid, $status);
						break;
				}
			}
			print "Done! :^)\n\n";
		}
	}

	/**
	 * @throws Exception
	 */
	protected function test_user_dao() {
		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		$user = $user_dao->getId_Name_Surname_Email_DescriptionByEmailAndPassword($this->get_arg('email'), sha1(sha1($this->get_arg('password'))));
		if(is_array($user)) {
			foreach ($user as $i => $_user)
				$user[$i] = $_user->toArrayForJson();
			return $user;
		}
		else return $user->toArrayForJson();
	}
}