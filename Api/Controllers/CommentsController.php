<?php

namespace Api\Controllers;

use Api\Models\Repositories\CommentRepository;
use Api\Models\Repositories\UserRepository;

class CommentsController extends Controller
{
    public function getCommentsOfPost()
    {
        self::setCORSHeaders();
        $post_id = $_GET['post_id'];
        $perPage = $_GET['limit'] ?? 5;
        $pageNumber = $_GET['offset'] ?? 0;

        $commentsArray['comments'] = CommentRepository::getComments($post_id, $perPage, $pageNumber);

        echo json_encode($commentsArray, JSON_PRETTY_PRINT);
    }

    public function addComment()
    {
        self::setCORSHeaders();
        $authRes = self::checkAuth();
        if (!empty(file_get_contents('php://input'))) {
            if ($authRes['auth']) {
                $json = file_get_contents('php://input');
                $data = json_decode($json);

                $post_id = $data->post_id;
                $content = $data->content;
                $login = $_COOKIE['login'];
                $user_id = UserRepository::findUserByLogin($login)['id'];

                CommentRepository::addComment($user_id, $post_id, $content);
            }
            echo json_encode($authRes, JSON_PRETTY_PRINT);
        }
    }
}
