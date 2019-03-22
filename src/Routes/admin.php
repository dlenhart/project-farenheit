<?php
/*
author:  Drew Lenhart
des:	routes - admin
e.g. -   $app->get("route/url", '{{controller}}:{{method}}');
*/

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



// Register New Users ( authenticate )
$app->get('/register', 'AuthController:viewRegister')
  ->add($oldFormData)
  ->add($validationErrors)
  ->add($authenticate);
$app->post('/register', 'AuthController:postRegister');

// Duplicate Register for initial user
$app->get('/create/initial/user', 'AuthController:initialUser')
  ->add($oldFormData)
  ->add($validationErrors);

//User view
$app->get('/users', 'AuthController:users')->add($authenticate);
$app->post('/users/delete', 'AuthController:deleteUser')->add($authenticate);

// Login/logout
$app->get('/login', 'AuthController:login')->add($validationErrors);
$app->post('/login', 'AuthController:postLogin');
$app->get('/logout', 'AuthController:logout');

// Messages
$app->get('/messages', 'AuthController:testMessages');
