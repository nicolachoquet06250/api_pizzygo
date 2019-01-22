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
	 * @entity UserEntity
	 * @size(11)
	 */
	protected $user_id = 0;
	/**
	 * @var int $address
	 * @not_null
	 * @entity AddressEntity
	 * @size(11)
	 */
	protected $address_id = 0;
	/**
	 * @var int $status
	 * @not_null
	 * @entity Order_statusEntity
	 * @size(11)
	 */
	protected $status_id = 0;
	/**
	 * @var int $end_status
	 * @not_null
	 * @entity End_statusEntity
	 * @size(11)
	 */
	protected $end_status = 0;
}