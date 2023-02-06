<?php

namespace Api\Controllers;

use Api\Models\Repositories\UserRepository;

class AuthController extends Controller
{
    public static function authUser($login, $password)
    {
        $user = UserRepository::findUserByLogin($login);
        $ret = ['auth' => false, 'ban' => false];
        if (!empty($user)) {
            if (UserRepository::verifyPassword($password, $user['salt'], $user['password'])) {
                if (strtotime($user['ban_timeout']) <= strtotime(date('Y-m-d H:i:s'))) {
                    session_start();
                    $ret['auth'] = true;
                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $user['login'];

                    $remember = true;
                    $key = self::generateCookie();
                    $time = time() + 60 * 30;
                    if ($remember) {
                        $time = time() + 60 * 60 * 24 * 30;
                    }

                    setcookie('login', $user['login'], $time, '/');
                    setcookie('key', $key, $time, '/');
                    UserRepository::updateUserCookie($login, $key);
                } else {
                    $ret['ban'] = true;
                }
            }
        }
        return $ret;
    }

    private static function generateCookie()
    {
        $cookie = '';
        $cookieLength = 8;
        for ($i = 0; $i < $cookieLength; $i++) {
            $cookie .= chr(mt_rand(97, 122));
        }
        return $cookie;
    }

    public static function checkAuth()
    {
        session_start();
        $ban = false;
        if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
            if (!empty($_COOKIE['login']) and !empty($_COOKIE['key'])) {
                $login = $_COOKIE['login'];
                $key = $_COOKIE['key'];

                $user = UserRepository::findUserByLogin($login);
                if (!empty($user) and !empty($user['key']) and $user['key'] == $key) {
                    session_start();
                    $_SESSION['auth'] = true;

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $user['login'];
                }
                if (strtotime($user['ban_timeout']) > strtotime(date('Y-m-d H:i:s'))) {
                    $ban = true;
                }
            }
        }
        $auth = false;
        if (!empty($_SESSION['auth']) and $_SESSION['auth'] and !$ban) {
            $auth = true;
        }
        $ret = ['auth' => $auth, 'ban' => $ban];
        return $ret;
    }

    public static function logoutUser()
    {
        self::setCORSHeaders();

        session_start();
        if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
            $login = $_SESSION['login'];
            session_destroy();

            setcookie('login', '', time(), '/');
            setcookie('key', '', time(), '/');

            UserRepository::updateUserCookie($login, '');
        }
    }
}
