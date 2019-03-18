<?php
/*
author:  Drew Lenhart
des:	routes - api
e.g. -   $app->get("route/url", '{{controller}}:{{method}}');
*/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Temperature API routes
$app->get('/api/get/temperature', 'ApiController:temperature')->add($authenticate);
$app->get('/api/get/temperature/log', 'ApiController:logTemperature')->add($authenticateAPI);

// Monitoring API routes
$app->get('/api/get/uptime', 'ApiController:uptime')->add($authenticateAPI);
$app->get('/api/get/health', 'ApiController:health');
$app->get('/api/get/test', 'ApiController:test')->add($authenticateAPI);
