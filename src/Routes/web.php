<?php
/*
author:  Drew Lenhart
des:	routes - web
e.g. -   $app->get("route/url", '{{controller}}:{{method}}');
*/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/', 'HomeController:index');
$app->get('/history', 'HomeController:history');
// Create Sample SQLite database
$app->get('/install', 'InstallController:createDatabase');
// Create Users table....
$app->get('/install/create', 'InstallController:createUserTable');
