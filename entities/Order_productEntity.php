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
	 * @entity ProductEntity
	 * @size(11)
	 */
	protected $product_id = 0;
	/**
	 * @var int $variant_id
	 * @not_null
	 * @entity VariantEntity
	 * @size(11)
	 */
	protected $variant_id = 0;
	/**
	 * @var int $order_id
	 * @not_null
	 * @entity OrderEntity
	 * @size(11)
	 */
	protected $order_id = 0;
}