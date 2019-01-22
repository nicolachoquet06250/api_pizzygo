<?php

class Address_typeEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $type
	 * @not_null
	 * @size(255)
	 */
	protected $type = '';
}