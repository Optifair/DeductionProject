<?php

namespace Api\Models\Repositories;

use Api\Models\Repositories\Repository;
use Exception;
use Api\Models\Tools\QueryObject as QO;

class PostRepository extends Repository
{
    public static function getPosts($perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->limit($perPage)->offset($pageNumber);
        $query->columns(
            'id',
            'user_id',
            'title',
            'content',
            'image'
        );

        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function getCurrentPost($id): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->where(['id',$id]);
        $query->columns(
            'id',
            'user_id',
            'title',
            'content',
            'image'
        );

        $post = self::executeQuery($query)[0];
        return $post;
    }
}
