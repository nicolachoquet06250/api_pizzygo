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
//								echo '<pre>';
//								var_dump($order);
//								echo '</pre>';
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
	}