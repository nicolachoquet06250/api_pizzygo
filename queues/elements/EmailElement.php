<?php

use mvc_framework\core\queues\classes\QueueElement;

class EmailElement extends QueueElement {
	protected $to, $from, $content, $object;
	private $loader;

	public function __construct($element) {
		parent::__construct($element);
		$this->loader = new Base();
		$this->to = $this->get('to');
		$this->from = $this->get('from');
		$this->content = $this->get('content');
		$this->object = $this->get('object');
	}

	/**
	 * @throws Exception
	 */public function execute() {
		/** @var EmailService $email_service */
		$email_service = $this->loader->get_service('email');
		/** @var OsService $os_service */
		$os_service = $this->loader->get_service('os');

		$sended = $email_service->charset('utf-8')
					  ->html()->object($this->object)
					  ->set_from_name('Nicolas Choquet')
					  ->to(
					  	$this->to,
						$email_service->get_mailer()->FromName
					  )->message($this->content)->send();

		echo $sended ? "Message sent! ".$os_service->get_chariot_return() : "Message not sent ".$os_service->get_chariot_return();
	}
}
