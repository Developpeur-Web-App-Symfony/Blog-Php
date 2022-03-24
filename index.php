<?php
session_start();
require 'Framework/Route.php';

$route = new Route();
$route->routeRequest();