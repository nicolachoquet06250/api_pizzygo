<?php
		
	class OrderController extends Controller {
		/** @var SessionService $session_service */
		protected $session_service;

		public function __construct($action, $params) {
			parent::__construct($action, $params);
			$this->session_service = $this->get_service('session');
		}

		/**
		 * @return Response
		 * @not_in_doc
		 * @throws Exception
		 */
		protected function index() {
			return $this->get_response([]);
		}


		/**
		 * @title ORDERS FOR A SHOP
		 * @describe Renvoie toutes les commandes d'une boutique donnée.
		 * @throws Exception
		 */
		protected function for_shop(ShopDao $shop_dao, UserDao $user_dao, OrderDao $order_dao) {
			/** @var UserEntity|bool $connected_user */
			$connected_user = $user_dao->getById($this->session_service->get('user')['id']);
			if(is_array($connected_user) && count($connected_user) === 1) {
				$connected_user = $connected_user[0];
			}
			if($connected_user) {
				/** @var ShopEntity|bool $shop */
				$shop = $shop_dao->getBy('user_id', $connected_user->get('id'));
				if(is_array($shop) && count($shop) === 1) {
					$shop = $shop[0];
				}
				if($shop) {
					/** @var OrderEntity|OrderEntity[]|bool $orders */
					$orders = $order_dao->getBy('shop_id', $shop->get('id'));
					if($orders) {
						if(is_array($orders)) {
							foreach ($orders as $i => $order) {
								$orders[$i] = $order->toArrayForJson();
							}
						}
						else $orders = $orders->toArrayForJson();
					}
					else $orders = [];
					return $this->get_response($orders);
				}

				return $this->get_response([]);
			}
			else return $this->get_error_controller(403)->message('You are not login');
		}

		/**
		 * @title ORDERS FOR A CUSTOMER
		 * @describe Renvoie les commandes passées par un client donné dans une boutique donnée.
		 * @param int $customer
		 * @param int $shop
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		protected function for_customer(OrderDao $order_dao) {
			if(!$this->get('customer') && $this->get('shop')) {
				return $this->get_error_controller(404)->message('The customer_id and shop_id are required');
			}
			$orders = $order_dao->getByUser_idAndShop_id((int)$this->get('customer'), (int)$this->get('shop'));
			if(!$orders) {
				$orders = [];
			}
			return $this->get_response($orders);
		}

		/**
		 * @throws Exception
		 */
		protected function for_user(UserDao $user_dao, OrderDao $order_dao) {
			if(!$this->session_service->has_key('user')) {
				return $this->get_error_controller(503)
							->message('Vous n\'êtes pas connécté !!');
			}
			$id = (int)$this->session_service->get('user')['id'];
			/** @var UserEntity $user */
			$user = $user_dao->getById($id)[0];
			$roles = [];
			foreach ($user->get_roles() as $i => $role) {
				$roles[$i] = $role['role'];
			}
			if(in_array('role_vendor', $roles)) {
				$orders = $order_dao->getBy('user_id', $id);
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