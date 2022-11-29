<?php

namespace Api\Controllers;

use Api\Models\Repositories\PostRepository;
use Api\Models\Repositories\UserRepository;

class PostsController extends Controller
{
    public function getPostsFromDatabase()
    {
        self::setCORSHeaders();

        $perPage = $_GET['limit'] ?? 5;
        $pageNumber = $_GET['offset'] ?? 0;

        $postsArray['posts'] = PostRepository::getPosts($perPage, $pageNumber);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }

    public function searchPosts()
    {
        self::setCORSHeaders();

        $perPage = $_GET['limit'] ?? 5;
        $pageNumber = $_GET['offset'] ?? 0;

        $key = $_GET['key'] ?? '';
        $startDate = $_GET['startDate'] ?? '2014-08-18T21:11:54';
        $endDate = $_GET['endDate'] ?? '3014-08-18T21:11:54';

        $postsArray['posts'] = PostRepository::searchPosts($perPage, $pageNumber, $key, $startDate, $endDate);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }

    public function getCurrentPost()
    {
        self::setCORSHeaders();

        $id = $_GET['id'] ?? null;

        $postsArray['posts'] = PostRepository::getCurrentPost($id);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }

    public function addPost()
    {
        self::setCORSHeaders();
        $authRes = self::checkAuth();
        if (!empty(file_get_contents('php://input'))) {
            if ($authRes['auth']) {
                $json = file_get_contents('php://input');
                $data = json_decode($json);

                $content = $data->content;
                $title = $data->title;
                $image = '';
                if (!empty($data->image)) {
                    $image = $data->image;
                }
                $login = $_COOKIE['login'];
                $user_id = UserRepository::findUserByLogin($login)['id'];

                PostRepository::addPost($user_id, $title, $content, $image);
            }
            echo json_encode($authRes, JSON_PRETTY_PRINT);
        }

    }
}
