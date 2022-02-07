<html lang="ru">
<head>
    <title>Авторизация</title>
</head>
<body>
<form action="login.php" method="post">

    <table>
        <caption>Авторизация</caption>
        <tr><td>E-mail или логин</td><td><input type="text" name="login"></td></tr>
        <tr><td>Пароль</td><td><input type="password" name="password"></td></tr>
    </table>
    <p><input type="submit"></p>
</form>
</body>
</html>


<?php

if(($token = Token::getTokenInfo($_COOKIE["candy"]))!=null){
	if(!$token->isInScope("web")){
		setcookie("candy","",1);
		header('Location: /');
	}else{
		header('Location: ../');
    }
	return;
}

if(sizeof($_POST)<=0) return;
$login = $_POST["login"];
$password = $_POST["password"];

if($login=="") { echo "Логин не должен быть пустой"; return; }
if(!preg_match("#^[A-z0-9\-@.]+$#", $login)) { echo "Логин может содержать только символы латинского алфавита, цифры и _-"; return; }
if($password=="") { echo "Пароль не должен быть пустой"; return; }

$user = null;
if(strpbrk($login, "@")) {
    if(!Utils::isEmail($login)){
	    echo "Почта не соответсвует общепринятым правилам";
	    return;
    }
	$user = User::getByEmail($login);
} else {
	$user = User::getByName($login);
}
if(!$user) {
    echo ('Неверный логин или пароль.');
    return;
}

if (!$user->checkSign()) {
    echo ("Хацкер. Ваш аккаунт заблокирован до выяснения обстоятельств.");
    return;
}

if (hash('sha512', $password) != $user->getPassword()) {
    echo ("Неверный логин или пароль.");
    return;
}
if(!Token::createToken($user->getUserID(), array("web"), 24*60*60, $candy)){
	echo "Произошла внутреняя ошибка";
	return;
}

setcookie("candy", $candy);
header('Location: ../');




























