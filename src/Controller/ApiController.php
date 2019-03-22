<?php
namespace APP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

/**
 * Class ApiController
 * @package ApiController\Controller
 */

class ApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    //check temperature.
    public function temperature(Request $request, Response $response, $args)
    {
        //call temperature method & just return F for this.
        //null temperature will indicate a problem with the Temperature reader!
        $data = $this->temperature->readTemperature();

        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withJson($data, 200);
        return $response;
    }

    // get latest temperature & log, for use with processing cmds
    public function logTemperature(Request $request, Response $response, $args)
    {
        $data = $this->temperature->readTemperature();
        //handle file not found
        if ($data['status'] == 'ERR') {
            $status = array('status' => 'ERROR', 'message' => $data['msg']);
            $this->log->critical(json_encode($status));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withJson($status, 500);
            return $response;
        }

        //Lets catch any thermometer failures here - incase the thermometr is having issues.
        //Log it differently in the datafile
        if ($data['status'] == 'NO') {
            $string = "Unable to get a temperature reading with attempts: " . $data['attempts'];
            $msgStat = "ERROR";
            //log thermometer problem
            $this->log->critical($string);
        } else {
            $string = $data['celcius'] . " : " . $data['ferenheit'];
            $msgStat = "SUCCESS";
        }

        //write log file
        $log = $this->logger->write($string);

        if (!$log) {
            $status = array('status' => 'ERROR', 'message' => 'Trouble logging to datafile!');
            $this->log->critical(json_encode($status));
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withJson($status, 500);
            return $response;
        }

        //rebuild array for custom messages.
        $status = array(
            'status'    =>  $msgStat,
            'timestamp' =>  $data['timestamp'],
            'ferenheit' =>  $data['ferenheit'],
            'celcius'   =>  $data['celcius'],
            'attempts'  =>  $data['attempts'],
          );
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withJson($status, 200);
        return $response;
    }

    // Health Status - Simple health check
    public function health(Request $request, Response $response, $args)
    {
        $data = array('status' => 'OK');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withJson($data, 200);
        return $response;
    }

    // Uptime Date
    public function uptime(Request $request, Response $response, $args)
    {
        $g = $this->uptime->getUptime();

        if (!$g) {
            $data = array(
            'status' => '500',
            'message' => 'Bad date conversion'
          );
            //log it
            $this->log->critical(json_encode($data));

            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withJson($data, 500);
            return $response;
        };

        //build array for json output
        $arr = array(
          'DateRaw' => $g,
          'DateConverted' => date('M-d-Y H:i:s', $g)
        );

        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withJson($arr, 200);
        return $response;
    }

    // Testing random stuff
    public function test(Request $request, Response $response, $args)
    {
        echo "Authenticated!";
    }

}
