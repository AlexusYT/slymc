<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";

if(isset($_POST["token"])&&isset($_POST["username"])){
	$data = file_get_contents("6b0dd4af7e7");
	unlink("6b0dd4af7e7");
	if(substr($data, 0, 128)!=$_POST["token"]){
		header('Location: ../');
		return;
	}
	$db = Utils::getDb();
	$user = User::getByName($_POST["username"]);
	if(!$user||!$user->resign()){
		echo "Не удалось обновить подпись для  ".$_POST["username"];
	}else
		echo "Подпись для ".$user->getDisplayUsername()." была обновлена";
	echo "<script> setTimeout(() => { window.location = \"../\"; }, 2000); </script>";

	return;
}