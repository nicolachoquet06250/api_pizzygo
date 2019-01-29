<?php

class RoleEntity extends Entity {
	const USER = 'role_user';
	const ADMIN = 'role_admin';
	const VENDOR = 'role_vendor';
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
	 * @JsonExclude
	 */
	protected $user_id = 0;
}