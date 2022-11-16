<?php

require 'Autoloader.php';

use Api\Router;

$current_link = $_SERVER['REQUEST_URI'];
Router::routing($current_link);
