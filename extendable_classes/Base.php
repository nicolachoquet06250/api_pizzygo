<?php

class Base {
	/**
	 * @param $model
	 * @return Model
	 * @throws Exception
	 */
	protected function get_model($model) {
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
	protected function get_service($service) {
		$service = ucfirst($service).'Service';
		if(file_exists(__DIR__.'/../services/'.$service.'.php')) {
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
	protected function get_dao($dao) {
		return $this->get_repository($dao);
	}

	/**
	 * @param $repository
	 * @return Repository
	 * @throws Exception
	 */
	protected function get_repository($repository) {
		$repository = ucfirst($repository).'Repository';
		if(file_exists(__DIR__.'/../dao/'.$repository.'.php')) {
			require_once __DIR__.'/../dao/'.$repository.'.php';
			/** @var Repository $o_service */
			return new $repository();
		}
		elseif (file_exists(__DIR__.'/../dao/'.str_replace('Repository', 'Dao', $repository).'.php')) {
			$repository = str_replace('Repository', 'Dao', $repository);
			require_once __DIR__.'/../dao/'.$repository.'.php';
			/** @var Repository $o_service */
			return new $repository();
		}
		else {
			throw new Exception('La classe '.$repository.' n\'existe pas !');
		}
	}

	/**
	 * @param $entity
	 * @return Entity
	 * @throws Exception
	 */
	protected function get_entity($entity) {
		$entity = ucfirst($entity).'Entity';
		if(file_exists(__DIR__.'/../entities/'.$entity.'.php')) {
			require_once __DIR__.'/../entities/'.$entity.'.php';
			/** @var Entity $o_service */
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
}