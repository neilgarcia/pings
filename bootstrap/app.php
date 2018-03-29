<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/db.php';

$app = new Silex\Application([
  'debug' => true
]);

$app->register(new Silex\Provider\DoctrineServiceProvider, [
  'db.options' => [
    'host' => 'localhost',
    'dbname' => 'pings',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'pdo' => $dbc
  ]
]);
