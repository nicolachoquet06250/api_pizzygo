<?php

class Threadable extends Base {
	protected $thread_pid;
	protected $execution_time;
	public function __construct() {
		$this->init_into_construct();
	}

	protected function init_into_construct() {

	}

	/**
	 * @throws Exception
	 */
	protected function before_run() {
		$this->thread_pid = pcntl_fork();
		if($this->thread_pid === -1)
			throw new Exception('Une erreur est survenu lors de la création de la thread !!');
	}

	protected function run($task_id = null) {
		$execution_time = rand(5, 10);
		$this->execution_time = $execution_time;
		sleep($execution_time);
		echo "Completed task: ${task_id}. Took ${execution_time} seconds.\n";
	}

	public function get_execution_time() {
		return $this->execution_time;
	}

	protected function after_run() {
//		while(pcntl_waitpid(0, $status) != -1);
//		return true;
	}

	/**
	 * @throws Exception
	 */
	public function start() {
		$this->before_run();
		$params = '';
		$i = 0;
		foreach (func_get_args() as $argument) {
			if(is_string($argument)) {
				$params .= '"'.$argument.'"';
			}
			elseif (is_numeric($argument)) {
				$params .= $argument;
			}
			elseif (is_object($argument)) {
				$params .= '$arguments['.$i.']';
			}
			$i++;
		}
		$result = $this->run((isset(func_get_args()[0]) ? func_get_args()[0] : null));
		$this->after_run();
		return $result ? $result : false;
	}
}