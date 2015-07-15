<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$env = getenv('SYMFONY_ENV') ? : 'prod';
$debug = getenv('SYMFONY_DEBUG') === '1';

if (isset($_SERVER['HTTP_HOST']) && substr_count($_SERVER['HTTP_HOST'], '.dev')) {
    $env = 'dev';
    $debug = true;
}

if ($env != 'dev') {
    $loader = require_once __DIR__ . '/../app/bootstrap.php.cache';
} else {
    $loader = require_once __DIR__ . '/../app/autoload.php';
}

if ($debug) {
    Debug::enable();
}

require_once __DIR__ . '/../app/AppKernel.php';

$kernel = new AppKernel($env, $debug);

if ($env != 'dev') {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
