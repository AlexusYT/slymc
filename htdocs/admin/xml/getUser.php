
<?php
if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";
try {
	$token = Token::getTokenInfo($_POST["accessToken"]);
	if(!$token) throw new Exception("invalid_token");
	if($token->isExpired()) throw new Exception("token_expired");
	if(!$token->isInScope("user.get")) throw new Exception("invalid_scope");
	if(!isset($_POST["username"])) throw new Exception("invalid_args");
}catch (Exception $e){
	$error = array("reason" => $e->getMessage(), "method"=>substr(basename(__FILE__), 0, -4));
	echo json_encode(array("error"=>$error));
	return;
}

$record = Utils::getDb()->getRow("SELECT * FROM players WHERE username = '?s' LIMIT 1", $_POST["username"]);

$document = new DOMDocument();
try {
	foreach ($record as $key => $val) {
		$button = null;
		if($key==="mail") $input = createInput($key, $val, "email");
		else if($key==="mailVerified" || $key==="twoFA" || $key==="license") $input = createCheckbox($key, $val);
		else if($key==="passChangedAt" || $key==="registeredAt" || $key==="lastOnlineAt") $input = createInput($key, $val, "datetime-local");
		else if($key==="password" || $key==="sign") $input = createInput($key, $val, "textarea");
		else $input = createInput($key, $val, "text");

		if($key==="sign"){
			$button = $document->createElement("button");
			$button->setAttribute("class", "editorTableButton");
			$button->setAttribute("id", "randomSign");
			$button->textContent = "Рандомно";
			$button->setAttribute("hidden", "");
			$button->setAttribute("onclick", "randomSign()");

			$resignCheck = createCheckbox("resign", true);
			$resignCheck->setAttribute("onclick", 'document.getElementById("randomSign").hidden = document.getElementById("resign").checked;');
			$document->appendChild(wrapRow("ResignOnUpdate", $resignCheck));
		}

		$document->appendChild(wrapRow($key, $input, $button));
	}
	$updateBtn = $document->createElement("button");
	$updateBtn->setAttribute("class", "editorTableButton");
	$updateBtn->setAttribute("id", "updateBtn");
	$updateBtn->textContent = "Обновить";
	$updateBtn->setAttribute("onclick", "update()");
	$document->appendChild($updateBtn);
} catch (DOMException $e) {
	echo "error";
}
echo $document->saveHTML();


/**
 * @throws DOMException
 */
function createCheckbox(string $key, $value){
	$input = createInput($key, $value, "checkbox");
	if($value==="1"||$value===true) $input->setAttribute("checked", "");
	$input->setAttribute("style", "width: unset");
	return $input;
}
/**
 * @throws DOMException
 */
function createInput(string $key, string $value, string $type){
	global $document;
	if($type==="textarea"){
		$input = $document->createElement('textarea');
		$input->setAttribute("class", "editorTableInput editorTableInputText");
		$input->setAttribute("readonly", "");
		$input->textContent = $value;
	}else{
		$input = $document->createElement('input');
		$input->setAttribute("class", "editorTableInput");
		$input->setAttribute("value", $value);
	}
	$input->setAttribute("type", $type);
	$input->setAttribute("name", $key);
	$input->setAttribute("id", $key);
	return $input;
}

/**
 * @throws DOMException
 */
function wraptd($element){
	global $document;
	$td = $document->createElement('td');
	if(gettype($element)==="string"){
		$td->textContent = $element;
	}else
		$td->appendChild($element);
	return $td;
}

/**
 * @throws DOMException
 */
function wrapRow(){
	global $document;
	$tr = $document->createElement('tr');
	$arg_list = func_get_args();
	for ($i = 0; $i < func_num_args(); $i++) {
		if($arg_list[$i]===null) continue;
		$tr->appendChild(wraptd($arg_list[$i]));
	}
	return $tr;
}



/*function populateTable(user){
		let editorTable = document.getElementById("editorTable");
		editorTable.innerHTML = "";
		for (const key of Object.keys(user)) {
			let input, button;
			if(key==="mail") input = createInput(key, user[key], "email");
			else if(key==="mailVerified" || key==="twoFA" || key==="license") input = createCheckbox(key, user[key]);
			else if(key==="passChangedAt" || key==="registeredAt" || key==="lastOnlineAt") input = createInput(key, user[key], "datetime-local");
			else if(key==="password" || key==="sign"){
				input = createInput(key, user[key], "textarea");
				input.classList.add("editorTableInputText");
				input.readOnly = true;
			}else input = createInput(key, user[key], "text");

			if(key==="sign"){
				button = document.createElement("button");
				button.classList.add("editorTableButton");
				button.id="randomSign";
				button.innerText = "Рандомно";
				button.hidden = true;
				button.onclick = function (){
					input.value = "";
					for (let i = 0; i < 128; i++) input.value += "0123456789abcdef".charAt(Math.floor(Math.random() * 16));
				};

				let resignCheck = createCheckbox("resign", true);

				resignCheck.onclick = function () {
					document.getElementById("randomSign").hidden = document.getElementById("resign").checked;
				};
				editorTable.append(wrapRow("ResignOnUpdate", resignCheck));
			}
			editorTable.append(wrapRow(key, input, button));
		}
		let updateBtn = document.createElement("button");
		updateBtn.classList.add("editorTableButton");
		updateBtn.id="updateBtn";
		updateBtn.innerText = "Обновить";
		updateBtn.onclick = function () {
			postJson("api/updateUser.php", {}).onload = function (){

            };
		};
		editorTable.append(updateBtn);
    }*/


/*function createCheckbox(key, value){
	let input = createInput(key, value, "checkbox");
	input.checked = value==="1"||value===true;
	input.style.width = "unset";
	return input;
}
function createInput(key, value, type){
	let input;
	if(type==="textarea"){
		input = document.createElement('textarea');
	}else{
		input = document.createElement('input');
	}
	input.classList.add("editorTableInput");
	input.type = type;
	input.value = value;
	input.id = input.name = key;
	return input;
}

function wraptd(element){
	let td = document.createElement('td');
	td.append(element);
	return td;
}
function wrapRow(...cells){
	let tr = document.createElement('tr');
	for (let i = 0; i < arguments.length; i++) {
		if(cells[i]===undefined) continue;
		tr.append(wraptd(cells[i]));
	}
	return tr;
}*/