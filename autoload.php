<?php

$base_dir = __DIR__;

require_once $base_dir . '/Controller/BaseController.php';
require_once $base_dir . '/Controller/ApiController.php';
require_once $base_dir . '/vendor/autoload.php';
require_once $base_dir . '/Models/database.php';
include_once $base_dir . "/Models/cors.php";
require_once $base_dir . '/web/secret_key.php';
require_once $base_dir . '/web/Token.php';
require_once $base_dir . '/env.config.php';