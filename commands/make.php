<?php

use PHPMailer\PHPMailer\PHPMailer;

class make extends cmd {
	/**
	 * @throws Exception
	 */
	protected function send_sms() {
		/** @var EmailService $email_service */
		$email_service = $this->get_service('email');
	}

	/**
	 * @throws Exception
	 */
	protected function set_email() {
		/** @var EmailService $email_service */
		$email_service = $this->get_service('email');
	}
}