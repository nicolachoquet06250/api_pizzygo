<?php
session_start();

class HttpService extends Service {
	protected $get;
	protected $post;
	protected $files;
	protected $response_header;
	protected $session;

	public function initialize_after_injection() {
		$this->get = $_GET;
		$this->post = $_POST;
		$this->files = $_FILES;
		$this->response_header = isset($http_response_header) ? $http_response_header : null;
		$this->session = isset($_SESSION) ? $_SESSION : null;
	}

	public function get($key = null) {
		if(is_null($key)) {
			return $this->get;
		}
		return isset($this->get[$key]) ? $this->get[$key] : null;
	}

	public function post($key) {
		if(is_null($key)) {
			return $this->post;
		}
		return isset($this->post[$key]) ? $this->post[$key] : null;
	}

	public function files($key) {
		if(is_null($key)) {
			return $this->files;
		}
		return isset($this->files[$key]) ? $this->files[$key] : null;
	}

	public function response_header($key, $value = null) {
		if(!is_null($value)) $this->response_header[$key] = $value;
		return isset($this->response_header[$key]) ? $this->response_header[$key] : null;
	}

	public function session($key = null, $value = null) {
		if(!is_null($value)) $this->response_header[$key] = $value;
		if(is_null($key)) {
			return $this->session;
		}
		return isset($this->response_header[$key]) ? $this->response_header[$key] : null;
	}
}