<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class MarkRepository extends Repository
{
    public static function getMarksForAdmin(): array
    {
        self::prepareExecution();
        $query = QO::select()->table('marks');
        $query->columns(
            'mark_id',
            'user_id',
            'post_id',
            'mark_date'
        );
        $marks = self::executeQuery($query);
        return $marks;
    }

    public static function getMarks($login, $perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('posts')->join(['marks', 'posts'], ['post_id', 'id'])
            ->join(['users', 'marks'], ['id', 'user_id'])
            ->join(['users', 'posts'], ['id', 'user_id'], 'postsUsers')
            ->where(['users.login', $login, '='])->limit($perPage)
            ->offset($pageNumber)->orderBy(['mark_date', 'DESC']);
        $query->columns(
            'posts.id',
            'posts.user_id',
            'title',
            'content',
            'image',
            'date',
            'postsUsers.avatar',
            'postsUsers.name',
            'mark_date',
            'users.login'
        );
        $posts = self::executeQuery($query);
        return $posts;
    }

    public static function addMark($user_id, $post_id)
    {
        self::prepareExecution();
        $query = QO::insert()->table('marks')->columns('user_id', 'post_id');

        $query->values(
            $user_id,
            $post_id,
        );
        self::executeQuery($query, false);
    }

    public static function findMark($user_id, $post_id)
    {
        self::prepareExecution();
        $query = QO::select()->table('marks')->where(['user_id', $user_id, '='])
            ->where(['post_id', $post_id, '=']);
        $query->columns(
            'mark_id',
            'user_id',
            'post_id'
        );
        $mark = self::executeQuery($query);
        return $mark;
    }

    public static function deleteMark($mark_id)
    {
        self::prepareExecution();
        $query = QO::delete()->table('marks')->where(['mark_id', $mark_id, '=']);
        self::executeQuery($query, false);
    }

    public static function updatePost_id($id, $newPost_id)
    {
        self::prepareExecution();
        $query = QO::update()->table('marks')->columns('post_id')->values($newPost_id);
        $query->where(['mark_id', $id, '=']);
        self::executeQuery($query, false);
    }

    public static function updateUser_id($id, $newUser_id)
    {
        self::prepareExecution();
        $query = QO::update()->table('marks')->columns('user_id')->values($newUser_id);
        $query->where(['mark_id', $id, '=']);
        self::executeQuery($query, false);
    }

    public static function updateDate($id, $newDate)
    {
        self::prepareExecution();
        $query = QO::update()->table('marks')->columns('mark_date')->values($newDate);
        $query->where(['mark_id', $id, '=']);
        self::executeQuery($query, false);
    }

}
