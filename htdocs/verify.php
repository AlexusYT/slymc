<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

require ROOT_PATH."data/User.php";

if(!isset($_GET["t"])){
	echo "Пусто";
	return;
}
$token = $_GET["t"];
if(!preg_match("#^[A-z0-9]+$#", $token)){
	echo "Символы";
	return;
}


$db = Utils::getDb();

try {

	$user = User::getByToken($token);
	if(!$user||!$user->getToken()||!$user->getToken()->isValid("email")) throw new Exception();

	if(!$db->query("UPDATE users SET users.MailVerified = 1 WHERE users.userID = '?s'", $user->getUserID())) return false;
	$user->resign();

	echo "Почта подтверждена. Нажмите <a href='../..'> сюда </a>, чтобы вернуться обратно или подождите 2 секунды для автоматического возврата";
	echo "<script> setTimeout(() => { window.location = \"../\"; }, 2000); </script>";

} catch (Exception $e) {
	echo "ошибка $e";

}
$db->close();
