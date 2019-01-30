<?php
		
	class ShopController extends Controller {

		/**
		 * @param int $id
		 * @return Response
		 * @throws Exception
		 * @alias getAll
		 */
		protected function index() {
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
		protected function getAll() {
			/** @var ShopDao $shop_dao */
			$shop_dao = $this->get_dao('shop');
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			if(!$session_service->has_key('user')) {
				return $this->get_error_controller(403)->message('You are not logged');
			}
			/** @var ShopEntity[]|bool $shops */
			$shops = $shop_dao->getBy('user_id', $session_service->get('user')['id']);
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
		protected function getById() {
			/** @var ShopDao $shop_dao */
			$shop_dao = $this->get_dao('shop');
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			if(!$session_service->has_key('user')) {
				return $this->get_error_controller(403)->message('You are not logged');
			}
			/** @var ShopEntity[]|bool $shops */
			$shop = $shop_dao->getById($this->get('id'));
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
		protected function addShop() {
			if(!$this->post('user_id') || !$this->post('name') || !$this->post('description')) {
				return $this->get_error_controller(403)->message('parameters user_id, name and description are required');
			}
			/** @var ShopDao $shop_dao */
			$shop_dao = $this->get_dao('shop');
			$shop = $shop_dao->create(function (Base $object) {
				/** @var ShopEntity $shop */
				$shop = $object->get_entity('shop');
				$shop->initFromArray(
					[
						'user_id' => (int)$this->post('user_id'),
						'name' => $this->post('name'),
						'description' => $this->post('description'),
					]
				);
				return $shop;
			});
			return $this->get_response($shop);
		}
	}