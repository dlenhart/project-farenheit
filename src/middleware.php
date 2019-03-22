<?php
// Application middleware - DDL
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use APP\Auth\Auth as Auth;


// Strip trailing slash if user enters one
$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath(substr($path, 0, -1));
        return $response->withRedirect((string)$uri, 301);
    }

    return $next($request, $response);
});

//Auth Middleware for Form
$authenticate = function ($request, $response, $next) {
    if (!isset($_SESSION['admin'])) {
        $path = $request->getAttribute('routeInfo');
        $path = $path['request'][1];

        //add path to flash msgs for login flash message!
        $this->flash->addMessage('url', $path);
        return $response->withRedirect('/login');
    }

    $response = $next($request, $response);
    return $response;
};

// Auth Middleware for API
$authenticateAPI = function ($request, $response, $next) {

    //POOR MAN's basic auth =)
    if (!isset($_SESSION['admin'])) {
        $auth = $request->getHeader('Authorization');

        if(!$auth){
          //auth header not sent
          $status = array('status' => '401', 'msg' => 'No credentials provided');
          $response = $response->withHeader('Content-Type', 'application/json');
          $response = $response->withJson($status, 401);
          return $response;
        }
        //decode authorization header
        if (strpos(strtolower($auth[0]),'basic')===0){
          $creds = explode(':',base64_decode(substr($auth[0], 6)));
        }
        // attempt new authentication
        $auth = new Auth;
        $auth = $auth->attempt($creds[0], $creds[1], true);

        if (!$auth) {
            //false
            $status = array('status' => '401', 'msg' => 'incorrect credentials!');
            
            //lets log the bad attempts
            $this->log->critical("Incorrect Credential attempt: Username: " . $creds[0]);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withJson($status, 401);
            return $response;
        }

    }

    $response = $next($request, $response);
    return $response;
};

// Validation Errors middleware
$validationErrors = function (Request $request, Response $response, $next) {
    //get session errors
    if (isset($_SESSION['ERRORS'])) {
        // Add to global variable
        $this->view->getEnvironment()->addGlobal('ERRORS', $_SESSION['ERRORS']);
        // remove session var
        unset($_SESSION['ERRORS']);
    }

    return $next($request, $response);
};

// Form Data middleware
$oldFormData = function (Request $request, Response $response, $next) {
    if (isset($_SESSION['DATA'])) {
        $this->view->getEnvironment()->addGlobal('DATA', $_SESSION['DATA']);
        $_SESSION['DATA'] = (array)$request->getParsedBody();
    }

    return $next($request, $response);
};
