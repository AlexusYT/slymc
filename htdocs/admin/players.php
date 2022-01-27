<?php

if(!isset($GLOBALS["accessTokenGet"])){
	echo "Bad invocation";
	return;
}


?>
<script>
	function get(nick) {
		postXml('xml/getUser.php', {"accessToken": "<?php echo $GLOBALS["accessTokenGet"]?>", "username":nick}).onload = function () {
            openPopup();

			let editorTable = document.getElementById("editorTable");
			editorTable.innerHTML = this.response;

            let content = document.getElementById("content");
            let progress = document.createElement('div');
            progress.id="progress";
            progress.innerHTML="updated"
            content.prepend(progress)
            setTimeout(() => {
                content.removeChild(progress)
            }, 2000);
        };
	}
	function update(nick) {
		postXml('xml/updateUser.php', {"verificationCode": "123456", "username":nick}).onload = function () {
			openPopup();

			let editorTable = document.getElementById("editorTable");
			editorTable.innerHTML = this.response;

			let content = document.getElementById("content");
			let progress = document.createElement('div');
			progress.id="progress";
			progress.innerHTML="updated"
			content.prepend(progress)
			setTimeout(() => {
				content.removeChild(progress)
			}, 2000);
		};
	}

	function postXml(address, fields){
		let data = "", delim = "";
		for (const key of Object.keys(fields)) {
			data+=delim+key+"="+fields[key];
			delim = "&";
		}
		const xhr = new XMLHttpRequest();
		xhr.open("POST", address, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		//xhr.responseType = 'document';
		xhr.send(data);
		return xhr;
    }

    function randomSign(){
		let input = document.getElementById("sign");
	    input.value = "";
	    for (let i = 0; i < 128; i++) input.value += "0123456789abcdef".charAt(Math.floor(Math.random() * 16));
    }


	function openPopup(){
		let popup = document.getElementById("popup");
		let content = document.getElementById("content");
		popup.hidden = false;
		content.style.overflow = "clip";
	}
	function closePopup(){
		let popup = document.getElementById("popup");
		let content = document.getElementById("content");
		popup.hidden = true;
		content.style.overflow = "";
	}
</script>
<div id="popup" style="width: -moz-available; width: -webkit-fill-available; height: 100%;background: #0000008c;z-index: 2;position: absolute;top: 0; left: 230px;" hidden="">
	<div id="close" style="height: 20px; width: 20px; background: red; cursor: pointer" onclick="closePopup()"> X </div>
	<div id="editor" style="width: 50%; background: #333333;">
		<table id="editorTable">


		</table>
	</div>
</div>
<table>
<?php
$columnsNames = ["playerID" => "ID", "mail" => "Почта", "mailVerified" => "Подтверждена", "displayUsername" => "Ник", "role" => "Type"];
$records = Utils::getDb()->getAll("SELECT users.userID, usersRoles.roleName, users.* FROM users INNER JOIN usersRoles ON role = usersRoles.roleID LIMIT 15");
$firstRow = false;
foreach($records as $record) {
	if(!$firstRow) {
		echo '<tr>';
		foreach ($record as $columns => $val) {
			if($columns=="roleName") continue;
			$columns = $columnsNames[$columns] ?? $columns;
			printf('<td class="valuesTableCell"><h2 style="margin: 0">%s</h2></td>', $columns);
		}
		echo '</tr>';
		$firstRow = true;
	}
	echo '<tr class="valuesTableRow">';
	$registerDate = null;
	$roleId = 0;
	foreach ($record as $columns => $val) {
		$newVal = "";
		$events = "";
		$style = "";
		switch ($columns) {
			case "roleName":
				$roleId = $val;
				$columns = "";
				break;
			case "role": $newVal = $roleId; break;
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
					else $newVal = Utils::pluralizeMessage($delta->days, "день", "дня", "деней")." назад";
					if($delta->invert==1) {
						$style = "color: red;";
						$val.=". Дата в будущем";
					}
				}catch (Exception $e){

				}
				break;
			case "password":
			case "sign":
				$newVal = substr($val, 0, 16) . " ... " . substr($val, -19);
				break;
			case "mail":
				$val = substr($val, 0, strrpos($val, "@"));
				$val = substr($val, 0, 3) . "..." . substr($val, -2);
				break;
			case "username":
				$style = "cursor: pointer;";
				$events = 'onclick="get(\''.$val.'\')"';
				break;
		}
		if($columns=="") continue;
		if(strlen($newVal)!=0)
			printf('
<td class="tooltip valuesTableCell " style="word-wrap: break-word;%s" %s> %s
<span class="tooltiptext">%s</span>
</td>', $style, $events, $newVal, $val);
		else
			printf('<td  class="valuesTableCell" style="%s" %s>%s</td>', $style, $events, $val);
	}
	echo '</tr>';

}
?>

</table>

<?php
