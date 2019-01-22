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
	 * @entity UserEntity
	 * @size(11)
	 */
	protected $user_id = 0;
}