<?php

require "api/services/DB.php";
require "api/Router.php";
use services\DB;
use Api\Router;

$current_link = $_SERVER['REQUEST_URI'];
Router::routes($current_link);
