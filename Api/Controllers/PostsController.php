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

    public function getCurrentPost()
    {
        self::getHeaders();
        $id = $_GET['id'] ?? null;

        $post = PostRepository::getCurrentPost($id);

        echo json_encode($post, JSON_PRETTY_PRINT);
    }
}
