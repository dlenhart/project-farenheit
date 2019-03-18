<?php
namespace APP\Utility;

/**
* @author  Drew D. Lenhart
* @since   March 8, 2019
* @link    https://github.com/dlenhart/project-cylon
* @version 1.0.0
*/

class Utility
{
    /**
    * $file - path and config file
    * @var string
    */
    protected $file;

    /**
    * $string - string value
    * @var string
    */
    protected $string;

    /**
    * Class constructor
    * @param string $file - path and filename of config file
    */
    public function __construct($file = __DIR__ . "/../../config/config.ini"){
        $this->file = $file;
        //check if file exsists
        if(!file_exists($this->file)){
            //throw exception if not found
            throw new Exception("ERROR: Unable to read configuration!", 1);
        }

    }

    /**
    * readINIConfig Method ( reads ini file )
    * @return array $parse
    */
    public function readINIConfig(){
        $parse = parse_ini_file($this->file);

        if (empty($parse)) {
          return false;
        }

        return $parse;
    }

    /**
    * getINIValue Method ( reads value in ini file )
    * @param string $string
    * @return $result
    */
    public function getINIValue($string){
        //readINIconfig returns array, find value
        $read = $this->readINIConfig();
        $result = $read[$string];

        if($result == '' || $result == null){
          return false;
        }

        return $result;
    }

}
