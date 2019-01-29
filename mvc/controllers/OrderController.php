<?php
		
	class OrderController extends Controller {

		/**
		 * @return Response
		 * @throws Exception
		 */
		protected function index() {
			return $this->get_response([]);
		}
	}