<?php

class install extends cmd {
	private $git_dependencies = [
		[
			'https://github.com/PHPMailer/PHPMailer.git',
			'PHPMailer'
		]
	];
	/**
	 * @throws Exception
	 */
	protected function test_db() {
		if(!is_null($this->get_arg('prefix'))) {
			$prefix = $this->get_arg('prefix');
			$this->get_conf('mysql')->set('table-prefix', $prefix, false);
		}
		/** @var InstallService $install_service */
		$install_service = $this->get_service('install');
		$install_service->test_databases();
	}

	/**
	 * @throws Exception
	 */
	protected function db() {
		/** @var InstallService $install_service */
		$install_service = $this->get_service('install');
		$install_service->databases();
	}

	protected function dependencies() {
		foreach ($this->git_dependencies as $git_dependency) {
			exec('cd '.__DIR__.'/../extendable_classes && git clone '.$git_dependency[0].' '.$git_dependency[1]);
		}
	}
}