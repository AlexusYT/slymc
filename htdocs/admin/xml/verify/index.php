<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";
require_once ROOT_PATH."utils/Utils.php";
if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()>2) {
	header('Location: ../');
	return;
}

Token::createVerifiedToken($user->getUserID(), array("user.update"), 60, "123456");

$GLOBALS["DIALOG_NAME"] = "verify";
$GLOBALS["DIALOG_CONTENT"] = file_get_contents("verifyTemplate.html");
$result = include ROOT_PATH."popups/dialog.php";
echo $result;
