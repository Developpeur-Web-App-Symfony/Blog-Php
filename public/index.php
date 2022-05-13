<?php
session_start();
require '../vendor/autoload.php';
require '../Autoloader.php';
\App\Autoloader::register();

use Framework\Route;

$route = new Route();
$route->routeRequest();