<?php

class AddressEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $address
	 * @not_null
	 * @size(255)
	 */
	protected $address = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @size(11)
	 * @entity UserEntity
	 */
	protected $user_id = 0;
	/**
	 * @var int $type_id
	 * @not_null
	 * @size(11)
	 * @entity Address_typeEntity
	 */
	protected $type_id = 0;
}