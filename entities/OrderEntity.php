<?php

class OrderEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $comment
	 * @not_null
	 * @text
	 */
	protected $comment = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @entity user
	 * @size(11)
	 */
	protected $user_id = 0;
	/**
	 * @var int $address
	 * @not_null
	 * @entity address
	 * @size(11)
	 */
	protected $address_id = 0;
	/**
	 * @var int $status
	 * @not_null
	 * @entity order_status
	 * @size(11)
	 */
	protected $status_id = 0;
	/**
	 * @var int $end_status_id
	 * @not_null
	 * @entity end_status
	 * @size(11)
	 */
	protected $end_status_id = 0;
	/**
	 * @var int $shop_id
	 * @not_null
	 * @entity shop
	 * @size(11)
	 */
	protected $shop_id = 0;

	/**
	 * @param bool $recursive
	 * @return array
	 * @throws Exception
	 */
	public function toArrayForJson($recursive = true) {
		$array = parent::toArrayForJson();

		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		/** @var UserEntity[]|bool $user */
		$user = $user_dao->getById($array['user_id']);
		$user = $user ? $user[0]->toArrayForJson() : $array['user_id'];
		$array['user_id'] = $user;

		/** @var AddressDao $address_dao */
		$address_dao = $this->get_dao('address');
		/** @var UserEntity[]|bool $address */
		$address = $address_dao->getById($array['address_id']);
		$address = $address ? $address[0]->toArrayForJson() : $array['address_id'];
		$array['address_id'] = $address;

		/** @var Order_statusDao $order_status_dao */
		$order_status_dao = $this->get_dao('order_status');
		/** @var Order_statusEntity[]|bool $order_status */
		$order_status = $order_status_dao->getById($array['status_id']);
		$order_status = $order_status ? $order_status[0]->toArrayForJson() : $array['status_id'];
		$array['status_id'] = $order_status;

		/** @var End_statusDao $end_status_dao */
		$end_status_dao = $this->get_dao('end_status');
		/** @var End_statusEntity[]|bool $end_status */
		$end_status = $end_status_dao->getById($array['end_status_id']);
		$end_status = $end_status ? $end_status[0]->toArrayForJson() : $array['end_status_id'];
		$array['end_status_id'] = $end_status;


		/** @var ShopDao $shop_dao */
		$shop_dao = $this->get_dao('shop');
		/** @var ShopEntity[]|bool $shop */
		$shop = $shop_dao->getById($array['shop_id']);
		$shop = $shop ? $shop[0]->toArrayForJson() : $array['shop_id'];
		$array['shop_id'] = $shop;

		return $array;
	}
}