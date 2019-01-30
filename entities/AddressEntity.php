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

	/**
	 * @return array
	 * @throws Exception
	 */
	public function toArrayForJson() {
		$array = parent::toArrayForJson();

		/** @var Address_typeDao $address_type_dao */
		$address_type_dao = $this->get_dao('address_type');
		/** @var Address_typeEntity[]|bool $user */
		$address_type = $address_type_dao->getById($array['type_id']);
		$address_type = $address_type ? $address_type[0]->toArrayForJson() : $array['type_id'];
		$array['type_id'] = $address_type;

		/** @var UserDao $user_dao */
		$user_dao = $this->get_dao('user');
		/** @var UserEntity[]|bool $user */
		$user = $user_dao->getById($array['user_id']);
		$user = $user ? $user[0]->toArrayForJson() : $array['user_id'];
		$array['user_id'] = $user;

		return $array;
	}
}