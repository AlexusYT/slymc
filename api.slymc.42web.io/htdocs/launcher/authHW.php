<?php
if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "epiz_30774026")+6) . DIRECTORY_SEPARATOR);
require_once "../../../htdocs/data/User.php";
if(!isset($_POST['startToken'])||!isset($_POST['data'])) sendError("invalid_request");


$conn = new mysqli('sql209.epizy.com', 'epiz_30774026', 'x6hyKkXWBV', 'epiz_30774026_slymc');
$conn->set_charset("utf8mb4");