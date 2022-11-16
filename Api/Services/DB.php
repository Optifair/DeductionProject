<?php

namespace Api\Services;

use Api\Services\DBCredentials as Creds;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private static $connection = null;

    public static function closeConnection()
    {
        self::$connection = null;
    }

    public static function checkConnection(): bool
    {
        $checkedCon = true;
        if (null === self::$connection) {
            $checkedCon = false;
        }
        return $checkedCon;
    }

    public static function executeQuery(string $query): PDOStatement
    {
        self::connect();

        $statement = DB::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }

    public static function connect(): array
    {
        if (null != self::$connection) {
            return [true, 'Already connect'];
        }
        try {
            self::$connection = new PDO('mysql:host=' . Creds::$db_host . ';dbname=' . Creds::$db_database, Creds::$db_user, Creds::$db_password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return [true, 'Connection successful'];
        } catch (PDOException $e) {
            return [false, 'Connection failed' . $e->getMessage()];
        }
    }
}
