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
            $user = UserRepository::findUserByLogin($_COOKIE['login']);
            if (!$user['is_editor'] && !$user['is_admin']) {
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
            $user = UserRepository::findUserByLogin($login);
            if ($user['is_editor'] || $user['is_admin']) {
                header('Location: \admin');
            } else {
                header('Location: \admin\authPage?error=notFound');
            }
        } else {
            header('Location: \admin\authPage?error=notFound');
        }
    }

}
