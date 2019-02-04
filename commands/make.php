<?php

use mvc_framework\core\queues\classes\QueueReceiver;
use mvc_framework\core\queues\classes\QueueSender;
use mvc_framework\core\queues\traits\Queue;

class make extends cmd {

	public function __construct($args) {
		parent::__construct($args);
		Queue::$NAMESPACE = '\\';
		Queue::$RECEIVERS_PATH = __DIR__.'/../queues/receivers';
		Queue::$SENDERS_PATH = __DIR__.'/../queues/senders';
		Queue::$ELEMENTS_PATH = __DIR__.'/../queues/elements';
		Queue::$QUEUES_PATH = __DIR__.'/../queues/queues';
	}

	/**
	 * @throws Exception
	 */
	public function send_in_queue() {
		/** @var QueueSender $queue_email_sender */
		$queue_email_sender = $this->queues_loader()->get_service_queue_sender()->get_queue('email');
		$queue_email_sender->enqueue(
			[
				'to' => 'nicolachoquet06250@gmail.com',
				'from' => 'nicolachoquet06250@gmail.com',
				'object' => 'Salutation',
				'content' => '<b>Hello</b>'
			]
		);
	}

	/**
	 * @throws Exception
	 */
	public function start_queue() {
		/** @var QueueReceiver $queue_email_receiver */
		$queue_email_receiver = $this->queues_loader()->get_service_queue_receiver()->get_queue('email');
		$queue_email_receiver->run();
	}
}