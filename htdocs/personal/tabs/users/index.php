<?php

if(!isset($GLOBALS["accessTokenGet"])){
	echo "Bad invocation";
	return;
}
?>
<script>
	function getUser(nick) {
		postXml('xml/getUser/', {"accessToken": "<?php echo $GLOBALS["accessTokenGet"]?>", "username":nick}).onload = function () {
			document.body.appendChild(this.responseXML.body.firstChild);
        };
	}
	function requestVerification(){

    }
	function update(nick) {
	}


    function randomSign(){
		let input = document.getElementById("sign");
	    input.value = "";
	    for (let i = 0; i < 128; i++) input.value += "0123456789abcdef".charAt(Math.floor(Math.random() * 16));
    }

</script>

<table class="box">
<?php
$columnsNames = ["userID" => "ID",
    "roleID" => "Тип",
	"displayUsername" => "Ник",
    "mail" => "Почта",
    "mailVerified" => "Подтв.",
	"password" => "Пароль",
	"passChangedAt" => "Сменён",
    "twoFA" => "2FA",
    "registeredAt" => "Регистрация",
    "lastOnlineAt" => "Онлайн"];
$dontDisplay = ["head","roleName", "skin", "cloak", "forumStatus"];
$records = Utils::getDb()->getAll("SELECT users.userID, userRoles.roleName, users.* FROM users INNER JOIN userRoles ON users.roleID = userRoles.roleID LIMIT 5");
$firstRow = false;
foreach($records as $record) {
	if(!$firstRow) {
		echo '<tr class="tableRow">';
		foreach ($record as $columns => $val) {
			if(in_array($columns, $dontDisplay)) continue;
            $localname = $columnsNames[$columns] ?? $columns;
			printf('<td><h2 style="margin: 0"><p title="%s">%s</p></h2></td>', $columns, $localname);
		}
		echo '</tr>';
		$firstRow = true;
	}
	echo '<tr class="tableRow">';
	$registerDate = null;
	$roleName = 0;
	foreach ($record as $columns => $val) {
        if($columns==="roleName") $roleName = $val;
		if(in_array($columns, $dontDisplay)) continue;
		$newVal = "";
		$events = "";
		$style = "";
		switch ($columns) {
			case "roleID": $newVal = $roleName; break;
			case "mailVerified":
			case "twoFA":
			case "license":
				$newVal = $val == 1 ? "Да" : "Нет";
				break;
			case "passChangedAt":
			case "lastOnlineAt":
			case "registeredAt":

				try {
					$now = new DateTime("now", new DateTimeZone("UTC"));
					$date = new DateTime($val, new DateTimeZone("UTC"));
					if($columns=="registeredAt") $registerDate = $date;
					if($columns=="lastOnlineAt"){
						$delta = $registerDate->diff($date);
						if($delta->invert){
							$style = "color: red;";
							$val.=". Последний онлайн раньше Регистрации";
						}
					}

					$delta = $date->diff($now);
					if($delta->days==0){
						if($delta->h!=0) $newVal=Utils::pluralizeMessage($delta->h, "час", "часа", "часов");
						elseif($delta->i!=0) $newVal=Utils::pluralizeMessage($delta->i, "минуту", "минуты", "минут");
						elseif($delta->s!=0) $newVal=Utils::pluralizeMessage($delta->s, "секунду", "секунды", "секунд");
						else $newVal = "Только что";
						if($delta->invert) $newVal.=" вперед"; else $newVal.=" назад";
					}
					elseif ($delta->days==1) $newVal = "Вчера";
					elseif ($delta->days==2) $newVal = "Позавчера";
					else $newVal = Utils::pluralizeMessage($delta->days, "день", "дня", "дней")." назад";
					if($delta->invert==1) {
						$style = "color: red;";
						$val.=". Дата в будущем";
					}
				}catch (Exception $e){

				}
				break;
			case "password":
			case "sign":
				$newVal = substr($val, 0, 6) . "..." . substr($val, -3);
				break;
			case "mail":
				$val = substr($val, 0, strrpos($val, "@"));
				$val = substr($val, 0, 3) . "..." . substr($val, -2);
				break;
			case "username":
				$style = "cursor: pointer;";
				$events = 'onclick="getUser(\''.$val.'\')"';
				break;
		}

		if(strlen($newVal)!=0)

			printf('<td title="%s" %s><p  style="%s">%s</p></td>', $val, $events, $style, $newVal);
			/*printf('
<td class="tooltip " style="word-wrap: break-word;%s" %s> <p>%s</p>
<span class="tooltiptext">%s</span>
</td>', $style, $events, $newVal, $val);*/
		else
			printf('<td style="%s" %s><p>%s</p></td>', $style, $events, $val);
	}
	echo '</tr>';

}
?>

</table>

<?php
