
<?php
/*if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH."data/User.php";
try {
	$token = Token::getTokenInfo($_POST["accessToken"]);
	if(!$token) throw new Exception("invalid_token");
	if($token->isExpired()) throw new Exception("token_expired");
	if(!$token->isInScope("user.get")) throw new Exception("invalid_scope");
}catch (Exception $e){
	$error = array("reason" => $e->getMessage(), "method"=>substr(basename(__FILE__), 0, -4));
	echo json_encode(array("error"=>$error));
	return;
}

$records = Utils::getDb()->getAll("SELECT * FROM players WHERE username IN ('?p') OR playerID IN ('?p') OR mail IN ('?p') LIMIT 100", get("usernames"), get("ids"), get("email"));
$result = array();
foreach($records as $record) {
	$field = array();
	foreach ($record as $columns => $val) {
		$field += [lcfirst($columns) => $val];
	}
	$result[] = $field;
}

//echo "<h1>test</h1>";
echo json_encode(["result"=>$result]);

function get($str): string
{
	$values = array();
	foreach (explode(",", $_POST[$str]) as $value){
		if(strlen($value)==0) continue;
		$values[] = Utils::getDb()->parse("?s", str_replace(" ", "", $value));
	}
	$result=implode(",", $values);
	if(strlen($result)==0) return "''";
	return $result;
}*/