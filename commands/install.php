<?php

class install extends cmd {
	/**
	 * @throws Exception
	 */
	protected function db() {
		foreach ($this->get_entities() as $entity_name) {
			$entity = $this->get_entity($entity_name);
			$entity->create_db();
		}
	}
}