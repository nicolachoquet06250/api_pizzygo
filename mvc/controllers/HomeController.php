<?php

class HomeController extends Controller {

	/**
	 * @inheritdoc
	 * @alias_method home
	 * @throws Exception
	 */
	public function index(): JsonResponse {
		return $this->home();
	}

	/**
	 * @return Response
	 * @throws Exception
	 */
	public function home(): JsonResponse {
		return $this->get_response(
			[
				'controller' => 'home',
				'get' => $this->http_service->get(),
				'session' => $this->http_service->session(),
				'server' => $_SERVER['SERVER_SOFTWARE'],
			]
		);
	}

	/**
	 * @param string $address
	 * @param int $limit
	 * @param bool $coordinates
	 * @return ErrorController|Response
	 * @throws Exception
	 */
	public function addresses(JsonService $json_service): JsonResponse {
		if(!$this->get('address')) {
			return $this->get_error_controller(404)
						->message('Vous devez entrer votre address');
		}
		$url_request = 'https://api-adresse.data.gouv.fr/search/?q='.str_replace(' ', '+', $this->get('address'));
		if($this->get('limit')) {
			$url_request .= '&limit='.$this->get('limit');
		}
		$result = file_get_contents($url_request);
		$result = $json_service->decode($result, true);
		if($this->get('coordinates')) {
			$result = $result['features'][0]['geometry']['coordinates'];
			$result['latitude'] = $result[0];
			$result['longitude'] = $result[1];
			unset($result[0]);
			unset($result[1]);
		}
		return $this->get_response($result);
	}
}