<?php

class UserDao extends Repository {
	/**
	 * @param string $email
	 * @param string $password
	 * @return bool|Entity
	 * @throws Exception
	 */
	public function getByEmailAndPassword(string $email, string $password) {
		$prefix = '';
		if($this->mysql_conf->has_property('table-prefix')) {
			$prefix = $this->mysql_conf->get('table-prefix');
		}
		$query = $this->get_mysql()->query('SELECT COUNT(*) FROM '.$prefix.$this->table_name.' WHERE `email`="'.$email.'" AND `password`="'.$password.'"');
		if($query) {
			$count = null;
			while ((list($nb_lignes) = $query->fetch_row()) !== false) {
				$count = (int)$nb_lignes;
				break;
			}
			if(!is_null($count) && $count > 0) {
				$query = $this->get_mysql()->query('SELECT * FROM '.$prefix.$this->table_name.' WHERE `email`="'.$email.'" AND `password`="'.$password.'"');
				if($query) {
					while ($user = $query->fetch_assoc()) {
						$_user = $this->get_entity('user');
						$_user->initFromArray($user);
						return $_user;
					}
				}
			}
		}
		return false;
	}
}