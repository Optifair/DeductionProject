<?php

namespace Api\Models\Repositories;

use Api\Services\DB;
use Exception;
use Api\Models\Tools\QueryObject;

class Repository
{
    public static function prepareExecution()
    {
        DB::connect();
        if (!DB::checkConnection()) {
            throw new Exception('Unable to connect to server. Please try again later.');
        }
    }

    public static function executeQuery(QueryObject $query, bool $yields = true)
    {
        ob_start();
        echo $query;
        $queryString = ob_get_clean();

        $statement = DB::executeQuery($queryString);
        if ($yields) {
            return $statement->fetchAll();
        } else {
            return [];
        }
    }
}
