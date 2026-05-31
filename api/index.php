<?php

define('CF_SERVERLESS', true);

$scriptRoot = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'script';
chdir($scriptRoot);

require $scriptRoot . DIRECTORY_SEPARATOR . 'index.php';
