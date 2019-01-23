<?php

class install extends cmd {
	/**
	 * @throws Exception
	 */
	protected function db() {
		if(!is_null($this->get_arg('prefix'))) {
			$prefix = $this->get_arg('prefix');
			$this->get_conf('mysql')->set('table-prefix', $prefix, false);
		}
		/** @var InstallService $install_service */
		$install_service = $this->get_service('install');
		$install_service->test_databases();
	}
}