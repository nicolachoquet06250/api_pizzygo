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
		 * @entity shop
		 */
		protected $shop_id = 0;
		/**
		 * @var int $user_id
		 * @not_null
		 * @entity user
		 */
		protected $user_id = 0;

	}
