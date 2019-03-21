<?php
namespace APP\Temperature;

/**
* @author  Drew D. Lenhart
* @since   March 13, 2019
* @link    https://github.com/dlenhart/project-cylon
* @version 1.0.0
*/

class Uptime
{

  /**
  * getUptime Method ( returns system start up date - epoch )
  * @return $validated
  */
    public function getUptime()
    {
        $os = $this->getSystemOS();

        if ($os === 'WIN') {
            /* Windows is the special case
            Did this mostly for testing on dev machine
            doubt this system will ever run on a windows
            server for production.*/

            try {
                $uptime = shell_exec('systeminfo | find "Time:"');
            } catch (Exception $e) {
                echo 'Caught Exception: ' . $e->getMessage();
            }

            if ($uptime) {
                $uptime = explode(": ", $uptime);
                $uptime = explode(", ", $uptime[1]);
                $date = $uptime[0] . " " . $uptime[1];
            } else {
                return false;
            }
        } elseif ($os === 'LIN' || $os ==='FRE') {
            try {
                $uptime = shell_exec('uptime -p -s');
                $date = $uptime;
            } catch (Exception $e) {
                echo 'Caught Exception: ' . $e->getMessage();
            }
        } else {
            //unknown os, really?
            return false;
        }

        $v = $this->convertDate($date);

        $validated = $v ? $v : false;
        return $validated;
    }

    /**
    * convertDate Method ( verify valid date & return epoch )
    * @param $date
    * @return $d
    */
    public function convertDate($date)
    {
        $d = strtotime($date);
        //strtotime will return false for bad input/unable to convert.
        if ($d === false || $d === '') {
            return false;
        }
        //return unix epoch
        return $d;
    }

    /**
    * getSystemOS Method ( get OS )
    * @return
    */
    public function getSystemOS()
    {
        return strtoupper(substr(PHP_OS, 0, 3));
    }
}
