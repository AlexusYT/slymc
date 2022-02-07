<?php
//echo __DIR__;
//if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strrpos(__DIR__, "www")+3) . DIRECTORY_SEPARATOR);
//if (!defined('API_PATH'))  define('API_PATH', ROOT_PATH."api.slymc.ru" . DIRECTORY_SEPARATOR);
//if (!defined('SITE_PATH'))  define('SITE_PATH', ROOT_PATH."slymc.ru" . DIRECTORY_SEPARATOR);
//if (!defined('LIB_PATH')) define('LIB_PATH', ROOT_PATH."lib" . DIRECTORY_SEPARATOR);

require_once "data/Token.php";
require_once "data/User.php";
require_once "SafeMySQL.php";
require_once "Utils.php";