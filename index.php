<?php

//if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

define('APP_DEBUG',true);

define('APP_PATH','./app/');

define ( 'RUNTIME_PATH', './runtime/' );

require './framework/ThinkPHP.php';