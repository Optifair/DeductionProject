<?php

namespace Api\Admin\AdminControllers;

use Api\Controllers\AuthController;
use Api\Models\Repositories\UserRepository;

class AuthAdminController
{
    static function logout()
    {
        AuthController::logoutUser();
        header('Location: \admin\authPage');
    }

    static function cookieAuth()
    {
        $auth = AuthController::checkAuth()['auth'];

        if ($auth) {
            if (!UserRepository::findUserByLogin($_COOKIE['login'])['isAdmin']) {
                $auth = false;
            }
        }
        return $auth;
    }

    static function auth()
    {
        $login = $_POST['login'];
        $password = $_POST['pass'];

        $auth = AuthController::authUser($login, $password)['auth'];
        if ($auth) {
            if (UserRepository::findUserByLogin($login)['isAdmin']) {
                header('Location: \admin');
            } else {
                header('Location: \admin\authPage?error=notFound');
            }
        } else {
            header('Location: \admin\authPage?error=notFound');
        }
    }

}
