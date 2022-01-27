<?php
const PAGE_TITLE = "Тест";
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
    <style>
        .categories {
            float: left;
            width: 210px;
            left: 0;
            z-index: 2;
        }

        .categoryButton{
            border-color: #7e7e7e;
            border-style: outset;
            text-decoration: none;
            width: 200px;
            height: 30px;
            line-height: 30px;
            font-size: large;
            font-family: Arial, serif;
            display: block;
            text-align: center;
            border-radius: 10px;
            margin: 4px 10px 4px 4px;

        }
        .categoryButtonSelected{
            border-style: inset;
        }
    </style>
	<aside class="floating mainBack categories">
		<?php
        if(!isset($_GET["category"])) $_GET["category"] = "summary";
		$categories = ["summary"=>"Общая информация",
            //"modlist"=>"Список модов",
            //"modpacks"=>"Модпаки",
            "users"=>"Игроки",
            //"playersOnServers"=>"Игроки на серверах",
			//"servers"=>"Сервера",
			//"serversStats"=>"Статусы серверов",
			//"tokens"=>"Токены"
        ];
		foreach ($categories as $nameEn => $nameRu){
			$class = "";
            if($_GET["category"] == $nameEn){
	            $class = "categoryButtonSelected";
            }

			printf('<a class="controlBack categoryButton %s" href="?category=%s">%s</a>', $class, $nameEn, $nameRu);
		}

		define("CATEGORY_NAME", $categories[$_GET["category"]]);
		?>
	</aside>
    <div class="content" id="content">
        <h1><?php echo CATEGORY_NAME?></h1>
	<?php
	require ADMIN_PATH."tabs/".$_GET["category"]."/index.php";
    ?>
    </div>
</body>


<?php

return;
require_once ROOT_PATH."data/Token.php";
require_once ROOT_PATH."data/User.php";

$db = Utils::getDb();


$user = null;


if(isset($_GET["user"])&&($_GET["user"]=="alexus_xx@mail.ru"||$_GET["user"]=="ggaaggaaggaa@yandex.ru")){
	$token = Utils::generateToken();
	$expiringDate = new DateTime("now", new DateTimeZone("utc"));
	$expiringDate->add(new DateInterval("PT3M"));
	if(!file_put_contents("b3449534da73f9", $token.serialize($expiringDate))){
		echo "failed to save";
		header('Location: ../');
		return;
	}
	$body = str_replace("__token__", $token, file_get_contents(ROOT_PATH."utils/emailAdminPattern.html"));
	Utils::sendEmail("alexus_xx@mail.ru", "Alexus_XX", $body, "Запрос на вход в админ-панель");
	header('Location: ../');
	return;
}else if(isset($_GET["t"])){
	$data = file_get_contents("b3449534da73f9");
	$currentDate = new DateTime("now", new DateTimeZone("utc"));
	if($currentDate->diff(unserialize(substr($data, 128)))->invert || substr($data, 0, 128)!=$_GET["t"]){
		header('Location: ../');
		return;
	}
	unlink("b3449534da73f9");

	$reloadToken = Utils::generateToken();
	$expiringDate = new DateTime("now", new DateTimeZone("utc"));
	$expiringDate->add(new DateInterval("PT60M"));
	if(!file_put_contents("b3449534da73f9", $reloadToken.serialize($expiringDate))){
		echo "failed to save";
		header('Location: ../');
		return;
	}
	echo '<form method="get">';
	echo '<input type="text" name="t" value="'.$reloadToken.'" hidden="hidden">';
	echo '<input type="submit" value="Перезагрузить">';
	echo '</form>';

	$users = array("ID" => User::getById($db, 1), "Username" => User::getByName("alexus_xx"), "Email" => User::getByEmail("alexus_xx@mail.ru"));
	$lastUser = null;
	foreach ($users as $name => $userDB){
		if(serialize($lastUser)==serialize((array)$userDB)) continue;

		echo '<div >';
		echo '<table style="max-width: 32%; border: 1px solid black; float:left;margin: 0 5px;">';
		printf('<caption>Информация по аккаунту (%s)</caption>', $name);
		foreach ((array)$userDB as $fieldName => $fieldValue) {
			if($lastUser!=null&&$lastUser[$fieldName]==$fieldValue){
				continue;
			}

			$fieldName = ucfirst(substr($fieldName, 6));
			$style = "";
			if($fieldName=="Sign"){
				$style .= "color: ".($userDB->getSign() == $userDB->getNewSign() ? "green" : "red");
			}elseif ($fieldName=="Head"||$fieldName=="Skin"||$fieldName=="License"||$fieldName=="Cloak"||$fieldName=="Token"){
				continue;
			}
			$text = "<tr style='".$style."'><td>%s</td><td style='word-wrap: anywhere;".$style.";max-width: 33%%;'>";
			$text = Utils::getText($fieldValue, $text);

			printf($text.'</td></tr>', $fieldName, $fieldValue);
		}
		echo '</table>';
		echo '</div>';
		$user = $userDB;
		$lastUser = (array)$userDB;
	}

	/*$token = Utils::generateToken();
	if(!file_put_contents("6b0dd4af7e7", $token, FILE_APPEND)){
		echo "failed to save";
		header('Location: ../');
		return;
	}*/

}
if(!($user||($user = User::getByToken($_COOKIE["candy"]))||$user->getToken()||$user->getToken()->isInScope("web")||$user->checkSign()||$user->getRole()>=6)) {
	echo $user->getNewSign();
	$db->close();
	header('Location: ../');
	return;
}
/*
echo '<div >';
echo '<table style="max-width: 32%; border: 1px solid black; float:left;margin: 0 5px;">';
echo '<form action="updateSign.php" method="post">';
foreach ((array)$user as $fieldName => $fieldValue) {
	$fieldName = ucfirst(substr($fieldName, 6));
	if ($fieldName=="Token") continue;

	$text = '<tr><td>%s</td><td><input type="text" name="%s" value="';
	$text = Utils::getText($fieldValue, $text);
	printf($text.'"></td></tr>', $fieldName, $fieldName, $fieldValue);
}
echo '<tr><td><input type="submit" value="Обновить профиль"></td></tr>';
echo '</form>';
echo '</table>';
echo '</div>';*/





