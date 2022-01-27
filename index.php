<?php
require "headerMain.php";
?>
<div>
    <div style="background-color: rgba(0, 0, 0, 0.65); width: 1400px; margin-left: auto; margin-right: auto; padding-top: 200px; padding-bottom: 400px;">
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