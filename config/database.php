<?php
/*
    Author:  Drew D. Lenhart
    Page: database.php *Default database
    Desc: Database connection info - see config.ini
*/

use Illuminate\Database\Capsule\Manager as Capsule;
use APP\Utility\Utility;

$capsule = new Capsule;
$utility = new Utility;

$capsule->addConnection(
    array(
        'driver'    => 'mysql',
        'host'      => $utility->getINIValue('DB_HOST'),
        'database'  => $utility->getINIValue('DB_NAME'),
        'username'  => $utility->getINIValue('DB_USER'),
        'password'  => $utility->getINIValue('DB_PASSWORD'),
        'prefix'    => '',
    ),
    "default"
);

$capsule->addConnection(
    array(
        'driver'   => 'sqlite',
        'database' => __DIR__ . '/../data/' . $utility->getINIValue('DB_FILE'),
        'prefix'   => ''
    ),
    "sqlite"
);

$capsule->bootEloquent();
$capsule->setAsGlobal();
