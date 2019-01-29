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

	protected function forks() {
		if (function_exists('pcntl_fork')) {
			function execute_task($task_id) {
				echo "Starting task: ${task_id}\n";
				// Simulate doing actual work with sleep().
				$execution_time = rand(5, 10);
				sleep($execution_time);
				echo "Completed task: ${task_id}. Took ${execution_time} seconds.\n";
			}

			$tasks = [
				"fetch_remote_data",
				"post_async_updates",
				"clear_caches",
				"notify_admin",
			];
			foreach ($tasks as $task) {
				$pid = pcntl_fork();
				if ($pid == -1) {
					exit("Error forking...\n");
				}
				else if ($pid == 0) {
					execute_task($task);
					exit();
				}
			}
			while(pcntl_waitpid(0, $status) != -1);
			echo "Do stuff after all parallel execution is complete.\n";
		}
	}

	/**
	 * @throws Exception
	 */
	protected function threads() {
		($this->get_thread('main'))->start();
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