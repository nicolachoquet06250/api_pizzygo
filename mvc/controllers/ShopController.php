<?php
		
	class ShopController extends Controller {
		/** @var SessionService $session_service */
		public $session_service;

		/** @var ShopDao $shop_dao */
		public $shop_dao;

		/**
		 * @param int $id
		 * @return Response
		 * @throws Exception
		 * @alias getAll
		 */
		public function index() {
			if(!$this->get('id')) {
				return $this->getAll();
			}
			return $this->getById();
		}

		/**
		 * @not_in_doc
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function getAll() {
			if(!$this->session_service->has_key('user')) {
				return $this->get_error_controller(403)->message('You are not logged');
			}
			/** @var ShopEntity[]|bool $shops */
			$shops = $this->shop_dao->getBy('user_id', $this->session_service->get('user')['id']);
			if(!$shops) {
				return $this->get_error_controller(404)->message('You have not shops');
			}
			return $this->get_response($shops);
		}

		/**
		 * @not_in_doc
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function getById() {
			if(!$this->session_service->has_key('user')) {
				return $this->get_error_controller(403)->message('You are not logged');
			}
			/** @var ShopEntity[]|bool $shops */
			$shop = $this->shop_dao->getById($this->get('id'));
			if(!$shop) {
				return $this->get_error_controller(404)->message('Shop with id '.$this->get('id').' not found');
			}
			return $this->get_response($shop[0]);
		}

		/**
		 * @title ADD SHOP
		 * @describe C'est une petite description pour voir
		 * ce que ça va donner en HTML
		 * @param int $user_id
		 * @param string $name
		 * @param string $description
		 * @http_verb post
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function addShop(ShopEntity $shop_entity) {
			if(!$this->post('user_id') || !$this->post('name') || !$this->post('description')) {
				return $this->get_error_controller(403)->message('parameters user_id, name and description are required');
			}
			$shop = $this->shop_dao->create(function () use (&$shop_entity) {
				$shop_entity->initFromArray(
					[
						'user_id' => (int)$this->post('user_id'),
						'name' => $this->post('name'),
						'description' => $this->post('description'),
					]
				);
				return $shop_entity;
			});
			return $this->get_response($shop);
		}
	}