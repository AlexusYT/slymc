<?php
require "headerMain.php";
?>
<style>


    .playRect {
        margin-left: auto;
        margin-right: auto;
        width: 701px;
        height: 85px;
        border: 2px solid #7a7a7a;
        box-sizing: border-box;
        border-radius: 50px;
        /* display: flex; */
        /* flex-direction: row; */
        align-items: center;
    }

    .zoom {
        transition: 1s;
    }

    .zoom:hover {
        transform: scale(1.2);
    }

    .startButton {
        font-family: Comfortaa, serif;
        font-style: normal;
        font-weight: normal;
        font-size: 72px;
        text-align: center;
        margin-left: 30px;
        text-decoration: none;
        cursor: pointer;
    }

    .h1frame2 {
        margin-left: auto;
        margin-right: auto;
        margin-top: 320px;
        text-align: center;
        font-family: Comfortaa, serif;
        font-style: normal;
        font-weight: normal;
        font-size: 72px;
        line-height: 80px;
    }

    .liText {
        font-size: 30px;
        margin-top: 15px;
        margin-bottom: 0;
        font-family: Comfortaa, serif;
    }

    .liStyle {
        margin-right: auto;
        margin-left: auto;
        width: 700px;
        height: fit-content;
        border: 2px solid #7a7a7a;
        box-sizing: border-box;
        border-radius: 30px;
    }

    .serverButtons {
        margin-left: 10px;
        margin-right: 10px;
        text-decoration: none;
        margin-top: 20px;
        /*border: 2px solid #ffffff;*/
        box-sizing: border-box;
        border-radius: 50px;
        width: 400px;
        height: 200px;
    }

    .serverText {
        font-family: Comfortaa, serif;
        font-size: 28px;
        text-align: center;
        border: 2px solid #7a7a7a;
    }


</style>
<div>
    <div style="background-color: rgba(0, 0, 0, 0.65); width: 1400px; margin-left: auto; margin-right: auto; padding-top: 200px; padding-bottom: 400px;">
        <?php
        if(IS_USER_LOGGED)
        echo '<h2 class="h2">TODO:</h2>
        <div class="liStyle">
            <span class="liText" style="font-weight: bolder; font-size: 40px"> Сайт:</span>
            <ul class="liText">
                <li>Переписать login и register на ajax и поместить в хедер</li>
                <li>Перенести анимации на js и отказаться от библиотеки анимации на css</li>
                <li>Настроить отправку почты с почтового ящика сайта</li>
                <li>Переделать верификацию аккаунта (verify.php) по почте под текущие изменения </li>
            </ul>
            <span class="liText" style="font-weight: bolder; font-size: 40px"> Админка:</span>
            <ul class="liText">
                <li>Переделать все на новый лейаут из фигмы </li>
                <li>Допилить обновление юзера </li>
                <li>Исправить краш скрипта при попытке редактирования новых аккаунтов </li>
            </ul>
            <span class="liText" style="font-weight: bolder; font-size: 40px"> Лаунчер:</span>
            <ul class="liText">
                <li>Подумать над тем, как отображать инфу о пользователе </li>
            </ul>
        </div>';
        ?>

    <h1 class="h1"> Начни играть по-новому</h1>
    <h2 class="h2">Наши сервера подарят вам новые положительные эмоции</h2>
    <div class="playRect zoom">
        <a class="startButton">Начать играть</a>
    </div>
    <div>
        <div class="scoreStyle">
            <a class="line score"><u>Игроков онлайн:</u></a>
            <a class="line score"><u>Серверов онлайн:</u></a>
        </div>
        <div class="scoreStyle">
            <a class="line scoreNum">
                <?php
                try {
					$result = Utils::getDb()->getOne('SELECT SUM(`OnServer`) FROM `playersOnServers` ');
					if (!$result) throw new Exception();
                    echo $result;
				}catch (Throwable $e){
					echo "Неизвестно";
                }
                ?>
            </a>
            <a class="line scoreNum">
				<?php
				try {
					$result = Utils::getDb()->getOne('SELECT SUM(status="online") FROM serverStats');
					if (!$result) throw new Exception();
					echo $result;
				}catch (Throwable $e){
					echo "Неизвестно";
				}
				?>
            </a>
        </div>
    </div>
    <h1 class="h1frame2">Что нужно для начала игры?</h1>
    <h2 class="h2">Для комфортной игры необходимо выполнить следующие шаги:</h2>
    <div class="liStyle">
    <ul class="liText">
        <li>Зарегестрироваться на сайте</li>
        <li>Загрузить наш <a href="#">_лаунчер_FixMe</a></li>
        <li>Выбрать понравившуюся сборку</li>
        <li>После загрузки наслаждаться игрой на нашем сервере</li>
    </ul>
    </div>
    <h1 style="margin-top: 450px" class="h1frame2">Cписок серверов:</h1>
    <div class="center">

        <table>
            <?php
            $serversTotal = Utils::getDb()->getCol('SELECT `ServerName` FROM `servers`');
           if(sizeof($serversTotal)==0){
			   printf('<tr class="scoreStyle"><td><div class="serverText serverButtons">%s</div></td></tr>',
                   "Произошла внутренная ошибка. Список серверов недоступен");
            }else {

			   printf('<tr class="scoreStyle">');
			   for ($i = 0; $i < sizeof($serversTotal); $i++) {
				   printf('<td><div class="serverText serverButtons">%s</div></td>', $serversTotal[$i]);
				   if ($i % 3 == 2)
					   printf('</tr><tr class="scoreStyle">');
			   }
			   printf('</tr>');
		   }
            ?>
        </table>
    </div>
    <h1 class="h1frame2">Наши преимущества</h1>
    <h2 class="h2">Наши сервера обладают рядом преимуществ, таких как:</h2>
    <div class="liStyle">
        <ul class="liText">
            <li>Донат стоит как двушка в центре</li>
            <li>Вас могут забанить при входе</li>
            <li>Вас будут гриферить ибо приват платный</li>
            <li>При попытке пожаловаться вас посадят в тюрьму на неделю</li>
        </ul>
    </div>
    </div>
</div>