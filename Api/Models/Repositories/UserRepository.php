<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class UserRepository extends Repository
{
    public static function addUser($name, $login, $pass)
    {
        $salt = self::generateSalt();
        $saltedPass = md5($pass . $salt);

        self::prepareExecution();
        $query = QO::insert()->table('users')->columns('name', 'login', 'password', 'salt', 'cookie', 'avatar');

        $query->values(
            $name,
            $login,
            $saltedPass,
            $salt,
            '',
            ''
        );
        self::executeQuery($query, false);
    }

    private static function generateSalt()
    {
        $salt = '';
        $saltLength = 8;
        for ($i = 0; $i < $saltLength; $i++) {
            $salt .= chr(mt_rand(33, 126));
        }
        return $salt;
    }

    public static function findUserByLogin($login)
    {
        self::prepareExecution();
        $query = QO::select()->table('users')->where(['login', $login, '=']);
        $query->columns(
            'id',
            'name',
            'login',
            'password',
            'salt',
            'cookie',
            'avatar'
        );
        $user = self::executeQuery($query)[0];
        return $user;
    }

    public static function verifyPassword($unverifiedPassword, $salt, $password)
    {
        $verified = false;
        $saltedUnverifiedPassword = md5($unverifiedPassword . $salt);

        if ($saltedUnverifiedPassword == $password) {
            $verified = true;
        }
        return $verified;
    }

    public static function updateCookieUser($login, $cookie)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('cookie')->values($cookie);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }
}
