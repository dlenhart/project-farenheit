<?php
namespace APP\Temperature;

use APP\Utility\Utility;

/**
* @author  Drew D. Lenhart
* @since   March 14, 2019
* @link    https://github.com/dlenhart/project-cylon
* @version 1.0.0
*/

class Temperature
{

    /**
    * $temperature_file - path and log file name
    * @var string
    */
    protected $temperature_file;

    /**
    * $attempts - number of attempts for file read
    * @var int
    */
    protected $attempts = 10;

    /**
    * $statusFlag - status is always considered true until proven false
    * @var boolean
    */
    protected $statusFlag = true;

    /**
    * Class constructor
    * @param string $temperature_file
    */
    public function __construct($temperature_file = '')
    {

        $utility = new Utility;

        if($utility->getINIValue('GPIO_TEST')){
          //test path
          $temperature_file = $utility->getINIValue('GPIO_TEST_PATH');
        }else{
          //production path
          $temperature_file = $utility->getINIValue('GPIO_PATH');
        }

        $this->temperature_file = $temperature_file;

    }

    /**
    * read one wire temperature file
    * @return array $results
    */
    public function readTemperature()
    {
      $timestamp = date("Y-m-d H:i:s");
      //read the one wire file

      if(!file_exists($this->temperature_file)){
          //return something nice so controller can return a readable json message.
          $error = array(
            'status' => 'ERR',
            'msg' => 'W1 file does not exsist! Verify path in config.ini!'
          );
          return $error;
          //die('W1 file does not exsist! Verify path in config.ini!');
      }

      $file = file($this->temperature_file);
      $lines = array_slice($file, 0, 2);
      $status = $this->stripWhiteSpaces($this->parseLastValue($lines[0], " "));
      $attempts = 1;

      if($status == 'NO'){
        //re-attempt read of file with pause
        for($i = 1; $i <= $this->attempts; $i ++)
        {
            $file = file($this->temperature_file);
            $lines = array_slice($file, 0, 2);
            $status = $this->stripWhiteSpaces($this->parseLastValue($lines[0], " "));
            $attempts = $i + 1;
            if($status == 'YES'){
              $this->statusFlag = true;
              break 1;
            }else{
              $this->statusFlag = false;
            }
            sleep(1);
        }
      }

      if($this->statusFlag)
      {
        //get temperature ( 2nd line ) in one wire file
        $temperature = $this->stripWhiteSpaces($this->parseLastValue($lines[1], "="));
        $results = array(
          'status' => $status,
          'timestamp' => $timestamp,
          'celcius' => $this->calculateCelcius($temperature),
          'ferenheit' => $this->calculateFerenheit($temperature),
          'attempts' => $attempts
        );

      }else{
        //output the bad results
        $results = array(
          'status' => $status,
          'timestamp' => $timestamp,
          'celcius' => null,
          'ferenheit' => null,
          'attempts' => $attempts
        );

      }

      return $results;
    }

    /**
    * parse last value of line
    * @param array $array
    * @param $delimeter
    * @return $split (last value in array)
    */
    public function parseLastValue($array = array(), $delimeter)
    {
      $split = explode($delimeter, $array);
      return end($split);
    }

    /**
    * strip white spaces
    * @param $string
    * @return
    */
    public function stripWhiteSpaces($string)
    {
      return trim(preg_replace('!\s+!', ' ', $string));
    }

    /**
    * calculate Ferenheit
    * @param $temperature
    * @return
    */
    public function calculateFerenheit($temperature)
    {
      $tmp = $this->calculateCelcius($temperature);
      return $tmp * 9.0 / 5.0 + 32.0;
    }

    /**
    * calculate Celcius
    * @param $temperature
    * @return
    */
    public function calculateCelcius($temperature)
    {
      return (float)$temperature / 1000.0;
    }

}
