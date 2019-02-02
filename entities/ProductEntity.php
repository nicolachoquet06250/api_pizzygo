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
	 * @entity product_category
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

	/**
	 * @return bool|Entity
	 * @throws Exception
	 */
	public function get_category() {
		$category_dao = $this->get_dao('product_category');
		return $category_dao->getById($this->get('category_id'));
	}

	/**
	 * @param bool $recursive
	 * @return array
	 * @throws Exception
	 */
	public function toArrayForJson($recursive = true) {
		$array = parent::toArrayForJson($recursive);
		$array['category_id'] = $this->get_category()->toArrayForJson();
		return $array;
	}
}