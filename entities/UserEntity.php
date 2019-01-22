<?php

class UserEntity extends Entity {
	/**
	 * @var int $id
	 * @primary
	 */
	protected $id = 0;
	/**
	 * @var string $name
	 * @size(255)
	 */
	protected $name = '';
	/**
	 * @var string $surname
	 * @size(255)
	 */
	protected $surname = '';
	/**
	 * @var string $address
	 * @text
	 */
	protected $address = '';
	/**
	 * @var string $email
	 * @text
	 */
	protected $email = '';
	/**
	 * @var string $phone
	 * @size(10)
	 */
	protected $phone = '';
	/**
	 * @var string $password
	 * @size(255)
	 */
	protected $password = '';
	/**
	 * @var string $description
	 * @text
	 */
	protected $description = '';
	/**
	 * @var string $profil_image
	 * @size(255)
	 */
	protected $profil_img = '';
	/**
	 * @var bool $premium
	 */
	protected $premium = false;
	/**
	 * @var bool $active
	 */
	protected $active = false;
	/**
	 * @var string $activate_token
	 * @size(255)
	 */
	protected $activate_token = '';
}