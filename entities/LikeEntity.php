<?php
	
	class LikeEntity extends Entity {
		/**
		 * @var int $id
		 * @not_null
		 * @primary
		 */
		protected $id = 0;
		/**
		 * @var int $shop_id
		 * @not_null
		 * @entity ShopEntity
		 */
		protected $shop_id = 0;
		/**
		 * @var int $user_id
		 * @not_null
		 * @entity UserEntity
		 */
		protected $user_id = 0;

	}
