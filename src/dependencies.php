<?php
// Configuration for Slim Dependency Injection Container

$container = $app->getContainer();

// Authentication
$container['auth'] = function ($container) {
    return new \APP\Auth\Auth;
};

// Using Twig as template engine
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('src/Views', [
        'cache' => false //'cache'
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $view->getEnvironment()->addGlobal('flash', $container['flash']);
    //auth
    $view->getEnvironment()->addGlobal(
        'auth',
        [
          'authenticated' => $container->auth->isAuthenticated(),
          'details' => $container->auth->getUser()
        ]
    );

    return $view;
};

// monolog - logging
$container['log'] = function ($c) {
    $settings = $c->get('settings')['log'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// 404 Not Found
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $title = "OOPS!";
        $data = array('title' => $title);
        return $container->view->render($response, '404.html', $data)
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html');
    };
};

$container['cache'] = function ($container) {
    return new \Slim\HttpCache\CacheProvider();
};

// Flash messages
$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

// Customized below

//Guzzle HTTP client
$container['httpClient'] = function ($container) {
    return new \GuzzleHttp\Client();
};

// Respect Validator
$container['validator'] = function ($container) {
    return new \APP\Validation\Validator;
};

// Utilities
$container['utility'] = function ($container) {
    return new \APP\Utility\Utility;
};

// Utilities
$container['install'] = function ($container) {
    return new \APP\Utility\Install;
};

// Uptime
$container['uptime'] = function ($container) {
    return new \APP\Temperature\Uptime;
};

// Temperature
$container['temperature'] = function ($container) {
    return new \APP\Temperature\Temperature;
};

// Data logger
$container['logger'] = function ($container) {
    return new \APP\Logger\Logger;
};

// HomeController
$container['HomeController'] = function ($container) {
    return new \APP\Controller\HomeController($container);
};

// ApiController
$container['ApiController'] = function ($container) {
    return new \APP\Controller\ApiController($container);
};

// AuthController
$container['AuthController'] = function ($container) {
    return new \APP\Controller\AuthController($container);
};

// InstallController
$container['InstallController'] = function ($container) {
    return new \APP\Controller\InstallController($container);
};
