<?php

class Base implements IBase {
	private static $confs = [];

	private static $queues_loader;

	protected function active_depencency_injection() {
		require_once __DIR__.'/autoload_for_dependencies_injection.php';
	}

	protected function get_controllers() {
		$directory = __DIR__.'/../mvc/controllers';
		$dir = opendir($directory);
		$controllers = [];
		while (($elem = readdir($dir)) !== false) {
			if($elem !== '.' && $elem !== '..') {
				$controllers[] = strtolower(str_replace('Controller.php', '', $elem));
			}
		}
		return $controllers;
	}

	/**
	 * @param $model
	 * @return Model
	 * @throws Exception
	 */
	public function get_model(string $model) {
		$model = ucfirst($model).'Model';
		if(file_exists(__DIR__.'/../mvc/models/'.$model.'.php')) {
			require_once __DIR__.'/../mvc/models/'.$model.'.php';
			return new $model();
		}
		else {
			throw new Exception('La classe '.$model.' n\'existe pas !');
		}
	}

	/**
	 * @param $service
	 * @return Service
	 * @throws Exception
	 */
	public function get_service(string $service) {
		$service = ucfirst($service).'Service';
		if(file_exists(__DIR__.'/../services/'.$service.'.php')) {
			require_once __DIR__.'/../services/interfaces/I'.$service.'.php';
			require_once __DIR__.'/../services/'.$service.'.php';
			/** @var Service $o_service */
			$o_service = new $service();
			$o_service->initialize_after_injection();
			return $o_service;
		}
		else {
			throw new Exception('La classe '.$service.' n\'existe pas !');
		}
	}

	/**
	 * @param $dao
	 * @return Repository
	 * @throws Exception
	 */
	public function get_dao(string $dao) {
		return $this->get_repository($dao);
	}

	/**
	 * @param $repository
	 * @return Repository
	 * @throws Exception
	 */
	public function get_repository(string $repository) {
		$repository = ucfirst($repository).'Repository';
		if(file_exists(__DIR__.'/../dao/'.$repository.'.php')) {
			require_once __DIR__.'/../dao/'.$repository.'.php';
			/** @var Repository $o_service */
			return new $repository();
		}
		elseif (file_exists(__DIR__.'/../dao/'.str_replace('Repository', 'Dao', $repository).'.php')) {
			$repository = str_replace('Repository', 'Dao', $repository);
			require_once __DIR__.'/../dao/'.$repository.'.php';
			return new $repository();
		}
		else {
			throw new Exception('La classe '.$repository.' n\'existe pas !');
		}
	}

	/**
	 * @param string $entity
	 * @return Entity
	 * @throws Exception
	 */
	public function get_entity(string $entity) {
		$entity = ucfirst($entity).'Entity';
		if(file_exists(__DIR__.'/../entities/'.$entity.'.php')) {
			require_once __DIR__.'/../entities/'.$entity.'.php';
			return new $entity();
		}
		else {
			throw new Exception('\'La classe '.$entity.' n\'existe pas !');
		}
	}

	/**
	 * @return array
	 */
	protected function get_entities() {
		$directory = __DIR__.'/../entities';
		$dir = opendir($directory);
		$entities = [];
		while (($elem = readdir($dir)) !== false) {
			if($elem !== '.' && $elem !== '..') {
				$entities[] = strtolower(str_replace('Entity.php', '', $elem));
			}
		}
		return $entities;
	}

	/**
	 * @param string $conf
	 * @return Conf
	 * @throws Exception
	 */
	public function get_conf(string $conf) {
		$conf = ucfirst($conf).'Conf';
		if(file_exists(__DIR__.'/../conf/'.$conf.'.php')) {
			if(!isset(self::$confs[$conf])) {
				require_once __DIR__.'/../conf/'.$conf.'.php';
				self::$confs[$conf] = new $conf();
			}
			return self::$confs[$conf];
		}
		else {
			throw new Exception('\'La classe '.$conf.' n\'existe pas !');
		}
	}

	/**
	 * @param string $thread
	 * @return Threadable
	 * @throws Exception
	 */
	protected function get_thread(string $thread) {
		$thread = ucfirst($thread).'Thread';
		if(file_exists(__DIR__.'/../threads/'.$thread.'.php')) {
			require_once __DIR__.'/../threads/'.$thread.'.php';
			return new $thread();
		}
		else {
			throw new Exception('\'La classe '.$thread.' n\'existe pas !');
		}
	}

	/**
	 * @param string $type
	 * @param mixed $object
	 * @return Response
	 * @throws Exception
	 */
	protected function get_response($object, $type = Response::JSON) {
		return Response::create($object, $type);
	}

	public function toArrayForJson($recursive = true) {
		return [];
	}

	/**
	 * @return \mvc_framework\core\queues\ModuleLoader
	 * @throws Exception
	 */
	public function queues_loader() {
		if(!class_exists(\mvc_framework\core\queues\ModuleLoader::class)) {
			throw new Exception('plugin `queues` not found');
		}
		if(is_null(self::$queues_loader)) {
			self::$queues_loader = new \mvc_framework\core\queues\ModuleLoader();
		}
		return self::$queues_loader;
	}
}