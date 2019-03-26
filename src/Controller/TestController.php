<?php
namespace APP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


/**
 * Class TestController
 * @package TestController\Controller
 */

class TestController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    // Test lights
    public function lights(Request $request, Response $response, $args)
    {
        //Execute python script - easy peasy
        $path = __DIR__ . '/../../scripts/test.py';
        if(file_exists($path)){
          $cmd = "python $path 2>&1";
          $test = shell_exec($cmd);
        }else{
          $test = "ERROR with file, not found!";
        }

        $data = array('msg' => rtrim($test));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withJson($data, 200);
        return $response;

    }

}
