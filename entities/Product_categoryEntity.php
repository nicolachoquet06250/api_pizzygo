<?php

class Product_categoryEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $name
	 * @not_null
	 * @size(255)
	 */
	protected $name = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @entity shop
	 * @size(11)
	 */
	protected $shop_id = 0;

	/**
	 * @return bool|ShopEntity|null
	 * @throws Exception
	 */
	public function get_shop() {
		/** @var ShopDao $shop_dao */
		$shop_dao = $this->get_dao('shop');
		/** @var ShopEntity|bool $shop */
		$shop = $shop_dao->getById($this->get('shop_id'));
		if(!$shop) {
			return $this->get('shop_id');
		}
		return $shop;
	}

	/**
	 * @return array|bool
	 * @throws Exception
	 */
	public function get_products() {
		/** @var ProductDao $product_dao */
		$product_dao = $this->get_dao('product');
		$products = $product_dao->getBy('category_id', $this->get('id'));
		if(!$products) {
			$products = [];
		}
		return $products;
	}

	/**
	 * @param bool $recursive
	 * @return array|void
	 * @throws Exception
	 */
	public function toArrayForJson($recursive = true) {
		$array = parent::toArrayForJson($recursive);
		$array['shop_id'] = $this->get_shop()->toArrayForJson();
		return $array;
	}
}