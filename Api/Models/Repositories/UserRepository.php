<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class UserRepository extends Repository
{
    public static function getUsersForAdmin()
    {
        self::prepareExecution();
        $query = QO::select()->table('users');
        $query->columns(
            'id',
            'name',
            'login',
            'password',
            'salt',
            'cookie',
            'avatar',
            'rating',
            'pass_reset_code',
            'ban_timeout',
            'is_admin',
            'is_editor'
        );
        $users = self::executeQuery($query);
        return $users;
    }

    public static function findUserById($id)
    {
        self::prepareExecution();
        $query = QO::select()->table('users')->where(['id', $id, '=']);
        $query->columns(
            'id',
            'name',
            'login',
            'password',
            'salt',
            'cookie',
            'avatar',
            'rating',
            'pass_reset_code',
            'ban_timeout',
            'is_admin',
            'is_editor'
        );
        $queryResult = self::executeQuery($query);
        $user = [];
        if (!empty($queryResult)) {
            $user = $queryResult[0];
        }
        return $user;
    }

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
            'pass_reset_code',
            'ban_timeout',
            'is_admin',
            'is_editor'
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

    public static function updateSalt($login, $newSalt)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('salt')->values($newSalt);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updateIs_editor($login, $newis_editor)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('is_editor')->values($newis_editor);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function updatePass_reset_code($login, $newPass_reset_code)
    {
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('pass_reset_code')->values($newPass_reset_code);
        $query->where(['login', $login, '=']);
        self::executeQuery($query, false);
    }

    public static function deleteUser($id)
    {
        self::prepareExecution();
        $query = QO::delete()->table('users')->where(['id', $id, '=']);
        self::executeQuery($query, false);
    }

    public static function banUser($id, $ban_time)
    {
        $time = date('Y-m-d H:i:s', (time() + $ban_time * 3600));
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('ban_timeout')->values($time);
        $query->where(['id', $id, '=']);
        self::executeQuery($query, false);
    }

    public static function unbanUser($id)
    {

        $time = date('Y-m-d H:i:s');
        self::prepareExecution();
        $query = QO::update()->table('users')->columns('ban_timeout')->values($time);
        $query->where(['id', $id, '=']);
        self::executeQuery($query, false);
    }
}
