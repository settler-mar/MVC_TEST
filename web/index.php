<?php
namespace web;

use Core\Application;

require __DIR__ . '/../vendor/autoload.php';

$config = array_merge(
    require __DIR__ . '/../config.php',
    require __DIR__ . '/../config-local.php'
);

(new Application($config))->run();