<?php

require_once __DIR__.'/../autoload.php';

class Main extends Base {
	/**
	 * @throws Exception
	 */
	public function run() {
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
		var_dump($user->toArrayForJson());
	}
}

(new Main())->run();