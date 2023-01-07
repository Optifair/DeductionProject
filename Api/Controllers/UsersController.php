<?php

namespace Api\Controllers;

use Api\Models\Repositories\UserRepository;
use Api\Services\MailSender;

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
            $ret = ['auth' => false];
            if (!empty($user)) {
                if (UserRepository::verifyPassword($password, $user['salt'], $user['password'])) {
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

                    setcookie('login', $user['login'], $time);
                    setcookie('key', $key, $time);
                    UserRepository::updateUserCookie($login, $key);
                }
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
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
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            $ret = self::checkAuth();
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }

    public function getUserData()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            $authRes = self::checkAuth();
            if ($authRes) {
                $login = $_COOKIE['login'];
                $user = UserRepository::findUserByLogin($login);
                echo json_encode($user, JSON_PRETTY_PRINT);
            }
        }
    }

    public function sendPasswordResetLink()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            $login = $_POST['login'];
            $ret = [
                'isFound' => false
            ];

            $user = UserRepository::findUserByLogin($login);
            $name = $user['name'];
            if (!empty($user)) {
                $ret['isFound'] = true;
                $key = UserRepository::createPasswordResetCodeForUser($login);
                MailSender::sendMail($login, $name, self::frontPath . "/changePassword/" . $key);
            }

            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }

    public function checkPasswordResetKey()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            $key = $_POST['key'];
            $ret = [
                'isFound' => false,
                'login' => ""
            ];

            $user = UserRepository::findUserWithKey($key);
            if (!empty($user)) {
                $ret['isFound'] = true;
                $ret['login'] = $user['login'];
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }

    public function editUserData()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {

            $newName = $_POST['newName'];
            $newLogin = $_POST['newLogin'];
            $newAvatar = $_FILES['newAvatar'];
            $newPass = $_POST['newPass'];
            $pass = $_POST['pass'];

            $ret = [
                'isEdit' => false,
                'isAuth' => false,
                'isPassCorrect' => false,
                'isLoginNotTaken' => true
            ];

            $authRes = self::checkAuth();
            if ($authRes) {
                $ret['isAuth'] = true;
                $login = $_COOKIE['login'];
                $user = UserRepository::findUserByLogin($login);
                if (UserRepository::verifyPassword($pass, $user['salt'], $user['password'])) {
                    $ret['isPassCorrect'] = true;
                    if ($newLogin != '') {
                        $searchedUser = UserRepository::findUserByLogin($newLogin);
                        if (empty($searchedUser)) {
                            UserRepository::updateLogin($login, $newLogin);
                            setcookie('login', $newLogin, time() + 60 * 60 * 24 * 30);
                            $login = $newLogin;
                        } else {
                            $ret['isLoginNotTaken'] = false;
                        }
                    }
                    if ($ret['isLoginNotTaken']) {
                        $ret['isEdit'] = true;
                        if ($newName != '') {
                            UserRepository::updateName($login, $newName);
                        }
                        if (is_uploaded_file($newAvatar['tmp_name'])) {
                            move_uploaded_file($newAvatar['tmp_name'], './Images/' . substr($newAvatar['tmp_name'], 8) . '.png');
                            $newAvatar_path = 'http://' . 'deductionproject' . '/Images/' . substr($newAvatar['tmp_name'], 8) . '.png';

                            UserRepository::updateAvatar($login, $newAvatar_path);
                        }
                        if ($newPass != '') {
                            UserRepository::updatePass($login, $newPass);
                        }
                    }
                }
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }

    public function editPassword()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {

            $key = $_POST['key'];
            $login = $_POST['login'];
            $newPass = $_POST['newPass'];

            $ret = [
                'isEdit' => false
            ];


            $user = UserRepository::findUserByLogin($login);
            if ($user['pass_reset_code'] == $key) {
                $ret['isEdit'] = true;
                if ($newPass != '') {
                    UserRepository::updatePass($login, $newPass);
                    UserRepository::resetPasswordResetCodeForUser($login);
                }
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
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

            UserRepository::updateUserCookie($login, '');
        }
    }

    public function addUser()
    {
        self::setCORSHeaders();
        if (!empty(file_get_contents('php://input'))) {

            $json = file_get_contents('php://input');
            $data = json_decode($json);

            $name = $data->name;
            $login = $data->login;
            $password = $data->password;

            $probablyUser = UserRepository::findUserByLogin($login);
            $ret = ['isRegister' => false];
            if (empty($probablyUser)) {
                UserRepository::addUser($name, $login, $password);
                $ret['isRegister'] = true;
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }
}
