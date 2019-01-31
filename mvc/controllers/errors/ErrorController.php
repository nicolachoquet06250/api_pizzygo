<?php

class ErrorController extends Controller {
	protected $code;
	protected $message;

	/**
	 * @inheritdoc
	 * @throws Exception
	 */
	protected function index() {
		$this->write_header();
		var_dump($this->get_response(
			[
				'code' => $this->code,
				'message' => $this->message,
			]
		));
		return $this->get_response(
			[
				'code' => $this->code,
				'message' => $this->message,
			]
		);
	}

	protected function write_header() {
		header('HTTP/1.0 '.$this->code.' '.$this->message);
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	protected function _201() {
		$this->code(201);
		return $this->index();
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
	protected function _400() {
		$this->code(400);
		return $this->index();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	protected function _403() {
		$this->code(403);
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
	 * @return Response
	 * @throws Exception
	 */
	protected function _503() {
		$this->code(503);
		return $this->index();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	protected function _501() {
		$this->code(501);
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
//		$this->write_header();
		return $this->run();
	}
}