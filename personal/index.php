<?php
const PAGE_TITLE = "Личный кабинет";
require "header.php";
if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

if (!defined('ADMIN_PATH')) define('ADMIN_PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";

if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()>2) {
	header('Location: ../');
	return;
}
Token::createToken($user->getUserID(), array("user.get"), 3600*2, $GLOBALS["accessTokenGet"]);

?>
<body>
</body>






