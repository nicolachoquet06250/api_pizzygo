<?php
	
	class CredentialsEntity extends Entity {
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
		/**
		 * @var string $login
		 * @not_null
		 * @size(255)
		 */
		protected $login = '';
		/**
		 * @var string $password
		 * @not_null
		 * @size(255)
		 */
		protected $password = '';

	}
