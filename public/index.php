<?php
use Framework\Route;

require_once '../vendor/autoload.php';

session_start();

$route = new Route();
$route->routeRequest();