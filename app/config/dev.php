<?php

// include the prod configuration
require __DIR__.'/prod.php';

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'chicncheap',
    'user'     => 'root',
    'password' => '',
);
// enable the debug mode
$app['debug'] = true;
