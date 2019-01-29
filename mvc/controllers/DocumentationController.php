<?php
		
	class DocumentationController extends Controller {
		/**
		 * @return Response
		 * @throws Exception
		 */
		protected function index() {
			/** @var DocumentationModel $model */
			$model = $this->get_model('documentation');
			$object = $model->get_doc_content();
			return $this->get_response($object, Response::HTML);
		}
	}