<?php

require 'Autoloader.php';
use Api\Services\DB;
use Api\Router;

$current_link = $_SERVER['REQUEST_URI'];
Router::routes($current_link);
