<?php

namespace Api\Controllers;

use Api\Models\Repositories\UserRepository;

class UsersController extends Controller
{
    public function authUser()
    {
        self::setCORSHeaders();

        if (!empty(file_get_contents('php://input'))) {
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            $login = $data->login;
            $password = $data->pass;
            $remember = $data->rem = false;

            $user = UserRepository::findUserByLogin($login);
            if (!empty($user)) {
                if (UserRepository::verifyPassword($password, $user['salt'], $user['password'])) {
                    session_start();

                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['login'] = $user['login'];

                    $remember = true;
                    $key = self::generateCookie();
                    $time = time() + 60 * 30;
                    if ($remember) {
                        $time = time() + 60 * 60 * 24 * 30;
                    }

                    setcookie('login', $user['login'], $time);
                    setcookie('key', $key, $time);
                    UserRepository::updateCookieUser($login, $key);
                    echo json_encode($_COOKIE, JSON_PRETTY_PRINT);
                }
            }
        }
    }

    private function generateCookie()
    {
        $cookie = '';
        $cookieLength = 8;
        for ($i = 0; $i < $cookieLength; $i++) {
            $cookie .= chr(mt_rand(33, 126));
        }
        return $cookie;
    }

    public function authUserWithCookie()
    {
        self::setCORSHeaders();

        session_start();
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
            }
        }
        $auth = false;
        if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
            $auth = true;
        }

        $ret = ['auth' => $auth];
        echo json_encode($ret, JSON_PRETTY_PRINT);
    }

    public function getUserData()
    {
        self::setCORSHeaders();

        $login = $_COOKIE['login'];
        $user = UserRepository::findUserByLogin($login);
        $res = [
            'name' => $user['name'],
            'login' => $user['login'],
        ];
        echo json_encode($res, JSON_PRETTY_PRINT);
    }

    public function logoutUser()
    {
        self::setCORSHeaders();

        session_start();
        if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
            $login = $_SESSION['login'];
            session_destroy();

            setcookie('login', '', time());
            setcookie('key', '', time());

            UserRepository::updateCookieUser($login, '');
        }
    }

    public function addUser()
    {
        self::setCORSHeaders();

        $name = $_GET['name'];
        $login = $_GET['login'];
        $password = $_GET['pass'];
        $probablyUser = UserRepository::findUserByLogin($login);
        if (empty($probablyUser)) {
            UserRepository::addUser($name, $login, $password);
        }
    }
}
