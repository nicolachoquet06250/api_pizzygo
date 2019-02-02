<?php

class EmailEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $email
	 * @not_null
	 * @size(255)
	 */
	protected $email = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @size(11)
	 * @entity user
	 */
	protected $user_id = 0;
}