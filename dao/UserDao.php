<?php

/**
 * @method bool|UserEntity getByEmailAndPassword(string $email, string $password)
 * @method bool|UserEntity[]|UserEntity getId_Name_Surname_Email_DescriptionByEmailAndPassword(string $email, string $password)
 * @method bool|UserEntity[]|UserEntity getId_Name_SurnameByEmailAndPassword(string $email, string $password)
 * @method bool|UserEntity getByEmail(string $email)
 */
class UserDao extends Repository {}