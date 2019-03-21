<?php
namespace APP\Utility;

/**
* @author  Drew D. Lenhart
* @since   March 8, 2019
* @link    https://github.com/dlenhart/project-cylon
* @version 1.0.0
*/

class Install
{
    /**
    * checkInstalled Method ( checks for installed file created durring install )
    * @param string $string
    * @return boolean
    */
    public function checkInstalled()
    {
        //installation creates a file in config directory.
        //check if file exsists
        $file = __DIR__ . "/../../data/installed";

        if (!file_exists($file)) {
            return false;
        }

        //use file function. get first line!
        $file_data = array_slice(file($file), 0, 1);

        if (strtolower($file_data[0]) == 'true') {
            return true;
        }

        return false;
    }

    /**
    * markAsInstalled Method ( creates installed file )
    * @param string $string
    * @return boolean
    */
    public function markAsInstalled()
    {
        //create installed file to mark the program as installed.
        $file = __DIR__ . "/../../data/installed";
        //simply overrite the file if it exsists already
        $handle = fopen($file, 'w') or die('Cannot open file:  '. $file);
        fwrite($handle, 'true'); //write true.
      fclose($handle); //close file

      //use checkInstalled method to verify file write and return boolean
        if (!$this->checkInstalled()) {
            return false;
        }

        return true;
    }
}
