<?php
const PAGE_TITLE = "Личный кабинет";

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
const HEADER_TYPE = "accountPage";
require ROOT_PATH."headerMain.php";
require_once ROOT_PATH."data/User.php";

/*if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()>2) {
	header('Location: ../');
	die;
}*/
if (IS_USER_LOGGED){
	echo "Юзер";
}else{
	header('Location: ../');
	die;
}

echo '

<div style="background-color: rgba(0, 0, 0, 0.65); width: 1400px; margin-left: auto; margin-right: auto; padding-top: 60px; padding-bottom: 200px;">
<div style="margin-top: 110px">
    <h1 class="h1"> Личный кабинет</h1>
    <h2 class="h2">Здесь вы можете редактировать информацию о игроке</h2>
</div>
<table style="margin-left: auto; margin-right: auto; width: fit-content;">
    <td>
    <div class="skinRect">
        <h4 class="skinText">Ваш скин и плащ:</h4>
        <div class="line" style="height: 0;"></div>
    </div>
    </td>
    <td>
        <div class="playerProfileRect">
            <div class="playerProfileText">Ваш никнейм:<input type="text" class="playerProfileInput" name="nickname"><a href="" class="playerProfileText">Изменить</a></div>
            <div class="playerProfileText">Лицензия на HD-скин и плащ: Есть</div>
            <div class="playerProfileText">Ваши привилегии: Classic (истекает через 21 день) <a href="" class="playerProfileInput">Продлить</a></div>
            <div class="playerProfileText">Ваши рефералы: 5 (активных 2)</div>
        </div>
    </td>
</table>
</div>


';







