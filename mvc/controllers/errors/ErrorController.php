<?php

class ErrorController extends Controller {
	protected $code;
	protected $message;

	/**
	 * @inheritdoc
	 * @throws Exception
	 */
	protected function index() {
		header('HTTP/1.0 '.$this->code.' '.$this->message);
		return $this->get_response(
			[
				'code' => $this->code,
				'message' => $this->message,
			]
		);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	protected function _404() {
		$this->code(404);
		return $this->index();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
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
	 * @return string
	 */
	public function display() {
		return $this->run();
	}
}