<?php

namespace Api\Controllers;

use Api\Models\Repositories\CommentRepository;
use Api\Models\Repositories\PostRepository;
use Api\Models\Repositories\UserRepository;

class LikesController extends Controller
{
    public function likeComment()
    {
        self::setCORSHeaders();
        $authRes = AuthController::checkAuth();
        if (!empty(file_get_contents('php://input'))) {
            $ret = ['owner' => false, 'auth' => $authRes['auth']];
            if ($ret['auth']) {
                $json = file_get_contents('php://input');
                $data = json_decode($json);

                $comment_id = $data->id;
                $liked = $data->liked;
                $login = $_COOKIE['login'];

                $user = UserRepository::findUserByLogin($login);
                $user_id = $user['id'];
                $comment = CommentRepository::findComment($comment_id);
                $post = PostRepository::getCurrentPost($comment['post_id']);
                if ($user_id == $post['user_id']) {
                    $ret['owner'] = true;
                    $rating = (int)$user['rating'];
                    if ($liked) {
                        CommentRepository::dislikeComment($comment_id);
                        $rating--;
                    } else {
                        CommentRepository::likeComment($comment_id);
                        $rating++;
                    }
                    UserRepository::updateRating($login, $rating);
                }
            }
            echo json_encode($ret, JSON_PRETTY_PRINT);
        }
    }
}
