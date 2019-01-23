<?php

class DemoController extends Controller {

	/**
	 * @inheritdoc
	 */
	protected function index() {

	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function _404() {
		return $this->get_error_controller(404)->message('La page que vous cherchez n\'existe pas !')->display();
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function _500() {
		return $this->get_error_controller(500)->message('Erreur de serveur !')->display();
	}
}