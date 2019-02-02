<?php
		
	class ProductController extends Controller {

		/** @var ProductDao $product_dao */
		public $product_dao;

		/**
		 * @param int $category_id
		 * @alias_method for_category
		 * @return ErrorController|JsonResponse|Response
		 * @throws Exception
		 */
		public function index(Product_categoryDao $product_categoryDao = null): JsonResponse {
			if($this->get('category_id')) {
				return $this->for_category();
			}
			elseif ($this->get('shop_id')) {
				return $this->for_shop($product_categoryDao);
			}
			return $this->get_error_controller(503)->message('parameter category_id is required');
		}

		/**
		 * @not_in_doc
		 * @return ErrorController|JsonResponse|Response
		 * @throws Exception
		 */
		public function for_category(): JsonResponse {
			if(!$this->get('category_id')) {
				return $this->get_error_controller(503)->message('parameter category_id is required');
			}
			$products = $this->product_dao->getBy('category_id', (int)$this->get('category_id'));
			if(!$products) {
				$products = [];
			}
			return $this->get_response($products);
		}

		/**
		 * @param int $shop_id
		 * @return ErrorController|JsonResponse|Response
		 * @throws Exception
		 */
		public function for_shop(Product_categoryDao $categoryDao): JsonResponse {
			if(!$this->get('shop_id')) {
				return $this->get_error_controller(503)->message('parameter shop_id is required');
			}
			/** @var Product_categoryEntity[]|bool $categories */
			$categories = $categoryDao->getBy('shop_id', $this->get('shop_id'));
			if(!$categories) {
				$categories = [];
			}
			$products = [];
			foreach ($categories as $category) {
				foreach ($category->get_products() as $product) {
					$products[] = $product;
				}
			}
			return $this->get_response($products);
		}
	}