<?php

class RoleEntity extends Entity {
	/**
	 * @var int $id
	 * @not_null
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $role
	 * @not_null
	 * @size(255)
	 */
	protected $role = '';
	/**
	 * @var int $user_id
	 * @not_null
	 * @size(11)
	 * @entity UserEntity
	 */
	protected $user_id = 0;
}