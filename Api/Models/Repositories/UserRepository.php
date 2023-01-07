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
            $salt .= chr(mt_rand(97, 122));
        }
        return $salt;
    }

    public static function createPasswordResetCodeForUser($login)
    {
        $code = self::generateSalt();
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('pass_reset_code')->values($code);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
        return $code;
    }

    public static function resetPasswordResetCodeForUser($login)
    {
        $code = "";
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('pass_reset_code')->values($code);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
        return $code;
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
            'avatar',
            'rating',
            'pass_reset_code'
        );
        $queryResult = self::executeQuery($query);
        $user = [];
        if (!empty($queryResult)) {
            $user = $queryResult[0];
        }
        return $user;
    }

    public static function findUserWithKey($key)
    {
        self::prepareExecution();
        $query = QO::select()->table('users')->where(['pass_reset_code', $key, '=']);
        $query->columns(
            'login',
        );
        $queryResult = self::executeQuery($query);
        $user = [];
        if (!empty($queryResult)) {
            $user = $queryResult[0];
        }
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

    public static function updateUserCookie($login, $newCookie)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('cookie')->values($newCookie);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updateName($login, $newName)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('name')->values($newName);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updateLogin($login, $newlogin)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('login')->values($newlogin);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updateAvatar($login, $newAvatar)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('avatar')->values($newAvatar);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updatePass($login, $newPassword)
    {
        $salt = self::generateSalt();
        $saltedPass = md5($newPassword . $salt);

        self::prepareExecution();
        $query = QO::update()->table('users')->columns('password')->values($saltedPass);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
        $query = QO::update()->table('users')->columns('salt')->values($salt);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updateRating($login, $rating)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('rating')->values($rating);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }
}
