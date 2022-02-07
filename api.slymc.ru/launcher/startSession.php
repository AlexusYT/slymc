<?php

$supportedPlatforms = ["windows", "linux"];

if(!in_array($_POST['platform'], $supportedPlatforms)) sendError("unsupported_os");

try {
	$res = openssl_pkey_new(array('private_key_bits' => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA));

	openssl_pkey_export($res, $privKey);

	$pubKey = str_replace(["-----BEGIN PUBLIC KEY-----", "\n-----END PUBLIC KEY-----"], "", openssl_pkey_get_details($res)["key"]);
	$privKey = str_replace(["-----BEGIN PRIVATE KEY-----\n", "\n-----END PRIVATE KEY-----"], "", $privKey);
	$currentDate = new DateTime("now", new DateTimeZone("utc"));
	$createdAt = $currentDate->format("Y-m-d H:i:s");
	if (!Utils::getDb()->query("INSERT INTO launcherAuth (public, private, platform, timestamp) VALUES ('?s','?s','?s', '?s')", Utils::getSha256($pubKey), $privKey, $_POST['platform'], $createdAt)){
		throw new Exception();
	}
	sendSuccess(['token' => $pubKey]);
}catch (Throwable $throwable){
	sendError("internal_error");
}

