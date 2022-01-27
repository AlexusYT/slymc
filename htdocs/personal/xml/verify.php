<?php
if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()<6) {
	header('Location: ../');
	return;
}

Token::createVerifiedToken($user->getPlayerID(), array("user.update"), 60, "123456");
