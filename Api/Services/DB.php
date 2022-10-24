<?php

namespace Api\Services;

use PDO;
use PDOException;

class DB
{
    private static $connection = null;

    public static function connect(): array
    {
        if (null != self::$connection) {
            return [true, 'Already connect'];
        }
        try {
            self::$connection = new PDO('mysql:host=' . self::$db_host . ';dbname=' . self::$db_database, self::$db_user, self::$db_password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return [true, 'Connection successful'];
        } catch (PDOException $e) {
            return [false, 'Connection failed' . $e->getMessage()];
        }
    }

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


    public static function executeQuery(string $query): \PDOStatement
    {
        self::connect();

        $statement = DB::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }

    public static $db_host = 'localhost';
    public static $db_user = 'test';
    public static $db_password = 'пароль';
    public static $db_database = 'deductionproject';
}
