<?php
namespace APP\Logger;

use APP\Utility\Utility;

/**
* @author  Drew D. Lenhart
* @since   March 10, 2019
* @link    https://github.com/dlenhart/project-cylon
* @version 1.0.0
*/

class Logger
{

    /**
    * $log_file - path and log file name
    * @var string
    */
    protected $log_file;

    /**
    * $file - file
    * @var string
    */
    protected $file;

    /**
    * $options - settable options
    * @var array
    */
    protected $options = array(
        'dformat' => 'd-M-Y H:i:s'
    );

    /**
    * Class constructor
    * @param string $log_file
    * @param array $params
    */
    public function __construct($log_file = '', $params = array())
    {
        $utility = new Utility;
        $log_file = $utility->getINIValue('DATAFILE');

        $this->log_file = $log_file;

        $this->params = array_merge($this->options, $params);

        //Create log file if it doesn't exist.
        if (!file_exists($log_file)) {
            fopen($log_file, 'w') or exit("Can't create $log_file!");
        }

        //Check permissions of file.
        if (!is_writable($log_file)) {
            //throw exception
            throw new Exception("ERROR: Unable to write to file!", 1);
        }
    }

    /**
    * Write method (write to data file)
    * @param string $message
    * @return boolean
    */
    public function write($temperature)
    {
        $write = (!$this->writeDataLog($temperature)) ? true : false;
        return $write;
    }

    /**
    * Write to output data file
    * @param string $message
    * @return void
    */
    public function writeDataLog($temp)
    {
        // open log file
        if (!is_resource($this->file)) {
            //set openLog to append
            $this->openLog('a');
        }

        //Grab time - based on timezone in php.ini
        $time = date($this->params['dformat']);

        // Write $time & $temp
        fwrite($this->file, "[$time] : $temp" . PHP_EOL);
    }

    /**
    * Open log file
    * @param string $type - append, write, etc
    * @return void
    */
    private function openLog($type)
    {
        $openFile = $this->log_file;
        // $type option = file operation w, a, etc..
        $this->file = fopen($openFile, $type) or exit("Can't open $openFile!");
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        if ($this->file) {
            fclose($this->file);
        }
    }
}
