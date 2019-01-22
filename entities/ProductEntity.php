<?php

class ProductEntity extends Entity {
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
	 * @entity Product_categoryEntity
	 */
	protected $category_id = 0;
	/**
	 * @var string $comment
	 * @not_null
	 * @text
	 */
	protected $comment = '';
	/**
	 * @var string $image
	 * @not_null
	 * @size(255)
	 */
	protected $image = '';
	/**
	 * @var string $image_alt
	 * @not_null
	 * @size(255)
	 */
	protected $image_alt = '';
	/**
	 * @var bool $background_dark
	 * @not_null
	 */
	protected $background_dark = true;
}