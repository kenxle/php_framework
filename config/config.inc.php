<?php
define('BASE_PATH', realpath(__DIR__ . "/../") . "/");	
define('TEMPLATES_PATH', BASE_PATH. 'templates/');
define('CSS_FILE_PATH', BASE_PATH. 'docroot/css/');
define('WEB_ROOT', 'http://k3.local/');
define('IMAGES_PATH', WEB_ROOT. 'images/');
define('CSS_WEB_PATH', WEB_ROOT. 'css/');
define('CSS_ROOT', BASE_PATH. 'www/css/');
define('JS_WEB_PATH', WEB_ROOT. 'js/');
define('JS_ROOT', BASE_PATH. 'www/js/');

// not using the db
// require_once ('db.php');

$inc = array(get_include_path(), BASE_PATH . 'lib');
set_include_path(implode(PATH_SEPARATOR, $inc));

require_once ('autoload.php');
BENCHMARK::activate(); // activate and deactivate because you can't call the class before you include this file, but we still want an early page benchmark. 
BENCHMARK::createPoint("Page start");
BENCHMARK::deactivate();

FPX::activate();
ERROR::activate();
//DEBUG::activate();
require_once(BASE_PATH . 'lib/util/common.php');
//require_once(BASE_PATH . "config/db.php");
//require_once(BASE_PATH . "config/config.auth.php");


$js_min_off = true;
$css_min_off = true;

