<?php

namespace Api\Controllers;

use Api\Models\Repositories\CommentRepository;

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
}
