<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "epiz_30774026")+13) ."/htdocs/");
if (!defined('API_PATH'))  define('API_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH."utils/Utils.php";
require_once ROOT_PATH."data/Token.php";
require_once ROOT_PATH."data/User.php";
require_once API_PATH."APIUtils.php";