<?php

if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()>2) {
	header('Location: ../');
	return;
}

Token::createVerifiedToken($user->getUserID(), array("user.update"), 60, "123456");

$GLOBALS["DIALOG_NAME"] = "verify";
$GLOBALS["DIALOG_CONTENT"] = file_get_contents("verifyTemplate.html");
$result = include "popups/dialog.php";
echo $result;
