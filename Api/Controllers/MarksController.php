<?php

namespace Api\Controllers;

use Api\Models\Repositories\MarkRepository;
use Api\Models\Repositories\UserRepository;

class MarksController extends Controller
{

    public function getMarks()
    {
        self::setCORSHeaders();
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {

            $authRes = AuthController::checkAuth();
            if ($authRes) {
                $login = $_COOKIE['login'];

                $perPage = $_GET['limit'] ?? 5;
                $pageNumber = $_GET['offset'] ?? 0;

                $postsArray['posts'] = MarkRepository::getMarks($login, $perPage, $pageNumber);
                echo json_encode($postsArray, JSON_PRETTY_PRINT);
            }
        }
    }

    public function markPost()
    {
        self::setCORSHeaders();
        $authRes = AuthController::checkAuth();
        if (!empty(file_get_contents('php://input'))) {
            if ($authRes['auth']) {
                $json = file_get_contents('php://input');
                $data = json_decode($json);

                $post_id = $data->id;
                $login = $_COOKIE['login'];
                $user = UserRepository::findUserByLogin($login);

                $mark = MarkRepository::findMark($user['id'], $post_id);
                if (!empty($mark)) {
                    MarkRepository::deleteMark($mark[0]['mark_id']);
                } else {
                    MarkRepository::addMark($user['id'], $post_id);
                }
            }
            echo json_encode($authRes, JSON_PRETTY_PRINT);
        }
    }
}
