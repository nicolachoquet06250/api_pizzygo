<?php

class Response extends Base {
	protected $element;
	protected $header_type = self::JSON;
	protected $parsed_element;
	const HTML = 'text/html';
	const TEXT = 'plain/text';
	const JSON = 'application/json';

	/**
	 * @param $element
	 * @param $type
	 * @return Response
	 * @throws Exception
	 */
	public static function create($element, $type = self::JSON) {
		$response_class = ucfirst(explode('/', $type)[1]).'Response';
		if(is_file(realpath(__DIR__.'/../responses/'.$response_class.'.php'))) {
			require_once realpath(__DIR__.'/../responses/'.$response_class.'.php');
			/** @var Response $response */
			$response = new $response_class($element);
			return $response;
		}
		throw new Exception('Nous n\'avons pu créer de réponse !!');
	}

	public function __construct($element) {
		$this->element($element);
		$this->header();
	}

	protected function element($element) {
		$this->element = $element;
	}

	protected function header() {
		header('Content-Type: '.$this->header_type.';charset=UTF-8');
	}

	protected function parse_element() {
		$element = null;
		if(is_array($this->element)) {
			$element = [];
			foreach ($this->element as $key => $elem) {
				if(is_object($elem)) {
					/** @var Base $elem */
					$element[$key] = $elem->toArrayForJson();
				}
				if (is_string($elem) || is_numeric($elem) || is_array($elem) || is_bool($elem)) {
					$element[$key] = $elem;
				}
			}
		}
		elseif (is_string($this->element)) {
			$element = [
				'message' => $this->element
			];
		}
		elseif (is_numeric($this->element)) {
			$element = [
				'code' => $this->element
			];
		}
		elseif (is_object($this->element)) {
			/** @var Base $_element */
			$_element = $this->element;
			$element = $_element->toArrayForJson();
		}
		$this->parsed_element = json_encode($element);
	}

	public function display() {
		$this->parse_element();
		return $this->parsed_element;
	}
}