<?php

class MainThread extends Threadable {
	/**
	 * @param null $task_id
	 * @throws Exception
	 */
	protected function run($task_id = null) {
		($this->get_thread('sleep'))->start(10);
		($this->get_thread('sleep'))->start(5);
		($this->get_thread('sleep'))->start(2);
	}

	protected function after_run() {
		echo "J'ai fini !!\n";
	}
}