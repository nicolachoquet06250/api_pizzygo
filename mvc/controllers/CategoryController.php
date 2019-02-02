<?php
		
	class CategoryController extends Controller {

		/** @var CategoryModel $model */
		public $model;

		/** @var Product_categoryDao $product_category_dao */
		public $product_category_dao;

		/**
		 * @param int $id
		 * @param int $shop
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function index(): JsonResponse {
			if($this->get('id')) {
				return $this->get_by_id();
			}
			elseif ($this->get('shop')) {
				return $this->get_by_shop();
			}
			return $this->get_all();
		}

		/**
		 * @not_in_doc
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function get_all(): JsonResponse {
			/** @var Product_categoryEntity[]|bool $product_categories */
			$product_categories = $this->product_category_dao->getAll();
			if(!$product_categories) {
				return $this->get_error_controller(404)->message('no categories found');
			}
			return $this->get_response($product_categories);
		}

		/**
		 * @not_in_doc
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function get_by_id(): JsonResponse {
			$id = (int)$this->get('id');
			/** @var Product_categoryEntity|bool $product_category */
			$product_category = $this->product_category_dao->getById($id);
			if(!$product_category) {
				return $this->get_error_controller(404)->message('no categories found for this id');
			}
			return $this->get_response($product_category[0]);
		}

		/**
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function get_by_shop(): JsonResponse {
			$shop = (int)$this->get('shop');
			/** @var Product_categoryEntity|bool $product_category */
			$product_category = $this->product_category_dao->getBy('shop_id', $shop);
			if(!$product_category) {
				return $this->get_error_controller(404)->message('no categories found for this shop');
			}
			return $this->get_response($product_category);
		}

		/**
		 * @param string $name
		 * @param int $shop_id
		 * @http_verb post
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function add(): JsonResponse {
			if(!($this->post('name') && $this->post('shop_id'))) {
				return $this->get_error_controller(503)->message('parameters name and shop_id are required');
			}
			$added = $this->model->add($this->product_category_dao, $this->post('name'), (int)$this->post('shop_id'));
			$success = $added ? true : false;
			if($success) {
				return $this->get_response(
					[
						'status' => true,
						'category' => $added
					]
				);
			}
			else {
				return $this->get_response(
					[
						'status' => $success
					]
				);
			}
		}

		/**
		 * @param int $id
		 * @return ErrorController|Response
		 * @throws Exception
		 */
		public function delete(): JsonResponse {
			if(!$this->get('id')) {
				return $this->get_error_controller(503)->message('parameter id is required');
			}
			$success = $this->model->delete($this->product_category_dao, (int)$this->get('id'));
			return $this->get_response(
				[
					'status' => $success,
				]
			);
		}
	}