<?php


class CookieService extends Service {

	public function initialize_after_injection() {

	}

	public function set($key, $value, $domain = '') {
		setcookie($key, $value, 0, '', $domain);
	}

	public function get($key) {
		return $this->has_key($key) ? $_COOKIE[$key] : null;
	}

	public function remove($key) {
		if($this->has_key($key)) {
			unset($_SESSION[$key]);
		}
	}

	public function has_key($key) {
		return isset($_COOKIE[$key]);
	}
}