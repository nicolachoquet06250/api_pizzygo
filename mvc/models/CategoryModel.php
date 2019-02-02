<?php

	class CategoryModel extends BaseModel {

		/**
		 * @param Product_categoryDao $dao
		 * @param string $name
		 * @param int $shop_id
		 * @return bool|Product_categoryEntity
		 * @throws Exception
		 */
		public function add(Product_categoryDao $dao, string $name, int $shop_id) {
			return $dao->create(function (Base $object) use ($name, $shop_id) {
				/** @var Product_categoryEntity $category */
				$category = $object->get_entity('product_category');
				$category->set('name', $name);
				$category->set('shop_id', $shop_id);
				return $category;
			});
		}

		/**
		 * @param Product_categoryDao $dao
		 * @param int $id
		 * @return bool
		 * @throws Exception
		 */
		public function delete(Product_categoryDao $dao, int $id): bool {
			/** @var Product_categoryEntity $category */
			$category = $dao->getById($id);
			return $category->delete();
		}
	}