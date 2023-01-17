<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class CommentRepository extends Repository
{
    public static function getCommentsForAdmin(): array
    {
        self::prepareExecution();
        $query = QO::select()->table('comments');
        $query->columns(
            'id',
            'user_id',
            'post_id',
            'content',
            'liked',
            'date'
        );
        $comments = self::executeQuery($query);
        return $comments;
    }

    public static function getComments($post_id, $perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('comments')->join(['users', 'comments'], ['id', 'user_id'])->where(['post_id', $post_id, '='])->limit($perPage)->offset($pageNumber);
        $query->columns(
            'comments.id',
            'user_id',
            'post_id',
            'content',
            'liked',
            'date',
            'avatar',
            'name'
        );
        $comments = self::executeQuery($query);
        return $comments;
    }

    public static function addComment($user_id, $post_id, $content)
    {
        self::prepareExecution();
        $query = QO::insert()->table('comments')->columns('user_id', 'post_id', 'content');

        $query->values(
            $user_id,
            $post_id,
            $content
        );
        self::executeQuery($query, false);
    }

    public static function findComment($id): array
    {
        self::prepareExecution();
        $query = QO::select()->table('comments')->join(['users', 'comments'], ['id', 'user_id'])->where(['comments.id', $id, '=']);
        $query->columns(
            'comments.id',
            'user_id',
            'post_id',
            'content',
            'liked',
            'date',
            'avatar',
            'name'
        );
        $comment = self::executeQuery($query)[0];
        return $comment;
    }

    public static function likeComment($comment_id)
    {
        self::prepareExecution();
        $query = QO::update()->table('comments')->columns('liked')->values(1);
        $query->where(['id', $comment_id, '=']);
        self::executeQuery($query, false);
    }

    public static function dislikeComment($comment_id)
    {
        self::prepareExecution();
        $query = QO::update()->table('comments')->columns('liked')->values(0);
        $query->where(['id', $comment_id, '=']);
        self::executeQuery($query, false);
    }
}
