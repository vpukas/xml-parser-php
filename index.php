<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('index', 'DefaultController');
Routing::get('', 'DefaultController');
Routing::get('parse', 'DefaultController');
Routing::post('parse', 'DefaultController');
Routing::post('parseFile', 'FileController');

Routing::run($path);
