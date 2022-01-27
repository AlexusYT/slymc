<?php
$supportedPlatforms = ["windows", "linux"];

if(!in_array($_POST['platform'], $supportedPlatforms)) sendError("unsupported_os");

try {
	$res = openssl_pkey_new(array('private_key_bits' => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA));

	openssl_pkey_export($res, $privKey);

	$pubKey = str_replace(["-----BEGIN PUBLIC KEY-----", "\n-----END PUBLIC KEY-----"], "", openssl_pkey_get_details($res)["key"]);
	$privKey = str_replace(["-----BEGIN PRIVATE KEY-----\n", "\n-----END PRIVATE KEY-----"], "", $privKey);
	$conn = new mysqli('sql209.epizy.com', 'epiz_30774026', 'x6hyKkXWBV', 'epiz_30774026_slymc');
	$conn->set_charset("utf8mb4");
	if (!($stmt = $conn->prepare("INSERT INTO launcherAuth (public, private, platform) VALUES (?,?,?)"))) throw new Exception();
	$hash = hash("sha256", $pubKey);
	if (!$stmt->bind_param("sss", $hash, $privKey, $_POST['platform'])) throw new Exception();
	if (!$stmt->execute()){

		//echo  $conn->error;
		throw new Exception();
	}
	if (!$stmt->close()) throw new Exception();
	if (!$conn->close()) throw new Exception();
	echo json_encode(array(
		'status' => 'ok',
		'token' => $pubKey
	));
}catch (Throwable $throwable){
	//echo $throwable;
	sendError("internal_error");
}


function sendError($error){

	die(json_encode(array(
		'status' => 'error',
		'error' => $error
	)));
}