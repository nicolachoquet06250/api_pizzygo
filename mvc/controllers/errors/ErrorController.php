<?php

class ErrorController extends Controller {
	protected $code;
	protected $message;

	/**
	 * @inheritdoc
	 */
	protected function index() {
		header('HTTP/1.0 '.$this->code.' '.$this->message);
		return [
			'code' => $this->code,
			'message' => $this->message,
		];
	}

	protected function _404() {
		$this->code(404);
		return $this->index();
	}

	protected function _500() {
		$this->code(500);
		return $this->index();
	}

	/**
	 * @param string $message
	 * @return ErrorController
	 */
	public function message(string $message) {
		$this->message = $message;
		return $this;
	}

	/**
	 * @param int $code
	 * @return ErrorController
	 */
	public function code(int $code) {
		$this->code = $code;
		return $this;
	}

	/**
	 * @return array
	 */
	public function display() {
		return $this->run();
	}
}