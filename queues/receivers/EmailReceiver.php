<?php

use mvc_framework\core\queues\classes\QueueReceiver;

class EmailReceiver extends QueueReceiver {
	public static function run_callback(EmailReceiver $queue, EmailElement $current_element) {
		$current_element->execute();
	}

	/**
	 * @inheritdoc
	 */
	public function run($callback = null) {
		parent::run($this->get_callback($callback));
	}
}