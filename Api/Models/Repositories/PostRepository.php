<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class PostRepository extends Repository
{
    public static function getPosts($perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->join(['users', 'posts'], ['id', 'user_id'])->limit($perPage)
            ->offset($pageNumber)->orderBy(['date', 'DESC']);
        $query->columns(
            'posts.id',
            'user_id',
            'title',
            'content',
            'image',
            'avatar',
            'date',
            'name'
        );

        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function getCurrentPost($id): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->where(['id', $id, '=']);
        $query->columns(
            'id',
            'user_id',
            'title',
            'content',
            'image',
            'date'
        );

        $post = self::executeQuery($query)[0];
        return $post;
    }

    public static function searchPosts($perPage, $pageNumber, $key, $startDate, $endDate): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->join(['users', 'posts'], ['id', 'user_id'])
            ->limit($perPage)->offset($pageNumber)
            ->where(['date', $startDate, '>='])->where(['date', $endDate, '<='])
            ->like($key)->orderBy(['date', 'DESC']);
        $query->columns(
            'posts.id',
            'user_id',
            'title',
            'content',
            'image',
            'avatar',
            'name',
            'date'
        );
        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function addPost($user_id, $title, $content, $image)
    {
        self::prepareExecution();
        $query = QO::insert()->table('posts')->columns('user_id', 'title', 'content', 'image');

        $query->values(
            $user_id,
            $title,
            $content,
            $image
        );
        self::executeQuery($query, false);
    }
}
