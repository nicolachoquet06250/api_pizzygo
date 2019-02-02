<?php

class Order_productEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var int $product_id
	 * @not_null
	 * @entity product
	 * @size(11)
	 */
	protected $product_id = 0;
	/**
	 * @var int $variant_id
	 * @not_null
	 * @entity variant
	 * @size(11)
	 */
	protected $variant_id = 0;
	/**
	 * @var int $order_id
	 * @not_null
	 * @entity order
	 * @size(11)
	 */
	protected $order_id = 0;
}