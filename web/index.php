<?php

use app\Config;
use app\WebApplication;

// PSR-4 Autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

$app = new WebApplication(new Config());
$app->run();