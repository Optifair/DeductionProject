<?php

namespace Api\Controllers;

use Api\Models\Repositories\PostRepository;
use Api\Models\Repositories\UserRepository;


class PostsController extends Controller
{
    public function getPosts()
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
        if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
            if ($authRes['auth']) {

                $content = $_POST['content'];
                $title = $_POST['title'];

                $image = $_FILES['image'];

                move_uploaded_file($image['tmp_name'], './Images/' . substr($image['tmp_name'], 8) . '.png');
                $image_path = 'http://' . 'deductionproject' . '/Images/' . substr($image['tmp_name'], 8) . '.png';

                $login = $_COOKIE['login'];
                $user_id = UserRepository::findUserByLogin($login)['id'];

                PostRepository::addPost($user_id, $title, $content, $image_path);
            }
            echo json_encode($authRes, JSON_PRETTY_PRINT);
        }

    }
}
