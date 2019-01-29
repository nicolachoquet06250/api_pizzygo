<?php

class SleepThread extends Threadable {
	protected function run($task_id = null) {
		sleep((int)$task_id);
		echo "J'ai attendu $task_id s.\n";
	}
}