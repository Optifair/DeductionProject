<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require "api/services/DB.php";
require "api/Router.php";
use services\DB;
use Api\Router;

$current_link = $_SERVER['REQUEST_URI'];
Router::routes($current_link);