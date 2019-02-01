<?php

	/**
	 * Class DocumentationController
	 */
	class DocumentationController extends Controller {
		/**
		 * @title DOCUMENTATION
		 * @describe Injection de dépendences disponible :
		 *  - Par propriété grace à la PHPDoc de la propriété
		 *  - Par paramètres de méthode dans les controlleurs en definissant le type voulu devant la variable.
		 * @return Response
		 * @throws Exception
		 */
		protected function index(SessionService $session_service = null, HttpService $http_service = null, DocumentationModel $model = null) {
			if($http_service->post('email') && $http_service->post('password')) {
				/** @var UserDao $user_dao */
				$user_dao = $this->get_dao('user');
				/** @var UserEntity|bool $user */
				$user = $user_dao->getByEmailAndPassword(
					$this->http_service->post('email'),
					sha1(sha1($this->http_service->post('password')))
				);
				if($user && $user->toArrayForJson()['roles'][0]['role'] === RoleEntity::ADMIN) {
					$model->create_session($user);
				}
				else {
					$object = $model->get_connexion_content('Votre compte n\'à pas les droits administrateurs !!');
					return $this->get_response($object, Response::HTML);
				}
			}
			$object = $session_service->has_key('doc_admin') ? $model->get_doc_content() : $model->get_connexion_content();

			return $this->get_response($object, Response::HTML);
		}

		/**
		 * @throws Exception
		 * @not_in_doc
		 */
		protected function disconnect(DocumentationModel $model) {
			if($model->delete_session()) {
				header('location: /api/index.php/documentation');
			}
			return $this->get_response('', Response::HTML);
		}
	}