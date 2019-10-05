<?php

use app\Application;

// PSR-4 Autoloader.
require_once 'vendor/autoload.php';

$app = new Application();
$app->run();