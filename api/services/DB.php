<?php

namespace services;

use mysqli;

class DB
{
    public $db_host = 'localhost';
    public $db_user = 'test';
    public $db_password = 'пароль';
    public $db_database = 'deductionproject';

    public function database()
    {
        $conn = new mysqli(
            $this->db_host,
            $this->db_user,
            $this->db_password,
            $this->db_database
        );

        if ($conn->connect_error) {
            die("Connection failed ".$conn->connect_error);
        }

        return $conn;
    }
}
