<?php

namespace Api\Models\Repositories;

use Api\Models\Tools\QueryObject as QO;

class CommentRepository extends Repository
{
    public static function getComments($user_id, $perPage, $pageNumber): array
    {
        self::prepareExecution();
        $query = QO::select()->table('comments')->where(['user_id', $user_id, '='])->limit($perPage)->offset($pageNumber);
        $query->columns(
            'id',
            'user_id',
            'content',
            'date'
        );

        $comments = self::executeQuery($query);
        return $comments;
    }
}
