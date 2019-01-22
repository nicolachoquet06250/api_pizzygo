<?php

class install extends cmd {
	/**
	 * @throws Exception
	 */
	protected function db() {
		foreach ($this->get_entities() as $entity_name) {
			$entity = $this->get_entity($entity_name);
			$entity->set_mysql(
				$this->get_mysql()
			);
			if(!$entity->create_db()) {
				throw new Exception('Une erreur est survenue lors de la création de la table '.$entity_name);
			}
		}
	}
}