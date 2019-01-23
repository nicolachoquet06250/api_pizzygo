<?php

class OsService extends Service {
	protected $os;
	public function initialize_after_injection() {
		$this->os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'windows' : 'unix';
	}

	public function IAmOnWindowsSystem() {
		return $this->os === 'windows';
	}

	public function IAmOnUnixSystem() {
		return $this->os === 'unix';
	}
}