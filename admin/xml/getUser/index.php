
<?php
if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";
require_once ROOT_PATH."utils/Utils.php";
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

$record = Utils::getDb()->getRow("SELECT * FROM users WHERE username = '?s' LIMIT 1", $_POST["username"]);

$document = new DOMDocument();

try {
	$div = $document->appendChild($document->createElement("div"));
	$table = $div->appendChild($document->createElement("table"));

	foreach ($record as $key => $val) {
		$button = null;
		if($key==="mail") $input = createInput($key, $val, "email");
		else if($key==="mailVerified" || $key==="twoFA" || $key==="license") $input = createCheckbox($key, $val);
		else if($key==="passChangedAt" || $key==="registeredAt" || $key==="lastOnlineAt") $input = createInput($key, str_replace(" ", "T", $val), "datetime-local");
		else if($key==="password" || $key==="sign") $input = createInput($key, $val, "textarea");
		else $input = createInput($key, $val, "text");

		if($key==="sign"){
			$button = $document->createElement("button");
			$button->setAttribute("class", "mainBack");
			$button->setAttribute("id", "randomSign");
			$button->textContent = "Рандомно";
			$button->setAttribute("hidden", "");
			$button->setAttribute("onclick", "randomSign()");

			$resignCheck = createCheckbox("resign", true);
			$resignCheck->setAttribute("onclick", 'document.getElementById("randomSign").hidden = document.getElementById("resign").checked;');
			$table->appendChild(Utils::wrapRow($document, "ResignOnUpdate", $resignCheck));
		}

		$table->appendChild(Utils::wrapRow($document, $key, $input, $button));
	}
	$updateBtn = $document->createElement("button");
	$updateBtn->setAttribute("class", "mainBack");
	$updateBtn->setAttribute("id", "updateBtn");
	$updateBtn->textContent = "Обновить";
	$updateBtn->setAttribute("onclick", 'postXml("xml/verify/", {"accessToken": "'.$_POST["accessToken"].'"}).onload = function () {
			document.body.appendChild(this.responseXML.body.firstChild);
		};');
	$updateBtn->setAttribute("style", "display: block;margin-left: auto;width: fit-content;");
	$div->appendChild(Utils::wrapRow($document, $updateBtn));
} catch (DOMException $e) {
	echo "error";
}
$GLOBALS["DIALOG_NAME"] = "userEdit";
$GLOBALS["DIALOG_CONTENT"] = $document->saveHTML();
$result = include ROOT_PATH."popups/dialog.php";
echo $result;


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
		$input->setAttribute("class", "mainBack editorTableInput editorTableInputText");
		$input->setAttribute("readonly", "");
		$input->textContent = $value;
	}else{
		$input = $document->createElement('input');
		$input->setAttribute("class", "mainBack editorTableInput");
		$input->setAttribute("value", $value);
	}
	$input->setAttribute("type", $type);
	$input->setAttribute("name", $key);
	$input->setAttribute("id", $key);
	return $input;
}


