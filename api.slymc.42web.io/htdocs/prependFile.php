<?php

if (!defined('MAIN_PATH'))  define('MAIN_PATH', substr(__DIR__, 0, strpos(__DIR__, "epiz_30774026")+13) ."/htdocs/");
if (!defined('API_PATH'))  define('API_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

require_once MAIN_PATH."utils/Utils.php";
require_once MAIN_PATH."data/Token.php";
require_once API_PATH."APIUtils.php";