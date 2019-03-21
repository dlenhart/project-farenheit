<?php
namespace APP\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Schema;
use APP\Models\Sample;
use APP\Models\User;

/**
 * Class InstallController
 * @package InstallController\Controller
 */

class InstallController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    //Install - Create Database & Monitor table
    public function createDatabase(Request $request, Response $response, $args)
    {
        // create database & sample table
        $db = $this->utility->getINIValue('DB_FILE');
        $baseDir = __DIR__ . '/../../data/' . $db;

        if (!file_exists($baseDir)) {
            echo ">__INFO, creating database..... " . $db .  "<br />";
            // Create new file
            $fh = fopen($baseDir, 'w') or die('Unable to create database!');
            if ($fh) {
                echo ">__SUCCESS, database successfully created!<br />";
            }
            fclose($fh);

            echo ">__INFO, creating a table with some sample data......<br /><br />";
            //check for sample table
            if (DB::connection('sqlite')->getSchemaBuilder()->hasTable('Sample')) {
                echo '>__WARNING, table already exsists!<br />';
                // Echo out whats in sample table!
                $output = new Sample;
                $output = Sample::all();
            } else {
                echo '>__INFO, creating sample table.....<br /><br />';
                $create = DB::connection('sqlite')->select("CREATE TABLE Sample (
                  id INTEGER PRIMARY KEY AUTOINCREMENT,
        					name CHAR(100) NOT NULL,
        					email CHAR(50) NOT NULL
        				)");
                // Insert a dummy record using Model
                $insert = new Sample;
                $insert->name = 'Test User 1';
                $insert->email = 'admin@localhost';
                $insert->save();
                if ($insert) {
                    echo ">__SUCCESS, sample data saved!<br />";
                    // Echo out whats in table
                    $output = new Sample;
                    $output = Sample::all();
                    echo ">__" . $output;
                }
            }
        } else {
            echo ">__WARNING, database already exsists..... " . "<br />";
        }
    }

    // Create User Table
    public function createUserTable(Request $request, Response $response, $args)
    {
        // CHECK if Users table exsists
        $table = "Users";
        $check = DB::connection('sqlite')->getSchemaBuilder()->hasTable($table);
        //create if no table.
        if (!$check) {
            echo ">__NO table found, creating table<br />";
            $create = DB::connection('sqlite')->select("CREATE TABLE $table (
        				id INTEGER PRIMARY KEY AUTOINCREMENT,
        				name VARCHAR(255),
        				email VARCHAR(255) NOT NULL,
        				password VARCHAR(255) NOT NULL,
        				created_at TIMESTAMP,
        				updated_at TIMESTAMP
    			   )");

            echo ">__SUCCESS, ready to create new user.";
        } else {
            echo ">__WARNING, User table already exsists!";
        }
    }
    
}
