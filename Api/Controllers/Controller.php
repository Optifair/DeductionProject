<?php

namespace Api\Controllers;

use Api\Models\Repositories\UserRepository;

class Controller
{
    public const frontPath = "http://deductionproject:3000";

    public static function setCORSHeaders()
    {
        header("Access-Control-Allow-Origin: " . self::frontPath);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: access-control-allow-credentials, access-control-allow-origin, content-type");
        header("Access-Control-Allow-Methods: *");
    }

    public static function checkAuth()
    {
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
        return $ret;
    }
}
