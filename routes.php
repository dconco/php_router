<?php

require './index.php';

$route = new Route();

$views = __DIR__ . '/views/';

// create new routes
$route->add('/', $views . 'index.php');
$route->add('index', $views . 'index.php');

$route->add('login', $views . 'login.php');
$route->add('signup', $views . 'signup.php');

$route->add('new/upload', $views . 'upload.php');

$route->add('user/{username}', $views . 'profile.php');

$route->notFound($views . 'errors/404.php');
