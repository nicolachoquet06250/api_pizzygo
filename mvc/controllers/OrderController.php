<?php
		
	class OrderController extends Controller {
		/** @var SessionService $session_service */
		public $session_service;

		/** @var OrderDao $order_dao */
		public $order_dao;

		/** @var UserDao $user_dao */
		public $user_dao;

		/**
		 * @return Response
		 * @not_in_doc
		 * @throws Exception
		 */
		public function index(): JsonResponse {
			return $this->get_response([]);
		}

		/**
		 * @title ORDERS FOR A SHOP
		 * @describe Renvoie toutes les commandes d'une boutique donnée.*
		 * @param int $shop_id
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function for_shop(ShopDao $shop_dao): JsonResponse {
			/** @var UserEntity|bool $connected_user */
			$connected_user = $this->user_dao->getById($this->session_service->get('user')['id']);
			if(is_array($connected_user) && count($connected_user) === 1) {
				$connected_user = $connected_user[0];
			}
			if(!$connected_user) {
				return $this->get_error_controller(403)->message('You are not logged');
			}
			else {
				/** @var ShopEntity|bool $shop */
				$shop = $this->get('shop_id') ? $shop_dao->getById($this->get('shop_id')) : $shop_dao->getBy('user_id', $connected_user->get('id'));
				if ($shop) {
					$orders = [];
					if(is_array($shop)) {
						/** @var ShopEntity $_shop */
						foreach ($shop as $_shop) {
							$_orders = $this->order_dao->getBy('shop_id', $_shop->get('id'));
							/** @var OrderEntity $order */
							foreach ($_orders as $order) {
								$orders[] = $order->toArrayForJson();
							}
						}
						return $this->get_response($orders);
					}
					else {
						/** @var OrderEntity|OrderEntity[]|bool $orders */
						$orders = $this->order_dao->getBy('shop_id', $shop->get('id'));
						if ($orders) {
							if (is_array($orders)) {
								foreach ($orders as $i => $order) {
									$orders[$i] = $order->toArrayForJson();
								}
							} else $orders = $orders->toArrayForJson();
						}
					}
					return $this->get_response($orders);
				}
				return $this->get_response([]);
			}
		}

		/**
		 * @title ORDERS FOR A CUSTOMER
		 * @describe Renvoie les commandes passées par un client donné dans une boutique donnée.
		 * @param int $customer
		 * @param int $shop
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function for_customer(): JsonResponse {
			if(!$this->get('customer') && $this->get('shop')) {
				return $this->get_error_controller(404)->message('The customer_id and shop_id are required');
			}
			$orders = $this->order_dao->getByUser_idAndShop_id((int)$this->get('customer'), (int)$this->get('shop'));
			if(!$orders) {
				$orders = [];
			}
			return $this->get_response($orders);
		}

		/**
		 * @title ORDERS FOR A VENDOR
		 * @describe Renvoie toutes les commandes pour le vendeur connecté.
		 * @return ErrorController|JsonResponse|Response
		 * @throws Exception
		 */
		public function for_vendor() {
			if(!$this->session_service->has_key('user')) {
				return $this->get_error_controller(503)
							->message('Vous n\'êtes pas connécté !!');
			}
			$id = (int)$this->session_service->get('user')['id'];
			/** @var UserEntity $user */
			$user = $this->user_dao->getById($id)[0];
			$roles = [];
			foreach ($user->get_roles() as $i => $role) {
				$roles[$i] = $role['role'];
			}
			if(in_array('role_vendor', $roles)) {
				$orders = $this->order_dao->getBy('user_id', $id);
				/**
				 * @var int $i
				 * @var OrderEntity $order
				 */
				foreach ($orders as $i => $order) {
					$orders[$i] = $order->toArrayForJson();
				}
				return $this->get_response($orders);
			}
			return $this->get_error_controller(503)
						->message('Vous n\'avez pas les droits nécéssaires pour accéder à cet url !!');
		}
	}