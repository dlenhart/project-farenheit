<?php
namespace APP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


/**
 * Class HomeController
 * @package HomeController\Controller
 */

class HomeController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    // Home Page
    public function index(Request $request, Response $response, $args)
    {
        //Check install - home page will display install box if no file.
        $install = $this->install->checkInstalled();

        // return response in view.
        $data = array('title' => 'Home', 'install' => $install);
        return $this->view->render($response, 'Home.html', $data);
    }

    // History
    public function history(Request $request, Response $response, $args)
    {
        //data file in config
        //not sure how crazy this file will get over time.....
        $history = $this->utility->getINIValue('DATAFILE');
        $fp = @fopen($history, 'r');

        // put each line into array.
        if ($fp) {
           $array = explode("\n", fread($fp, filesize($history)));
        }

        fclose($fp);
        //use array filter to remove empty elements.
        // Use TWIG to parse the entries to a table & reverse the order.
        $data = array('title' => 'History', 'contents' => array_filter($array));
        return $this->view->render($response, 'History.html', $data);
    }

}
