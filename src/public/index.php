<?php

use Core\Database;
use Core\Router;

require '../vendor/autoload.php';

session_start();
$db = Database::connect();

$router = new Router($db);
$router->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
