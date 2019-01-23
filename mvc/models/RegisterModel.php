<?php

class RegisterModel extends Model {
	/**
	 * @param array $user_infos
	 * @return array|UserEntity
	 * @throws Exception
	 */
	public function register_user(array $user_infos) {
		foreach ($user_infos as $user_info => $info_details) {
			if(is_null($info_details)) {
				return [
					'status' => false,
					'message' => 'write all user infos'
				];
			}
		}
		/** @var UserEntity $user */
		$user = $this->get_entity('user');
		$user->initFromArray($user_infos);
		$user->save(false);
		return $user;
	}
}