<html lang="ru">
<head>
	<title>Регистрация</title>
</head>
<body>
	<form action="register.php" method="post">

        <table>
            <caption>Регистрация</caption>
            <tr><td>E-mail</td><td><input type="email" name="email"></td></tr>
            <tr><td>Логин (ник)</td><td><input type="text" name="login"></td></tr>
            <tr><td>Пароль</td><td><input type="password" name="password"></td></tr>
            <tr><td>Повторить пароль</td><td><input type="password" name="passwordR"></td></tr>
        </table>
		<p><input type="submit"></p>
	</form>


</body>
</html>

<?php

/*
 * spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
 */


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
$email = $_POST["email"];
$login = $_POST["login"];
$password = $_POST["password"];

if($email=="") { echo "Почта не должна быть пустой"; return; }
if(!Utils::isEmail($email)) { echo "Указанный адрес почты не соответсвует общепринятым правилам"; return; }
if($login=="") { echo "Логин не должен быть пустой"; return; }
if(!preg_match("#^[A-z0-9\-]+$#", $login)) { echo "Логин может содержать только символы латинского алфавита, цифры и _-"; return; }
if($password=="") { echo "Пароль не должен быть пустой"; return; }
if($_POST["passwordR"]!=$password) { echo "Пароли должны совпадать"; return; }

$password = hash('sha512', $password);

if(!Utils::getDb()->query("INSERT INTO `users`(`username`, `displayUsername`, `mail`, `password`, `sign`) VALUES ('?s', '?s', '?s', '?s', '0')", strtolower($login), $login, $email, $password)){

    if(strstr(Utils::getDb()->getLastError(), "nickname")){
	    echo "Пользователь с таким никнеймом уже существует";
        return;
    }
    if(strstr(Utils::getDb()->getLastError(), "mail")){
		echo "Почта занята!";
		return;
	}
}
$user = User::getByName($login);
$user->resign();

$body = file_get_contents("utils/emailVerifyPattern.html");
Token::createToken($user->getUserID(), array("email"), 24*60*60, $str);
$body = str_replace("__token__", $str, $body);

$body = str_replace("__nick__", $login, $body);

if(!Utils::sendEmail($email, $login, $body, "Подтверждение адреса электронной почты")){
	echo "Произошла ошибка при отправке сообщения на почту";
	return;
}else{
	echo "Сообщение отправлено на почту";
}

if(!Token::createToken($user->getUserID(), array("web"), 24*60*60, $candy)){
    echo "Произошла внутреняя ошибка. Попробуйте войти с введенными данными";
    return;
}
setcookie("candy", $candy);

header('Location: ../');






















