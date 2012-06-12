<?php
define('BASE_PATH', '/var/www/1stdibs_shipping/');

require_once ('autoload.php');
BENCHMARK::activate(); // activate and deactivate because you can't call the class before you include this file, but we still want an early page benchmark. 
BENCHMARK::createPoint("Page start");
BENCHMARK::deactivate();

FPX::activate();
ERROR::activate();
//DEBUG::activate();
require_once(BASE_PATH . 'lib/util/common.php');
require_once(BASE_PATH . "config/db.php");
require_once(BASE_PATH . "config/config.auth.php");