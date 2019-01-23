<?php

class OsService extends Service {
	protected $os;
	public function initialize_after_injection() {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			echo 'This is a server using Windows!';
		} else {
			echo 'This is a server not using Windows!';
		}
		$this->os = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'windows' : 'unix';
	}

	public function IAmOnWindowsSystem() {
		return $this->os === 'windows';
	}

	public function IAmOnUnixSystem() {
		return $this->os === 'unix';
	}
}