<?php
		
	class OrderController extends Controller {

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
		protected function for_shop() {
			/** @var SessionService $session_service */
			$session_service = $this->get_service('session');
			/**
			 * @var ShopDao $shop_dao
			 * @var UserDao $user_dao
			 */
			list($shop_dao, $user_dao) = [$this->get_dao('shop'), $this->get_dao('user')];
			/** @var UserEntity|bool $connected_user */
			$connected_user = $user_dao->getById($session_service->get('user')['id']);
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
					/** @var OrderDao $order_dao */
					$order_dao = $this->get_dao('order');
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
		protected function for_customer() {
			if(!$this->get('customer') && $this->get('shop')) {
				return $this->get_error_controller(404)->message('The customer_id and shop_id are required');
			}
			/** @var OrderDao $order_dao */
			$order_dao = $this->get_dao('order');
			$orders = $order_dao->getByUser_idAndShop_id((int)$this->get('customer'), (int)$this->get('shop'));
			return $this->get_response($orders);
		}
	}