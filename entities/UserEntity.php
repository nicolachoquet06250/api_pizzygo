<?php

class UserEntity extends Entity {
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
	 * @var string $surname
	 * @not_null
	 * @size(255)
	 */
	protected $surname = '';
	/**
	 * @var string $address
	 * @not_null
	 * @text
	 */
	protected $address = '';
	/**
	 * @var string $email
	 * @not_null
	 * @text
	 */
	protected $email = '';
	/**
	 * @var string $phone
	 * @not_null
	 * @size(10)
	 */
	protected $phone = '';
	/**
	 * @var string $password
	 * @not_null
	 * @size(255)
	 * @JsonExclude
	 */
	protected $password = '';
	/**
	 * @var string $description
	 * @not_null
	 * @text
	 */
	protected $description = '';
	/**
	 * @var string $profil_image
	 * @not_null
	 * @size(255)
	 */
	protected $profil_img = '';
	/**
	 * @var bool $premium
	 * @not_null
	 */
	protected $premium = false;
	/**
	 * @var bool $active
	 * @not_null
	 */
	protected $active = false;
	/**
	 * @var string $activate_token
	 * @not_null
	 * @size(255)
	 */
	protected $activate_token = '';

	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * @return bool
	 */
	public function isPremium() {
		return $this->premium;
	}
}