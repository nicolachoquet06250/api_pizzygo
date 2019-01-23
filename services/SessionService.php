<?php
session_start();

class SessionService extends Service {

	public function initialize_after_injection() {}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function set(string $key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function get(string $key) {
		return $this->has_key($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	/**
	 * @param string $key
	 */
	public function remove(string $key) {
		if($this->has_key($key)) {
			unset($_SESSION[$key]);
		}
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has_key(string $key) {
		return isset($_SESSION[$key]);
	}

	/**
	 * @param string|null $key
	 * @return array|mixed|null
	 */
	public function toArrayForJson(string $key = null) {
		if(is_null($key)) {
			return $_SESSION;
		}
		$result = $this->get($key);
		return is_array($result) ? $result : [$result];
	}
}