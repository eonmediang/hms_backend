<?php

require __DIR__.'/../vendor/autoload.php';

date_default_timezone_set('Africa/Lagos');
ob_start();

require_once __DIR__.'/../Config.php';

$CFG->server_env ? error_reporting(0) : error_reporting(-1);

//	Include core functions
require_once __DIR__.'/../Core.func.php';

//	Include other functions
require_once __DIR__.'/../lib/functions.php';

//	Include App 
require_once 'App.php';

//	Include custom init file
require_once __DIR__.'/../custom_init.php';