<?php

return [
    'settings' => [
        // If you put in production, change it to false
        'displayErrorDetails' => true,
        // Monolog settings
        'log' => [
            'name' => 'project-farenheit',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
