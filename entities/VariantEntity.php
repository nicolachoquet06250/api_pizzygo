<?php

class VariantEntity extends Entity {
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
	 * @var int $category_id
	 * @not_null
	 * @size(11)
	 * @entity Product_categoryEntity
	 */
	protected $category_id = 0;
	/**
	 * @var float $price
	 * @not_null
	 * @size(11)
	 */
	protected $price = 0.00;
}