<?php

if(!isset($_POST['startToken'])||!isset($_POST['data'])){
	sendError("invalid_token");
}else{
	$pubKey = base64_decode($_POST['startToken']);
	
	if(!endsWith($pubKey, "-----END PUBLIC KEY-----\n")){
		echo json_encode(array(
			'status' => 'error',
			'error' => 'Invalid token'
		));
		return;
	}
	//echo $pubKey;//FIXME проверить на возможность провести инъекцию
	
	//из БД выбрать запись по $pubKey, сделанную в предыдущем вызове, чтобы получить privKey, timestamp и platform. Запрос совмещенный, чтобы из другой таблицы была взята хеш-сумма по platform и записалась в $launcherHash
	
	//по timestamp проверить, если токен, создан более 10 секунд назад, то удалить запись с ним и выдать ошибку
	/*
	echo json_encode(array(
		'status' => 'error',
		'error' => 'Expired token'
	));
	return;
	
	*/
	
/*FIXME убрать, когда появится БД*/
/*FIXME*/	$launcherHash = "530a66646cfafb1d2a51e2c914e2052ed7df099c567edc1251a587bc69342bc7";
/*FIXME*/	$privKey = base64_decode("LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2Z0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktnd2dnU2tBZ0VBQW9JQkFRRGxuTHRBVFBTVXVqNkwKQmh4Q2VyQkRyNzk0NldpeGFoWEM1UWZlYWp5ZlF4ZTdSNk9Wb0ZudVdtNVNBWjRMQ0pZRTBMZnZyVW5ueGZ2YwpYWlNhOExRUDIxNmdWcWRGSUtpNkxNQ09GdW9YVnZwMXBDQ05qSVd2WGxTaVMzbjRGTGtqS2x2QU90QjBwdzJDCmxXcWZod1I4Rk1tUXg0Y2pMNEU1UThVTUlCMkpXY3ZCYktkU1pBbm1GOGU0UStWRkYySW1LcUJYQk1paThZNVAKTDZPcVBhSllEYmVGT0tkK0tYaVNGWWpNMDFET0VIQThDTmx2MHJMT3RYRXZxdzh1YWFETUplSnpld1A2NnU0MApVeXFEVXBJOFVZNmxBd29PTlJ6N3doTmpVMlcwdjVWQk56WFI3blY3NXRXK0RRTWQ0aEk0REh0U3hMVWhWYkpUClhpZys0aDRwQWdNQkFBRUNnZ0VCQU13bEdXY0dCTDduQ0F4YkZtMFUzL3FPRzFMSlUwZko1aWN0c2wxUlh5eksKWGlNb2dvelowSnRXMDVjZDBRSkdVL3RjQnUyVnlJdkZQelNpRHluaXNIVVl1WDBkRGdNc0VsZlV5aTZLdVA2ZgpXbmVWd1V1WWtKTm03eUNNc3BpYTlEQUx6UitRS3g1TUdIcnRsYXJZUkdoOW91aVNQZGhPdURuTnd6Z1FwYklpCjhJdnlja1diVXdiMmZiNHBWRmwvZGNtMWJEVVNhZ2swNzF6MWx5bXg2RTBNS1MwTTI5R3Y1Rk5aQ0QyS0FEK2QKNVEwNW1nb2VhTGdWbXhWWHVkSmJhZ2U0eTNFaWZucmVHeTViNzlrMnRFbFhpbmdpdDlaM2JaaW5DWnRBVk5IRgovdDlSQmtLVUtWVjlydWNFN051M09NNDYrMUpnMzZMZGZuU2hOODJtYzhFQ2dZRUEvM2w5T3RvRGRhQXJFSWZlClc5Q1hOOU1DUTJ1V25PM2NyY1N2eU45SDBBKzd5cElKWHZ6cjNmZWxYODFRVnRYNVhkTmhnaWRjMjhQSG5wRDEKdXhqcVRHc25hTHNRbWxRNWovVlFkQm04QzNZME5aVnRHSFRZZytuSUM1Uk5KQkVIK0xCakVUQkx1RUNnblpzWApSUFU0TytZSmswZGRDQ2o2WUtKcjJJQzV0eDBDZ1lFQTVoV2dHa2JMU1Jsbk0vcWJWbDlwcC8yNzdGUnczUXhGClFYaC8zeWVBQTZ1QlpwTDFVZ2xNc096eEl5YjVFTTdqSjh6WlNoWVM0dGRvMDV2VTE1MndzWFJVb0hIbWdSSSsKTW1va0YraGoraVdUd3hxSGNnSEJHbW9OL0E4cE1YSFVkeGVmR1JISU9ZOWpPMi80UmlTMWZJZFFheHJ3ZytzaQoyUi9iZHFPVGVYMENnWUFRNmdjN2hVR2pZUVpZNHRYNitEVjYwYktkcWhyZnF2UG9ZVlJPRlZKWTJTSFN5SlpzClBveVROcGt4WFpPTEhFR1ZUN0J1QUpNcVRhMEN0NUE5WWVucFExTHBvVEQ1TnNoVTJxWUgrY2cwYmhBSTJDclAKNnJTSEQweGFUK2hIa2dVWUZ2MklIczEwbG5yTDFIL1c1ZkZpTGRuR2NYd2NWVEkyZ2Fwb1BDV1BsUUtCZ1FDSApFTnJUa2d0ejlmMm5mYzRDZmpBLzlhdURxRzQ0MVNNeXM5SWM1Y3M0WEUyeU1VTGh6YU8vbU5oVmttTlRncC9HCmxYSlpFMnd5emRFenA1N2lsQ2ROaE1USkN4UU5ZUEF5R0N6bisvdjB0R3B4ZGZsYzY3cTREdG4yeGMrZ3Z2bkMKajVOTjNDcDEzNzZZL2JuNU0wTjJ2dGh1aDNuNWR1Y1dIcVZ1bDhmUzRRS0JnSFBYK2dsazZhTkFXR0tlZ0xEUwpWa3pRaTRJZi9WQk53T2M5NWJnUC9oSi9FL0ZqdDVMUTNuQVZGcllCTXZ0QjJNVG1ULzU5WUluNmtWZERCS3J6CktjeVNTeWhlclVuZVVmQ1JmOVE4NU1FZnJCTzV0TmREZjc0VGc3MTMvT2IrL1BnUFpkemgxRWxoRUlsOWFXTVUKcUlrTEtxQ0NhU3czNXErM0ZnSjR0Ri9QCi0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0K");
/*FIXME убрать, когда появится БД*/
	
	/*if(!openssl_private_decrypt(base64_decode($_POST['data']), $receivedHash, $privKey)){
		
		echo json_encode(array(
			'status' => 'error',
			'error' => 'Invalid data'
		));
		//удалить запись с токеном
		return;
	}*/
	
	/*if($receivedHash != $launcherHash){
		echo json_encode(array(
			'status' => 'error',
			'error' => 'Outdated'
		));
	}else{*/
		$res = openssl_pkey_new(array('private_key_bits' => 2048, "private_key_type" => OPENSSL_KEYTYPE_RSA));

		openssl_pkey_export($res, $privKey);

		$pubKey = openssl_pkey_get_details($res)["key"];
		//FIXME $privKey, $pubKey и timestamp здесь идут в таблицу HardwareAuth

		echo json_encode(array(
			'status' => 'ok',
			'hardCheckToken' => base64_encode($pubKey),
		));
	//}
	
	//удалить запись с токеном



}

function endsWith($haystack, $needle): bool
{
	$length = strlen($needle);
	return !($length > 0) || substr($haystack, -$length) === $needle;
}

function sendError($error){
	die(json_encode(array(
		'status' => 'error',
		'error' => $error
	)));
}


