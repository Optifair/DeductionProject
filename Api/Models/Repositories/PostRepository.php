<?php

namespace Api\Models\Repositories;

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
            'image',
            'date'
        );

        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function getMarks($login, $perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->join(['marks', 'posts'], ['post_id', 'id'])->
        join(['users', 'marks'], ['id', 'mark_user_id'])->where(['login', $login, '='])->limit($perPage)->offset($pageNumber);
        $query->columns(
            'posts.id',
            'user_id',
            'title',
            'content',
            'image',
            'date',
            'mark_date'
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
        $query = QO::select()->table('posts')->limit($perPage)->offset($pageNumber)->
        where(['date', $startDate, '>='])->where(['date', $endDate, '<='])->like($key);
        $query->columns(
            'id',
            'user_id',
            'title',
            'content',
            'image',
            'date'
        );
        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function addMark($user_id, $post_id)
    {
        self::prepareExecution();
        $query = QO::insert()->table('marks')->columns('mark_user_id', 'post_id');

        $query->values(
            $user_id,
            $post_id,
        );
        self::executeQuery($query, false);
    }

    public static function findMark($user_id, $post_id)
    {
        self::prepareExecution();
        $query = QO::select()->table('marks')->where(['mark_user_id', $user_id, '='])
            ->where(['post_id', $post_id, '=']);
        $query->columns(
            'mark_id',
            'mark_user_id',
            'post_id'
        );
        $mark = self::executeQuery($query);
        return $mark;

    }

    public static function deleteMark($mark_id)
    {
        self::prepareExecution();
        $query = QO::delete()->table('marks')->where(['mark_id', $mark_id, '=']);
        echo $query;
        $mark = self::executeQuery($query)[0];
    }
}
