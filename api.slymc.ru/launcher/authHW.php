<?php

if(count(array_diff_assoc(['startToken', 'data'], array_keys($_POST)))>0) sendError("invalid_request");

if(!($row = Utils::getDb()->getRow("SELECT private, `hash`, `timestamp` FROM `launcherAuth` INNER JOIN `launcherVersions` ON launcherAuth.platform = launcherVersions.platform WHERE public = '?s' LIMIT 1", $_POST['startToken']))){
	sendError("invalid_token");
}


try {
	$expiredAt = (new DateTime($row["timestamp"], new DateTimeZone("UTC")))->add(new DateInterval("PT5S"));
	$currentTime = new DateTime("now", new DateTimeZone("UTC"));
	if ($currentTime->diff($expiredAt)->invert) {
		throw new Exception();
	}
}catch (Throwable $throwable){
	deleteToken();
	sendError("expired_token");
}
try {
	if (!openssl_private_decrypt(base64_decode($_POST['data']), $receivedHash, "-----BEGIN PRIVATE KEY-----\n" . $row["private"] . "-----END PRIVATE KEY-----")) {
		throw new Exception();
	}
}catch (Throwable $throwable){
	deleteToken();
	sendError('invalid_data');
}
//TODO добавить проверку по железу
//sendBanned("Тест", "Test");

if($receivedHash != $row["hash"]){
	deleteToken();
	sendError('outdated');
}

deleteToken();
$token = Token::createToken(0, ["launcher.auth"], 24*60*60, $authToken);
if(!$token) sendError("internal_error");

sendSuccess(["authToken" => $authToken, "expiresAt" => $token->getExpiresAt()->getTimestamp()]);



function deleteToken(){
	Utils::getDb()->query("DELETE FROM `launcherAuth` WHERE public = '?s'", $_POST['startToken']);
}
