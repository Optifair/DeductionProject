<?php

namespace Api\Controllers;

use Api\Services\DB;
use Api\Models\Repositories\PostRepository;
use Api\Controllers\Controller;

class PostsController extends Controller
{
    public function getPostsFromDatabase()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $perPage = $_GET['limit'] ?? 5;
        $pageNumber = $_GET['offset'] ?? 0;

        $postsArray['posts'] = PostRepository::getPosts($perPage, $pageNumber);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }

    public function searchPostsByKey()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $key = $_GET['key'];

        $postsArray['posts'] = PostRepository::searchPostsByKey($key);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }

    public function getCurrentPost()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        self::getHeaders();
        $id = $_GET['id'] ?? null;

        $postsArray['posts'] = PostRepository::getCurrentPost($id);

        echo json_encode($postsArray, JSON_PRETTY_PRINT);
    }
}
