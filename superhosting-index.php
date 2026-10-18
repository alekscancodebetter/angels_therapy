<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$applicationPath = '/home/nikkiebl/angels-therapy-experiment/current';

if (is_file(__DIR__.'/.deploying') || ! is_file($applicationPath.'/vendor/autoload.php')) {
    http_response_code(503);
    header('Retry-After: 60');
    exit('The website is being updated. Please try again shortly.');
}

if (file_exists($maintenance = $applicationPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $applicationPath.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $applicationPath.'/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
